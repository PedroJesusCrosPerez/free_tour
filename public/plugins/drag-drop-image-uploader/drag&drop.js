$(function () {
    $('.input-images').imageUploader({
        extensions: ['.jpg', '.jpeg', '.png'],
        mimes: ['image/jpeg', 'image/png'],
        maxSize: undefined,
        maxFiles: 1,
    });

    $('#tabs').tabs();


    $(".connectedSortable").sortable({
        connectWith: ".connectedSortable"
    }).disableSelection();
});