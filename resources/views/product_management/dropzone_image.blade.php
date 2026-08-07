<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dropzone Image Upload</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<style>
    body {
    background: rgb(243, 244, 245);
    height: 100%;
    color: rgb(100, 108, 127);
    line-height: 1.4rem;
    font-family: Roboto, "Open Sans", sans-serif;
    font-size: 20px;
    font-weight: 300;
    text-rendering: optimizeLegibility;
}

h1 { text-align: center; }

.dropzone{
    background:#fff;
    border:2px dashed #0d6efd;
    border-radius:8px;
    min-height:220px;
    padding:20px;
    transition:.3s;
}

.dropzone:hover{
    border-color:#198754;
}

.dropzone .dz-message{
    font-size:18px;
    color:#666;
}

.dropzone .dz-preview .dz-image{
    width:120px;
    height:120px;
    border-radius:8px;
}

.dropzone .dz-preview{
    margin:10px;
}
/* Hide default "Remove file" text */
.dropzone .dz-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    color: #fff !important;
    border-radius: 50%;
    text-indent: -9999px; /* Hide text */
    overflow: hidden;
    cursor: pointer;
    z-index: 1000;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
}

/* Create X icon */
.dropzone .dz-remove::before {
    content: "✕";
    position: absolute;
    inset: 0;
    text-indent: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
}

/* Hover effect */
.dropzone .dz-remove:hover {
    background: #b02a37;
    transform: scale(1.1);
}
</style>

</head>
<body>

<h1>DropzoneJS File Upload Demo</h1>
<SECTION>
  <DIV id="dropzone">
      <form id="imageDropzone"
      class="dropzone"
      action="{{ route('products.uploadImage') }}"
      method="POST"
      enctype="multipart/form-data">
      @csrf
      <DIV class="dz-message needsclick">
        Drop files here or click to upload.
      </DIV>
    </FORM>
  </DIV>
</SECTION>

<br/>


<script>
    Dropzone.autoDiscover = false;
    document.addEventListener("DOMContentLoaded", function () {

    new Dropzone("#imageDropzone", {

        url: "{{ route('products.uploadImage') }}",

        paramName: "image", // The name that will be used to transfer the file

        maxFiles: 5,    // Maximum number of files

        maxFilesize: 3, // Maximum file size in MB

        acceptedFiles: ".jpg,.jpeg,.png,.gif,.webp", // Accepted file types

        addRemoveLinks: true, // Show remove links on each file preview

        parallelUploads: 5,    // Number of files to upload in parallel

        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },

        success: function(file, response){
            console.log(response);
        },

        error: function(file, response){
            console.log(response);
        }

    });

});
</script>


</body>
</html>

