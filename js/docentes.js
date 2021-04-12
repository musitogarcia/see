
var table;
var myData = {};
var selector;

function crearSelector() {
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
            $('.selector-principal .areas').html($(data));
            selector = document.getElementsByClassName("areas");
            myData.selected = selector[0].options[0].value;
            table = new Tabla('#tabla-docentes', 'tablaDocentes.php');
            var sumas = [8, 9, 10];
            table.generarTabla(myData, sumas);
        }
    });
}

function situaciones(situacion) {
    var abreviaciones = ["PT", "TT", "TL", "PL", "CG", "SG"];
    var situaciones = ["Pasante de Técnico", "Título de Técnico", "Título de Licenciatura", "Pasante de Licenciatura", "Con grado", "Sin grado"];
    var index = 0;
    while (abreviaciones[index] != situacion)
        index++;
    constructorSelector = '<option value="' + abreviaciones[index] + '"> ' + situaciones[index] + ' </option>';
    for (var i = 0; i < abreviaciones.length; i++) {
        if (i != index)
            constructorSelector = constructorSelector + '<option value="' + abreviaciones[i] + '"> ' + situaciones[i] + ' </option>';
    }
    $('.selector-editar .situacion').html(constructorSelector);
}

function selectorAreas(selectorAreas) {
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
            $(selectorAreas + ' .areas').html($(data));
        }
    });
}

$(document).ready(function () {
    crearSelector();
    $('[data-toggle="tooltip"]').tooltip();

    $(".selector-principal .areas").change(function () {
        myData.selected = this.value;
        table.getTable().ajax.reload(null, false);
    });

    $('#modal-agregar').on('shown.bs.modal', function () {
        $('#input-agregar').focus();
    });

    $('#modal-eliminar').on('shown.bs.modal', function () {
        $('#input-eliminar').focus();
    });

    $('#modal-editar').on('shown.bs.modal', function () {
        $('#input-editar').focus();
    });

    $('#modal-administrativos').on('shown.bs.modal', function () {
        $('#input-administrativos').focus();
    });

    $(document).on("click", "#boton-agregar", function () {
        selectorAreas(".selector-agregar");
    });

    $(document).on("click", "#boton-administrativos", function () {
        selectorAreas(".selector-administrativos");
        var tabla = 'personal';
        var campos = 'personal.id, personal.nombre, personal.apellidos';
        var tabla2 = 'administrativos';
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
                $('.selector-administrativos .personal').html($(data));
            }
        });
    });

    $(document).on("click", "#boton-eliminar", function () {
        var idPersonal = $(this).data('id');
        var numeroDePersonal = idPersonal;
        var nombrePersonal = $(this).data('nombre');
        var apellidosPersonal = $(this).data('apellidos');
        var docente = $(this).data('docente');
        $("#modal-eliminar .id-personal").val(idPersonal);
        $("#modal-eliminar .numero-de-personal").val(numeroDePersonal);
        $("#modal-eliminar .nombre-personal").val(nombrePersonal);
        $("#modal-eliminar .apellidos-personal").val(apellidosPersonal);
        $("#modal-eliminar .docente").val(docente);
    });

    $(document).on("click", "#boton-editar", function () {
        var idPersonal = $(this).data('id');
        var numeroDePersonal = idPersonal;
        var nombrePersonal = $(this).data('nombre');
        var apellidosPersonal = $(this).data('apellidos');
        var ingreso = $(this).data('ingreso');
        var escolaridad = $(this).data('escolaridad');
        var areaEdu = $(this).data('area');
        var situacion = $(this).data('situacion');
        var causa = $(this).data('causa');
        $("#modal-editar .id-personal").val(idPersonal);
        $("#modal-editar .numero-de-personal").val(numeroDePersonal);
        $("#modal-editar .nombre-personal").val(nombrePersonal);
        $("#modal-editar .apellidos-personal").val(apellidosPersonal);
        $("#modal-editar .ingreso").val(ingreso);
        $("#modal-editar .escolaridad").val(escolaridad);
        $("#modal-editar .causa").val(causa);
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
        situaciones(situacion);
    });

    $("#form-agregar").submit(function (event) {
        enviarFormularioDoble(event, 'personal', 'docentes', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'docentes', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormularioDoble(event, 'docentes', 'personal', 'editar');
    });

    $("#form-administrativos").submit(function (event) {
        enviarFormulario(event, 'docentes', 'administrativos');
    });
});
