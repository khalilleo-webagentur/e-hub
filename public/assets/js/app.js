$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 10000);

    if ($('#clipboard').length) {
        $('#clipboard').on('click', function (e) {
            let link = $('#link').text();
            copyContent(link);
            swal('', 'Link (' + link + ') is copied to clipboard.', 'success');
        });
    }

    if ($('.three-dots').length) {
        $('.three-dots').on('click', function () {
            $('.dataId').val($(this).attr('data-id'));
        });
    }

    let modal = $('.modal');
    modal.on('shown.bs.modal', function () {
        $(this).find('[autofocus]').focus();
    });

    if ($('.btnLike').length) {

        let i = $('.btnLike').attr('data-id');

        if (isLocalStorageAvailable && window.localStorage.getItem('y3q7d2z6g6f6c2r7' + i) === '1') {
            $('.btnLike').prop('disabled', 'true');
        }

        $('.btnLike').on('click', function () {
            let i = $('.btnLike').attr('data-id');
            if (isLocalStorageAvailable) {
                window.localStorage.setItem('y3q7d2z6g6f6c2r7' + i, '1');
            }
        });
    }

    if ($('.btnDisLike').length) {

        let i = $('.btnDisLike').attr('data-id');

        if (isLocalStorageAvailable && window.localStorage.getItem('x4k3m9g2r5e6m2k7' + i) === '1') {
            $('.btnDisLike').prop('disabled', 'true');
        }

        $('.btnDisLike').on('click', function () {
            let i = $('.btnDisLike').attr('data-id');
            if (isLocalStorageAvailable) {
                window.localStorage.setItem('x4k3m9g2r5e6m2k7' + i, '1')
                window.localStorage.removeItem('y3q7d2z6g6f6c2r7' + i);
            }
        });
    }

    // remove modal after click
    $('.rmModal').on('click', function () {
        setTimeout(function () {
            $('.removeModalAfterClick').modal('hide');
            $('input').val('');
        }, 1500);
    });
});

function isLocalStorageAvailable() { return typeof (Storage) !== "undefined" }

async function copyContent(text) {
    try {
        if (isLocalStorageAvailable) {
            await navigator.clipboard.writeText(text);
        }
    } catch (err) {
        swal('', 'clipboard is not available on your Browser.', 'warning');
    }
}

// blur images
window.addEventListener('load', function() {
    const image = document.querySelector('img');
    if (image) {
        image.style.filter = 'blur(0)';
    }
});
