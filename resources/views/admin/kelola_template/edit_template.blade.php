@extends('layout.index')
@section('title', 'Kelola Template Dokumen')
@section('content')

<div class="main-content side-content pt-0">

    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Kelola Template Dokumen</h2>

                </div>
            </div>

            <div class="card custom-card">
                <div class="card-header">
                    Edit Data Template
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.template_dokumen.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Ups!</strong> Ada beberapa masalah dengan input Anda.<br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-15 col-md-6">

                                    <div class="form-group">
                                        <label>Nama Dokumen</label>
                                        <input type="text" name="nama_dokumen" class="form-control" required
                                            value="{{ $data->nama ?? old('nama_dokumen') }}">
                                        <div class="text-danger">
                                            @error('nama_dokumen')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Kategori Dokumen</label>
                                        <select name="kategori_dokumen" id="kategori_dokumen" class="form-control"
                                            required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($kategori as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $data->dokumen_kategori_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">
                                            @error('kategori_dokumen')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>File Template</label>
                                        <input type="file" name="file_template" class="form-control" required
                                            value="{{ old('file_template') }}">
                                        <div class="text-danger">
                                            @error('file_template')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="flex-grow-1 d-flex flex-row">
                                        {{-- <details class="docx-thumbnails h-100">
                                            <summary></summary>
                                            <div id="thumbnails-container" class="docx-thumbnails-container"></div>
                                        </details> --}}
                                        <div id="document-container" class="overflow-auto flex-grow-1 h-100"></div>
                                    </div>

                                    <!-- Frame -->
                                    {{-- <iframe src="{{ asset('/laraview/#../storage/' . $data->file) }}" width="100%"
                                    height="500px"></iframe> --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
let currentDocument = null;
const docxOptions = Object.assign(docx.defaultOptions, {
    debug: true,
    experimental: true,
    breakPages: true,
    inWrapper: true,
    useBase64URL: true,
    ignoreHeight: true,
    // ignoreWidth: true,
    trimXmlDeclaration: true,
    ignoreLastRenderedPageBreak: true,
    renderHeaders: true,
    renderFooters: true,
    renderFootnotes: true,
    renderEndnotes: true,
});

const container = document.querySelector("#document-container");

async function renderDocx(file) {
    currentDocument = file;

    if (!currentDocument)
        return;

    // optional, find and convert all tiff images
    let docxBlob = preprocessTiff(currentDocument);
    // render document
    let res = await docx.renderAsync(docxBlob, container, null, docxOptions)
    // optional - render thumbnails
    renderThumbnails(container, document.querySelector("#thumbnails-container"));
    // console.log(res);
}


fetch("{{ Storage::url($data->file) }}")
    .then(response => response.blob())
    .then(blob => {
        // console.log("Fetched the file:", blob);
        const file = new File([blob],
            "{{ pathinfo($data->file, PATHINFO_FILENAME) . '.' . pathinfo($data->file, PATHINFO_EXTENSION) }}", {
                type: blob.type
            });
        // console.log("Created a file:", file);

        // const reader = new FileReader();
        // reader.onloadend = () => {
        //     const base64data = reader.result;
        // };
        // reader.readAsDataURL(blob);
        renderDocx(file);
    })
    .catch(error => console.error('Error fetching the file:', error));
</script>
@endsection