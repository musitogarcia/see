$(document).on("click", ".boton .editar", function () {
    $.ajax({
        url: 'php/index.php',
        type: 'POST',
        data: $('#form-inicio').serialize(),
        success: function () {
            window.open("concentrado.html", "_self");
        },
    });
});