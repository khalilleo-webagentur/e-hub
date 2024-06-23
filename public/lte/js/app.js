$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 6000);

    let selector = $('.selector');

    if (selector.length) {
        selector.on('click', function (e) {
            e.preventDefault();
            $('.ID').val($(this).attr('data-id'));
        });
    }

    // copy to clipboard
    $('.copyToClipBoard').on('click', function (e) {
        e.stopPropagation();
        let content = document.getElementById('copyToClipBoard').innerHTML;
        copyContent(content);
        alert('Data has been copied to clipboard. [' + content + ']');
    });

    // remove modal after click
    $('.rmModal').on('click', function () {
        setTimeout(function () {
            $('.removeModalAfterClick').modal('hide');
            $('input').val('');
        }, 1500);
    });
});

// clipboard
async function copyContent(text) {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}