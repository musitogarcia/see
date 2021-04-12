
var table;

function selectorAreas(selector) {
    var tabla = 'areas_educativas';
    var campos = 'id, nombre';
    $.ajax({
        url: 'php/bd/select.php',
        type: 'POST',
        data: {
            'tabla': tabla,
            'campos': campos
        },
        success: function (data) {
            $(selector + ' .areas').html($(data));
        }
    });
}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    table = new Tabla('#tabla-materias', 'tablaMaterias.php');
    table.generarTabla(null, null);

    $('#modal-agregar').on('shown.bs.modal', function () {
        $('#input-agregar').focus();
    });

    $('#modal-eliminar').on('shown.bs.modal', function () {
        $('#input-eliminar').focus();
    });

    $('#modal-editar').on('shown.bs.modal', function () {
        $('#input-editar').focus();
    });

    $(document).on("click", "#boton-agregar", function () {
        selectorAreas(".selector-agregar");
    });

    $(document).on("click", "#boton-eliminar", function () {
        var clave = $(this).data('clave');
        var nombre = $(this).data('nombre');
        var id = clave;
        $("#modal-eliminar .id").val(id);
        $("#modal-eliminar .nombre-asignatura").val(nombre);
        $("#modal-eliminar .clave").val(clave);
    });

    $(document).on("click", "#boton-editar", function () {
        var clave = $(this).data('clave');
        var nombre = $(this).data('nombre');
        var id = clave;
        var horasTeoricas = $(this).data('horast');
        var horasPracticas = $(this).data('horasp');
        var areaEdu = $(this).data('area');
        $("#modal-editar .id").val(id);
        $("#modal-editar .nombre-asignatura").val(nombre);
        $("#modal-editar .clave").val(clave);
        $("#modal-editar .horas-teoricas").val(horasTeoricas);
        $("#modal-editar .horas-practicas").val(horasPracticas);
        var tabla = 'areas_educativas';
        var campos = 'id, nombre';
        var inicial = areaEdu;
        var campo = 'id';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'inicial': inicial,
                'campo': campo,
            },
            success: function (data) {
                $('.selector-editar .areas').html($(data));
            }
        });
    });

    $("#form-agregar").submit(function (event) {
        enviarFormulario(event, 'materias', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'materias', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormulario(event, 'materias', 'editar');
    });
});