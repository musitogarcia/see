
var table;

$(document).ready(function () {
    var sumas = [2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14];
    table = new Tabla('#tabla-carreras', 'tablaCarreras.php')
    table.generarTabla(null, sumas);
    $('[data-toggle="tooltip"]').tooltip();
    $("#periodo").val('AD');

    $('#modal-agregar').on('shown.bs.modal', function () {
        $('#input-agregar').focus();
    });

    $('#modal-eliminar').on('shown.bs.modal', function () {
        $('#input-eliminar').focus();
    });

    $('#modal-editar').on('shown.bs.modal', function () {
        $('#input-editar').focus();
    });

    $(document).on("click", "#boton-eliminar", function () {
        var idCarrera = $(this).data('id');
        var numeroCarrera = idCarrera;
        var nombreCarrera = $(this).data('nombre');
        $("#modal-eliminar .id-carrera").val(idCarrera);
        $("#modal-eliminar .numero-carrera").val(numeroCarrera);
        $("#modal-eliminar .nombre-carrera").val(nombreCarrera);
    });

    $(document).on("click", "#boton-editar", function () {
        var idCarrera = $(this).data('id');
        var numeroCarrera = idCarrera;
        var nombreCarrera = $(this).data('nombre');
        var gruposCarrera = $(this).data('grupos');
        var totalAlumnos = $(this).data('alumnos');
        var alumnosPromedio = $(this).data('alumnosp');
        var matricula = $(this).data('matricula');
        var posgrado = $(this).data('posgrado');
        $("#modal-editar .id-carrera").val(idCarrera);
        $("#modal-editar .numero-carrera").val(numeroCarrera);
        $("#modal-editar .nombre-carrera").val(nombreCarrera);
        $("#modal-editar .grupos-carrera").val(gruposCarrera);
        $("#modal-editar .total-alumnos").val(totalAlumnos);
        $("#modal-editar .promedio-alumnos").val(alumnosPromedio);
        $("#modal-editar .matricula").val(matricula);
        if (posgrado == 0)
            document.getElementById("radio-editar-1").checked = true;
        else
            document.getElementById("radio-editar-2").checked = true;
    });

    $("#form-agregar").submit(function (event) {
        enviarFormulario(event, 'carreras', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'carreras', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormulario(event, 'carreras', 'editar');
    });
});