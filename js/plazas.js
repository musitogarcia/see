
var table;

function categorias(categoria) {
    var categorias = ["E3505", "E3507", "E3519", "E3521", "E3525", "E3615", "E3713", "E3715", "E3717", "E3811", "E3815", "E3817", "E3837"];
    var index = 0;
    while (categorias[index] != categoria)
        index++;
    selector = '<option value="' + categorias[index] + '"> ' + categorias[index] + ' </option>';
    for (var i = 0; i < categorias.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + categorias[i] + '"> ' + categorias[i] + ' </option>';
    }
    $('.selector-editar .categorias').html(selector);
}

function movimientos(movimiento) {
    var movimientos = [10, 20, 95, 97];
    var index = 0;
    while (movimientos[index] != movimiento)
        index++;
    selector = '<option value="' + movimientos[index] + '"> ' + movimientos[index] + ' </option>';
    for (var i = 0; i < movimientos.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + movimientos[i] + '"> ' + movimientos[i] + ' </option>';
    }
    $('.selector-editar .movimientos').html(selector);
}

function horas(hora) {
    var horas = [3, 4, 5, 6, 8, 16, 19, 20, 30, 36, 40];
    var index = 0;
    while (horas[index] != hora)
        index++;
    selector = '<option value="' + horas[index] + '"> ' + horas[index] + ' </option>';
    for (var i = 0; i < horas.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + horas[i] + '"> ' + horas[i] + ' </option>';
    }
    $('.selector-editar .horas').html(selector);
}

function crearSelector() {
    var tabla = 'areas_educativas';
    var campos = 'id, nombre';
    var inicial = 'Todas';
    $.ajax({
        url: 'php/bd/select.php',
        type: 'POST',
        data: {
            'tabla': tabla,
            'campos': campos,
            'inicial': inicial
        },
        success: function (data) {
            $('.selector-principal .areas').html($(data));
        }
    });
}

$(document).ready(function () {
    crearSelector();
    var myData = {};
    table = new Tabla('#tabla-plazas', 'tablaPlazas.php');
    table.generarTabla(myData, null);
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

    $('#modal-honorarios').on('shown.bs.modal', function () {
        $('#input-honorarios').focus();
    });

    $(document).on("click", "#boton-agregar", function () {
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
                $('.selector-agregar .profesor').html($(data));
            }
        });
    });

    $(document).on("click", "#boton-eliminar", function () {
        var id = $(this).data('id');
        var idPersonal = $(this).data('idp');
        var nombre = $(this).data('nombre');;
        var apellidos = $(this).data('apellidos');
        var unisub = $(this).data('unisub');
        var categoria = $(this).data('categoria');
        var diagonal = $(this).data('diagonal');
        $("#modal-eliminar .profesor").val(idPersonal + '. ' + apellidos + ' ' + nombre);
        $("#modal-eliminar .id").val(id);
        $("#modal-eliminar .plaza").val(unisub + categoria + '/' + diagonal);
    });

    $(document).on("click", "#boton-editar", function () {
        var id = $(this).data('id');
        var idPersonal = $(this).data('idp');
        var unisub = $(this).data('unisub');
        var categoria = $(this).data('categoria');
        var hora = $(this).data('horas');
        var diagonal = $(this).data('diagonal');
        var movimiento = $(this).data('mov');
        $("#modal-editar .id").val(id);
        $("#modal-editar .unisub").val(unisub);
        $("#modal-editar .diagonal").val(diagonal);
        movimientos(movimiento);
        horas(hora);
        categorias(categoria);
        var tabla = 'personal';
        var campos = 'personal.id, personal.nombre, personal.apellidos';
        var inicial = idPersonal;
        var campo = 'personal.id';
        var tabla2 = 'docentes';
        var join = 'docentes.id = personal.id';
        var orden = 'personal.apellidos ASC';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'inicial': inicial,
                'campo': campo,
                'tabla2': tabla2,
                'join': join,
                'orden': orden
            },
            success: function (data) {
                $('.selector-editar .profesor').html($(data));
            }
        });
    });

    $(document).on("click", "#boton-honorarios", function () {
        var tabla = 'plazas';
        var campos = "plazas.id, CONCAT(uniSub,'',categoria,'/',diagonal)";
        var tabla2 = 'docentes';
        var join = 'plazas.idPersonal = ' + tabla2 + '.id';
        var orden = 'unisub ASC, categoria ASC, diagonal ASC';
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
                $('.selector-honorarios .plazas').html($(data));
            }
        });
        var tabla = 'honorarios';
        var campos = 'personal.id, personal.nombre, personal.apellidos';
        var tabla2 = 'personal';
        var join = 'honorarios.id = ' + tabla2 + '.id';
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
                $('.selector-honorarios .honorarios').html($(data));
            }
        });
    });

    $("#form-agregar").submit(function (event) {
        enviarFormulario(event, 'plazas', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'plazas', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormulario(event, 'plazas', 'editar');
    });

    $("#form-honorarios").submit(function (event) {
        enviarFormulario(event, 'plazas', 'honorarios');
    });
});