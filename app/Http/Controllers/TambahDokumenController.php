<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DokumenKategori;
use App\Models\DokumenKeluar;
use App\Models\DokumenMasuk;
use App\Models\DokumenTemplate;
use App\Models\Instansi;
use App\Models\User;
use App\Notifications\SignDocumentKeluars;
use App\OfficeConverter;
use App\PdfOptimzer;
use App\TagPrefixFixer;
use App\TemplateProcessor;
use DateTime;
use DateTimeZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Html;
use Spatie\PdfToText\Pdf;

class TambahDokumenController extends Controller {

    /**
     * Menampilkan halaman tambah_dokumen
     */
    public function tambah_dokumen() {
        $instansi = Instansi::all();
        $kategori = DokumenKategori::all();
        $template_dok = DokumenTemplate::all();
        return view(
            "admin.tambah_dokumen.tambah_dokumen",
            compact("instansi", "kategori", "template_dok")
        );
    }

    public function jsonGetDataDokTemplate($id) {
        $data = DokumenTemplate::findOrfail($id);
        return response()->json($data);
    }

    /**
     * Menyimpan data dokumen ke database
     */
    public function simpan(Request $request) {
        // Mendapatkan nilai dari field 'jenis_dokumen' dari request
        $jenis = $request->jenis_dokumen;


        if ($jenis == "dokumen_masuk") {

            $hasil = $this->__simpanDokumenMasuk($request);
        } elseif ($jenis == "dokumen_keluar") {

            $hasil = $this->__simpanDokumenKeluar($request);
        }

        // Memeriksa apakah data berhasil disimpan
        if ($hasil instanceof DokumenMasuk) {

            return redirect()
                ->route("admin.tambah_dokumen")
                ->with("pesan", "Data berhasil di simpan!");
        } elseif ($hasil instanceof DokumenKeluar) {

            return redirect()
                ->route("admin.tambah_dokumen")
                ->with("pesan", "Data berhasil di simpan!");
        } else {

            return redirect()
                ->route("admin.tambah_dokumen")
                ->with("error", "Data gagal disimpan!");
        }
    }

    /**
     *
     *
     * @param array $request
     * @return DokumenMasuk
     *
     * @throws \Illuminate\Http\RedirectResponse
     *
     *
     */
    protected function __simpanDokumenMasuk($request): DokumenMasuk | RedirectResponse {
        // dd($request);


        $dateTime = new DateTime("now", new DateTimeZone("Asia/Jakarta"));
        $dtFormat = $dateTime->format("dmY_His");


        $data = [
            "nama_dokumen" => $request->nama_dokumen,
            "penerima" => $request->nama_penerima,
            "pengirim" => $request->nama_pengirim,
            "tanggal_masuk" => $request->tanggal_masuk,
            "keterangan" => $request->keterangan,
            "instansi_id" => $request->dinas_id,
            "dokumen_kategori_id" => $request->kategori_id,
            "user_id" => auth()->user()->id,
        ];

        // Memeriksa apakah file diunggah dan merupakan file dokumen yang valid
        if (
            $request->hasFile("file_dokumen") &&
            $request->file("file_dokumen")->isValid()
        ) {

            $file = $request->file("file_dokumen");


            $nama_dokumen = preg_replace("/\s+/", "_", $request->nama_dokumen);


            $file_name = $nama_dokumen . "_" . $dtFormat;
            // Membuat nama file baru dengan format tanggal dan waktu saat ini
            $file_name = $nama_dokumen . "_" . $dtFormat . "." . $file->getClientOriginalExtension();

            // Memeriksa apakah file yang diunggah adalah file dokumen yang valid (DOC atau DOCX)
            if ($this->__cekFileDokumen($file)) {

                $uploadPath = $file->storeAs("dokumen/masuk", $file_name . '.docx', "public");


                $convert = new OfficeConverter(storage_path("app/public/" . $uploadPath));
                $pdfFileName = pathinfo($convert->convertTo($file_name . '.pdf'), PATHINFO_FILENAME) . '.pdf';


                Storage::disk("public")->delete($uploadPath);


                $pdfFileCompress = new PdfOptimzer(storage_path("app/public/dokumen/masuk/" . $pdfFileName), storage_path("app/public/dokumen/masuk"));
                $pdfCompressName = $pdfFileCompress->convertPdf();


                Storage::disk("public")->delete("dokumen/masuk/" . $pdfFileName);


                $data["lampiran"] = "dokumen/masuk/" . pathinfo($pdfCompressName, PATHINFO_FILENAME) . ".pdf";
            } elseif ($file->getClientOriginalExtension() == "pdf") {

                $uploadPath = $file->storeAs("dokumen/masuk", $file_name . '.pdf', "public");


                $pdfFileCompress = new PdfOptimzer(storage_path("app/public/" . $uploadPath), storage_path("app/public/dokumen/masuk"));
                $pdfCompressName = $pdfFileCompress->convertPdf();


                Storage::disk("public")->delete($uploadPath);


                $data["lampiran"] = "dokumen/masuk/" . pathinfo($pdfCompressName, PATHINFO_FILENAME) . ".pdf";
            } else {

                return redirect()->back()->with("error", "File yang diunggah harus berupa file dokumen (DOC, DOCX, atau PDF)!")->withInput();
            }
        } else {

            return redirect()->back()->with("error", "File dokumen harus diunggah!")->withInput();
        }

        $content = Pdf::getText(
            Storage::disk("public")->path($data["lampiran"]), config('libpath.pdf_to_text_path')
        );

        $content = preg_replace('/[^A-Za-z0-9\s]/', '', $content);
        $content = Str::limit($content, 60000);

        $data['pdf_content'] = $content;
        // Membuat record 'DokumenMasuk' baru di database dengan array data
        return DokumenMasuk::create($data);
    }

    /**
     * Menyimpan dokumen keluar ke dalam database dan mengunggah file dokumen yang terkait.
     *
     * @param array $request
     * @return DokumenKeluar
     *
     * @throws \Illuminate\Http\RedirectResponse
     *
     *
     */
    protected function __simpanDokumenKeluar($request): DokumenKeluar | RedirectResponse {
        // Mendapatkan tanggal dan waktu saat ini di zona waktu Asia/Jakarta
        $dateTime = new DateTime("now", new DateTimeZone("Asia/Jakarta"));
        $dtFormat = $dateTime->format("dmY_His");

        // Membuat array dengan data yang akan disimpan ke database untuk 'dokumen_keluar'
        $data = [
            "nama_dokumen" => $request->nama_dokumen,
            "penerima" => $request->nama_penerima,
            // "pengirim" => $request->nama_pengirim,
            "tanggal_keluar" => $request->tanggal_keluar,
            "keterangan" => $request->keterangan,
            "status" =>
            $request->pengajuan_ke_pimpinan == "ya"
            ? "Menunggu Persetujuan"
            : "Dikirimkan",
            "persetujuan" => $request->pengajuan_ke_pimpinan,
            "instansi_id" => $request->dinas_id,
            "dokumen_kategori_id" => $request->kategori_id,
            "user_id" => auth()->user()->id,
        ];

        // Nama file menggunakan nama_dokumen yang sudah dirubah (mengganti spasi dengan underscore) dan ditambahkan dengan waktu
        $nama_dokumen = preg_replace("/\s+/", "_", $request->nama_dokumen);

        // Memeriksa apakah file diunggah dan merupakan file dokumen yang valid
        if ($request->hasFile("file_dokumen") && $request->file("file_dokumen")->isValid()) {
            // Mendapatkan file yang diunggah dari request
            $file = $request->file("file_dokumen");

            // Membuat nama file baru dengan format tanggal dan waktu saat ini
            $file_name = $nama_dokumen . "_" . $dtFormat . "." . $file->getClientOriginalExtension();

            // Memeriksa apakah file yang diunggah adalah file dokumen yang valid (DOC atau DOCX)
            if ($this->__cekFileDokumen($file)) {

                $uploadPath = $file->storeAs("dokumen/keluar", $file_name . '.docx', "public");


                $convert = new OfficeConverter(storage_path("app/public/" . $uploadPath));
                $pdfFileName = pathinfo($convert->convertTo($file_name . '.pdf'), PATHINFO_FILENAME) . '.pdf';


                Storage::disk("public")->delete($uploadPath);


                $pdfFileCompress = new PdfOptimzer(storage_path("app/public/dokumen/keluar/" . $pdfFileName), storage_path("app/public/dokumen/keluar"));
                $pdfCompressName = $pdfFileCompress->convertPdf();


                Storage::disk("public")->delete("dokumen/keluar/" . $pdfFileName);


                $data["lampiran"] = "dokumen/keluar/" . pathinfo($pdfCompressName, PATHINFO_FILENAME) . ".pdf";
            } elseif ($file->getClientOriginalExtension() == "pdf") {

                $uploadPath = $file->storeAs("dokumen/keluar", $file_name . '.pdf', "public");


                $pdfFileCompress = new PdfOptimzer(storage_path("app/public/" . $uploadPath), storage_path("app/public/dokumen/keluar"));
                $pdfCompressName = $pdfFileCompress->convertPdf();


                Storage::disk("public")->delete($uploadPath);


                $data["lampiran"] = "dokumen/keluar/" . pathinfo($pdfCompressName, PATHINFO_FILENAME) . ".pdf";
            } else {

                return redirect()->back()->with("error", "File yang diunggah harus berupa file dokumen (DOC, DOCX, atau PDF)!")->withInput();
            }
            // cek jika file template di pilih
        } elseif ($request->pilihTemplate != null || $request->pilihTemplate != '') {
            $hasil = $this->__prosesTemplateDokumen($request, $nama_dokumen . "_" . $dtFormat);
            $data["lampiran"] = $hasil;
        } else {
            // Mengembalikan redirect dengan pesan kesalahan
            return redirect()->back()->with("error", "File dokumen harus diunggah!")->withInput();
        }

        // dd($request->all());

        if ($request->pengajuan_ke_pimpinan == "ya") {
            // ambil data kategori dan instansi untuk notifikasi
            $kategori = DokumenKategori::find($request->kategori_id);
            $instansi = Instansi::find($request->dinas_id);
            // Mengirim notifikasi ke telegram
            $user = User::where('role', 'pimpinan')->first();
            try {
                $user->notify(new SignDocumentKeluars($request->nama_dokumen, $kategori->nama_kategori, $instansi->nama_instansi));
            } catch (\Exception $e) {
                logger("Whatsapp Notification: ".$e);
                // Mengembalikan redirect dengan pesan kesalahan
                // return redirect()->back()->with("error", "Gagal mengirim notifikasi ke pimpinan!")->withInput();
            }
        }
        // Membuat record 'DokumenKeluar' baru di database dengan array data
        return DokumenKeluar::create($data);
    }

    protected function __prosesTemplateDokumen(Request $request, $nama_dokumen): string | RedirectResponse {
        $fileTemplate = DokumenTemplate::findOrFail($request->pilihTemplate)->file;

        // cek jika file template tidak ditemukan
        if (!Storage::disk('public')->exists($fileTemplate)) {
            return redirect()->back()->with("error", "File template tidak ditemukan!")->withInput();
        }

        // memulai proses template
        $template = new TemplateProcessor(storage_path('app/public/' . $fileTemplate));

        // inisialisasi section
        $htmlSection = new Section(0);

        // cek jika ada konten yang dikirimkan
        if ($request->var_KONTEN !== null || $request->var_ISISURAT !== null) {
            // tambahkan konten ke section
            // Html::addHtml($htmlSection, TagPrefixFixer::addNamespaces(TagPrefixFixer::cleanHTML($request->var_KONTEN)));
            Html::addHtml($htmlSection, TagPrefixFixer::addNamespaces($request->var_KONTEN));
        }

        // lakukan perulangan dengan form yang dikirimkan
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'var_')) {
                // hapus var_ karakter
                $key = substr($key, 4);
                if ($key === 'KONTEN' || $key === 'ISI_SURAT' || $key === 'ISI-SURAT' || $key === 'ISI SURAT' || $key === 'ISISURAT') {
                    $template->setComplexBlock($key, $htmlSection);
                } else {
                    $template->setValue($key, $value);
                }
            }
        }
        $lokasiFile = 'dokumen/keluar/' . $nama_dokumen . '.docx';
        try {
            // cek folder dokumen keluar
            if (!Storage::disk('public')->exists('dokumen/keluar')) {
                Storage::disk('public')->makeDirectory('dokumen/keluar');
            }
            // simpan file
            $template->saveAs(storage_path('app/public/' . $lokasiFile));
        } catch (\Exception $e) {
            // Mengembalikan redirect dengan pesan kesalahan
            return redirect()->back()->with("error", "Gagal memproses dokumen!, Error: " . $e)->withInput();
        }
        // return nama file
        return $lokasiFile;
    }

    /**
     * Memeriksa apakah ekstensi file yang diunggah termasuk dalam daftar ekstensi yang diperbolehkan.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    protected function __cekFileDokumen($file) {
        $daftarExtensi = ["doc", "docx"];

        $extensiDariFile = $file->getClientOriginalExtension();

        return in_array($extensiDariFile, $daftarExtensi);
    }

}