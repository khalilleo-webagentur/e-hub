const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block'],
    ['link', 'image', 'video', 'formula'],

    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
    [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
    [{ 'direction': 'rtl' }],                         // text direction

    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
    [{ 'font': [] }],
    [{ 'align': [] }],

    ['clean']                                         // remove formatting button
];

if ($('#editor').length) {
    let quill = new Quill('#editor', {
        placeholder: 'Compose an epic...',
        modules: {
            syntax: true,
            toolbar: toolbarOptions,
            history: {
                delay: 2000,
                maxStack: 500,
                userOnly: true
            },
        },
        theme: 'snow',
    });

    let = formId = document.getElementById('formSave');

    if (formId) {
        formId.addEventListener('click', function (e) {
            let blogHtml = quill.container.firstChild.innerHTML;
            let input = document.createElement('input');
            input.setAttribute('name', 'content');
            input.setAttribute('value', blogHtml);
            input.setAttribute('type', 'hidden')
            formId.appendChild(input);
        });
    }
}