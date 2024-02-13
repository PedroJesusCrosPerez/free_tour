$(function () {
    var textarea = document.getElementById('description');
    sceditor.create(textarea, {
        format: 'bbcode',
        icons: 'monocons',
        style: '{{ asset("plugins/minified/themes/content/default.min.css") }}'
    });
    

    // var themeInput = document.getElementById('theme');
    // themeInput.onchange = function() {
    //     var theme = '{{ asset("minified/themes/' + themeInput.value + '.min.css") }}';

    //     document.getElementById('theme-style').href = theme;
    // };
    // var themeInput = ;
    // document.getElementById('theme').addEventListener("change", function() {
    //     var theme = '{{ asset("minified/themes/' + themeInput.value + '.min.css") }}';

    //     document.getElementById('theme-style').href = theme;
    // });
})