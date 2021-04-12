
var table;
var myData = {};
var selector;

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
    $('.selector-editar .situaciones').html(constructorSelector);
}

function movimientos(movimiento) {
    var movimientos = [10, 95];
    var index = 0;
    while (movimientos[index] != movimiento)
        index++;
    constructorSelector = '<option value="' + movimientos[index] + '"> ' + movimientos[index] + ' </option>';
    for (var i = 0; i < movimientos.length; i++) {
        if (i != index)
            constructorSelector = constructorSelector + '<option value="' + movimientos[i] + '"> ' + movimientos[i] + ' </option>';
    }
    $('.selector-editar .movimientos').html(constructorSelector);
}

function selectorAreas(selectorAreas) {
    var tabla = 'areas_administrativas';
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

function crearSelector() {
    var tabla = 'areas_administrativas';
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
            table = new Tabla('#tabla-administrativos', 'tablaAdministrativos.php');
            var sumas = [6, 7];
            table.generarTabla(myData, sumas);
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
        $('#confirmacion-eliminar').focus();
    });

    $('#modal-editar').on('shown.bs.modal', function () {
        $('#input-editar').focus();
    });

    $(document).on("click", "#boton-agregar", function () {
        selectorAreas(".selector-agregar");
    });

    $(document).on("click", "#boton-eliminar", function () {
        var idPersonal = $(this).data('id');
        var numeroDePersonal = idPersonal;
        var nombrePersonal = $(this).data('nombre');
        var apellidosPersonal = $(this).data('apellidos');
        $("#modal-eliminar .id-personal").val(idPersonal);
        $("#modal-eliminar .numero-de-personal").val(numeroDePersonal);
        $("#modal-eliminar .nombre-personal").val(nombrePersonal);
        $("#modal-eliminar .apellidos-personal").val(apellidosPersonal);
    });

    $(document).on("click", "#boton-editar", function () {
        var idPersonal = $(this).data('id');
        var numeroDePersonal = idPersonal;
        var nombrePersonal = $(this).data('nombre');
        var apellidosPersonal = $(this).data('apellidos');
        var tipoMov = $(this).data('mov');
        var ingreso = $(this).data('ingreso');
        var plaza = $(this).data('plaza');
        var puesto = $(this).data('puesto');
        var escolaridad = $(this).data('escolaridad');
        var areaAdmin = $(this).data('area');
        var situacion = $(this).data('situacion');
        $("#modal-editar .id-personal").val(idPersonal);
        $("#modal-editar .numero-de-personal").val(numeroDePersonal);
        $("#modal-editar .nombre-personal").val(nombrePersonal);
        $("#modal-editar .apellidos-personal").val(apellidosPersonal);
        $("#modal-editar .ingreso").val(ingreso);
        $("#modal-editar .plaza").val(plaza);
        $("#modal-editar .puesto").val(puesto);
        $("#modal-editar .escolaridad").val(escolaridad);
        var tabla = 'areas_administrativas';
        var campos = 'id, nombre';
        var inicial = areaAdmin;
        var campo = 'id';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'inicial': inicial,
                'campo': campo
            },
            success: function (data) {
                $('.selector-editar .areas').html($(data));
            }
        });
        situaciones(situacion);
        movimientos(tipoMov);
    });

    $("#form-agregar").submit(function (event) {
        enviarFormularioDoble(event, 'personal', 'administrativos', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'personal', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormularioDoble(event, 'administrativos', 'personal', 'editar');
    });
});