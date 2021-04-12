
var table;

function crearSelector() {
    var tabla = 'carreras';
    var campos = 'id, nombre';
    var inicial = 'Todas';
    var orden = 'id ASC';
    $.ajax({
        url: 'php/bd/select.php',
        type: 'POST',
        data: {
            'tabla': tabla,
            'campos': campos,
            'inicial': inicial,
            'orden': orden
        },
        success: function (data) {
            $('.selector-principal .carreras').html($(data));
        }
    });
}

function cambiarMaterias(value, selector) {
    var tabla = 'materias';
    var campos = 'materias.clave, materias.clave AS c, materias.nombre';
    // var campos = 'materias.clave, materias.nombre';
    var tabla2 = 'asignaturas';
    var join = 'asignaturas.idMateria = materias.clave';
    var where = 'asignaturas.idCarrera = ' + value;
    var order = "materias.nombre ASC";
    $.ajax({
        url: 'php/bd/select.php',
        type: 'POST',
        data: {
            'tabla': tabla,
            'campos': campos,
            'tabla2': tabla2,
            'join': join,
            'where': where,
            'order': order
        },
        success: function (data) {
            $(selector + ' .materia').html($(data));
        }
    });
}

function cambiarTipos(selector) {
    var tabla = 'personal';
    var campos = 'personal.id, CONCAT(personal.nombre, " ",personal.apellidos), personal.id';
    // var campos = 'personal.id, personal.nombre, personal.apellidos';
    var tabla2 = this.value;
    var join = 'personal.id = ' + tabla2 + '.id';
    var orden = 'personal.id ASC';
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
            $(selector + ' .profesor').html($(data));
        }
    });
}

function tipos(tipo) {
    var tipos = ["honorarios", "docentes"];
    var index = 0;
    while (tipos[index] != tipo)
        index++;
    selector = '<option value="' + tipos[index] + '"> ' + tipos[index] + ' </option>';
    for (var i = 0; i < tipos.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + tipos[i] + '"> ' + tipos[i] + ' </option>';
    }
    $('.selector-editar .tipos').html(selector);
}

function grupos(grupo) {
    var grupos = ['1', '2', '3', '4', '5', '6'];
    var index = 0;
    while (grupos[index] != grupo)
        index++;
    selector = '<option value="' + grupos[index] + '"> ' + grupos[index] + ' </option>';
    for (var i = 0; i < grupos.length; i++) {
        if (i != index)
            selector = selector + '<option value="' + grupos[i] + '"> ' + grupos[i] + ' </option>';
    }
    $('.selector-editar .grupos').html(selector);
}

$(document).ready(function () {
    crearSelector();
    var myData = {};
    table = new Tabla('#tabla-clases', 'tablaClases.php');
    table.generarTabla(myData, null);
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

    $(".selector-principal .carreras").change(function () {
        myData.selected = this.value;
        table.getTable().ajax.reload(null, false);
    });

    $(document).on("click", "#boton-agregar", function () {
        var tabla = 'carreras';
        var campos = 'id, nombre';
        var orden = 'id ASC';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'orden': orden
            },
            success: function (data) {
                $('.selector-agregar .carreras').html($(data));
                var selector = document.getElementsByClassName("carreras");
                var tabla = 'materias';
                var campos = 'materias.clave, materias.clave AS c, materias.nombre';
                var tabla2 = 'asignaturas';
                var join = 'asignaturas.idMateria = materias.clave';
                var where = 'asignaturas.idCarrera = ' + selector[1].options[0].value;
                var order = "materias.nombre ASC";
                $.ajax({
                    url: 'php/bd/select.php',
                    type: 'POST',
                    data: {
                        'tabla': tabla,
                        'campos': campos,
                        'tabla2': tabla2,
                        'join': join,
                        'where': where,
                        'order': order
                    },
                    success: function (data) {
                        $('.selector-agregar .materia').html($(data));
                    }
                });
            }
        });
        $(".selector-agregar .carreras").change(function () {
            cambiarMaterias(this.value, '.selector-agregar');
        });
        var selector = document.getElementsByClassName("tipos");
        var tabla = 'personal';
        var campos = 'personal.id, CONCAT(personal.nombre, " ",personal.apellidos), personal.id';
        var tabla2 = selector[0].options[0].value;
        var join = 'personal.id = ' + tabla2 + '.id';
        var orden = 'personal.id ASC';
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
        $(".selector-agregar .tipos").change(function () {
            cambiarTipos('.selector-agregar');
        });
    });

    $(document).on("click", "#boton-eliminar", function () {
        var id = $(this).data('id');
        var materia = $(this).data('materia');
        var carrera = $(this).data('carrera');
        var profesor = $(this).data('profesor');
        var grupos = $(this).data('grupos');
        $("#modal-eliminar .id").val(id);
        $("#modal-eliminar .carreras").val(carrera);
        $("#modal-eliminar .materia").val(materia);
        $("#modal-eliminar .profesor").val(profesor);
        $("#modal-eliminar .grupos").val(grupos);
    });

    $(document).on("click", "#boton-editar", function () {
        var id = $(this).data('id');
        var materia = $(this).data('materia');
        var carrera = $(this).data('carrera');
        var profesor = $(this).data('profesor');
        var grupo = $(this).data('grupos');
        var honorarios = $(this).data('honorarios');
        $("#modal-editar .id").val(id);
        grupos(grupo);
        var tabla = 'carreras';
        var campos = 'id, nombre';
        var inicial = carrera;
        var campo = 'id';
        var orden = 'id ASC';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'inicial': inicial,
                'campo': campo,
                'orden': orden
            },
            success: function (data) {
                $('.selector-editar .carreras').html($(data));
            }
        });
        $(".selector-editar .carreras").change(function () {
            cambiarMaterias(this.value, '.selector-editar');
        });
        var tabla = 'materias';
        var campos = 'clave, nombre';
        var inicial = materia;
        var campo = 'clave';
        var tabla2 = 'asignaturas';
        var join = 'asignaturas.id = materias.clave';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos,
                'inicial': inicial,
                'campo': campo,
                'tabla2': tabla2,
                'join': join
            },
            success: function (data) {
                $('.selector-editar .materia').html($(data));
            }
        });
        var tabla2 = 'docentes';
        if (honorarios == 1) {
            tabla2 = 'honorarios';
            tipos('honorarios');
        } else
            tipos('docentes');
        var tabla = 'personal';
        var campos = 'personal.id, personal.nombre, personal.apellidos';
        var inicial = profesor;
        var campo = 'personal.id';
        var join = tabla2 + '.id = personal.id';
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
        $(".selector-editar .tipos").change(function () {
            cambiarTipos('.selector-editar');
        });
    });

    $("#form-agregar").submit(function (event) {
        enviarFormulario(event, 'clases', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'clases', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormulario(event, 'clases', 'editar');
    });

    $("#form-clases").submit(function (event) {
        enviarFormulario(event, 'clases', 'clases');
    });
});