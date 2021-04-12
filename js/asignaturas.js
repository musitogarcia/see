
var table;
var myData = {};
var selector;

function crearSelector() {
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
            $('.selector-principal .carreras').html($(data));
            selector = document.getElementsByClassName("carreras");
            myData.selected = selector[0].options[0].value;
            table = new Tabla('#tabla-asignaturas', 'tablaAsignaturas.php');
            var sumas = [2, 3, 4, 5, 12, 13, 14, 15, 16, 17, 18];
            table.generarTabla(myData, sumas);
        }
    });
}

$(document).ready(function () {
    crearSelector();
    $('[data-toggle="tooltip"]').tooltip();

    $(".selector-principal .carreras").change(function () {
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
                $('.selector-agregar .carrera').html($(data));
            }
        });
        var tabla = 'materias';
        var campos = 'clave, clave AS c, nombre';
        $.ajax({
            url: 'php/bd/select.php',
            type: 'POST',
            data: {
                'tabla': tabla,
                'campos': campos
            },
            success: function (data) {
                $('.selector-agregar .materia').html($(data));
            }
        });
    });

    $(document).on("click", "#boton-eliminar", function () {
        var id = $(this).data('id');
        var materia = $(this).data('materia');
        var carrera = $(this).data('carrera');
        $(".modal-body .id").val(id);
        $(".modal-body .materia").val(materia);
        $(".modal-body .carrera").val(carrera);
    });

    $(document).on("click", "#boton-editar", function () {
        var id = $(this).data('id');
        var clave = $(this).data('clave');
        var carrera = $(this).data('carrera');
        var semestre = $(this).data('semestre');
        var alumnos = $(this).data('alumnos');
        var _as = $(this).data('as');
        var bc = $(this).data('bc');
        var cpa = $(this).data('cpa');
        $("#modal-editar .id").val(id);
        $("#modal-editar .semestre").val(semestre);
        $("#modal-editar .alumnos").val(alumnos);
        $("#modal-editar .as").val(_as);
        $("#modal-editar .bc").val(bc);
        $("#modal-editar .cpa").val(cpa);
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
                $('.selector-editar .carrera').html($(data));
            }
        });
        var tabla = 'materias';
        var campos = 'clave, clave AS c, nombre';
        var inicial = clave;
        var campo = 'clave';
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
                $('.selector-editar .materia').html($(data));
            }
        });
    });

    $("#form-agregar").submit(function (event) {
        enviarFormulario(event, 'asignaturas', 'agregar');
    });

    $("#form-eliminar").submit(function (event) {
        enviarFormulario(event, 'asignaturas', 'eliminar');
    });

    $("#form-editar").submit(function (event) {
        enviarFormulario(event, 'asignaturas', 'editar');
    });

});