
var table;

function situaciones(situacion) {
    var abreviaciones = ["PT", "TT", "TL", "PL", "CG", "SG"];
    var situaciones = ["Pasante de Técnico", "Título de Técnico", "Título de Licenciatura", "Pasante de Licenciatura", "Con grado", "Sin grado"];
    var index = 0;
    while (abreviaciones[index] != situacion)
        index++;
    selector = '<option value="' + abreviaciones[index] + '"> ' + situaciones[index] + ' </option>';
    for (var i = 0; i < abreviaciones.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + abreviaciones[i] + '"> ' + situaciones[i] + ' </option>';
    }
    $('.selector-editar .situacion').html(selector);
}

$(document).ready(function () {
    table = new Tabla('#tabla-honorarios', 'tablaHonorarios.php');
    sumas = [3];
    table.generarTabla(null, sumas);
    $('[data-toggle="tooltip"]').tooltip();

    $('#modal-agregar').on('shown.bs.modal', function () {
        $('#input-agregar').focus();
    });

    $('#modal-eliminar').on('shown.bs.modal', function () {
        $('#input-eliminar').focus();
    });

    $('#modal-editar').on('shown.bs.modal', function () {
        $('#input-editar').focus();
    });

    $('#modal-docentes').on('shown.bs.modal', function () {
        $('#input-docentes').focus();
    });

    $(document).on("click", "#boton-docentes", function () {
        var tabla = 'personal';
        var campos = 'personal.id, personal.nombre, personal.apellidos';
        var tabla2 = 'docentes';
        var join = 'personal.id = ' + tabla2 + '.id';
        var orden = 'personal.apellidos ASC';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'tabla2': tabla2,
                'join': join,
                'orden': orden
            },
            success: function (data) {
                $('.selector-docentes .personal').html($(data));
            }
        });
    });

    $(document).on("click", "#boton-editar", function () {
        var idPersonal = $(this).data('id');
        var numeroDePersonal = idPersonal;
        var nombrePersonal = $(this).data('nombre');
        var apellidosPersonal = $(this).data('apellidos');
        var fechaIngreso = $(this).data('ingreso');
        var escolaridad = $(this).data('escolaridad');
        var situacion = $(this).data('situacion');
        var categoriaRequerida = $(this).data('categoria');
        var fechaPropuesta = $(this).data('fecha');
        $("#modal-editar .id-personal").val(idPersonal);
        $("#modal-editar .numero-de-personal").val(numeroDePersonal);
        $("#modal-editar .nombre-personal").val(nombrePersonal);
        $("#modal-editar .apellidos-personal").val(apellidosPersonal);
        $("#modal-editar .fecha-ingreso").val(fechaIngreso);
        $("#modal-editar .escolaridad").val(escolaridad);
        $("#modal-editar .categoria-requerida").val(categoriaRequerida);
        $("#modal-editar .fecha-propuesta").val(fechaPropuesta);
        situaciones(situacion);
    });

    $(document).on("click", "#boton-eliminar", function () {
        var idPersonal = $(this).data('id');
        var numeroTrabajador = idPersonal;
        var nombreTrabajador = $(this).data('nombre');
        var apellidosTrabajador = $(this).data('apellidos');
        var honorario = $(this).data('honorario');
        $("#modal-eliminar .id-personal").val(idPersonal);
        $("#modal-eliminar .numero-trabajador").val(numeroTrabajador);
        $("#modal-eliminar .nombre-trabajador").val(nombreTrabajador);
        $("#modal-eliminar .apellidos-trabajador").val(apellidosTrabajador);
        $("#modal-eliminar .honorario").val(honorario);
    });


    $("#form-agregar").submit(function (event) {
        enviarFormularioDoble(event, 'personal', 'honorarios', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'honorarios', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormularioDoble(event, 'honorarios', 'personal', 'editar');
    });

    $("#form-docentes").submit(function (event) {
        enviarFormulario(event, 'honorarios', 'docentes');
    });
});
