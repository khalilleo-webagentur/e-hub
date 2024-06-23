let dropzoneWrapper = $('.dropzone-wrapper'),
    dropzone = $(".dropzone");

function readFile(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            let fileName = document.querySelector(".showFileNameAfterUpload");
            fileName.innerHTML = '<span class="fa fa-check-circle text-success fa-sm"></span><br>';

            fileName.innerHTML += input.files[0].name;
            if (input.files.length > 1) {
                fileName.innerHTML += ' ...'
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}

dropzone.change(function () {
    readFile(this)
});

dropzoneWrapper.on('dragover', function (e) {
    e.preventDefault(); e.stopPropagation(); $(this).addClass('dragover')
});

dropzoneWrapper.on('dragleave', function (e) {
    e.preventDefault(); e.stopPropagation(); $(this).removeClass('dragover')
});

const cancel = document.querySelector('.cancelFile');

if (cancel) {
    cancel.addEventListener("click", function (e) {
        e.preventDefault(); document.querySelector(".importFile").value = '';
        document.querySelector(".showFileNameAfterUpload").innerHTML = '<i class="fa fa-upload fa-sm"></i><p>Choose your file or drag it here.</p>'
    })
}