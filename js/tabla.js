function enviarFormulario(event, tabla, operacion) {
    event.preventDefault();
    if (!$('#form-' + operacion)[0].checkValidity())
        event.stopPropagation();
    else
        $.ajax({
            url: 'php/' + tabla + '.php',
            type: 'POST',
            data: $('#form-' + operacion).serialize(),
            success: function (result) {
                var json = $.parseJSON(result);
                if (json.response.status == 'success') {
                    table.actualizar(operacion);
                    $('#form-' + operacion).removeClass('was-validated');
                } else
                    alert(json.response.message);
            }
        });
    $('#form-' + operacion).addClass('was-validated');
}

function enviarFormularioDoble(event, tabla, tabla2, operacion) {
    event.preventDefault();
    if (!$('#form-' + operacion)[0].checkValidity())
        event.stopPropagation();
    else
        $.ajax({
            url: 'php/' + tabla + '.php',
            type: 'POST',
            data: $('#form-' + operacion).serialize(),
            success: function () {
                $.ajax({
                    url: 'php/' + tabla2 + '.php',
                    type: 'POST',
                    data: $('#form-' + operacion).serialize(),
                    success: function (result) {
                        var json = $.parseJSON(result);
                        if (json.response.status == 'success') {
                            table.actualizar(operacion);
                            $('#form-' + operacion).removeClass('was-validated');
                        } else
                            alert(json.response.message);
                    }
                });
            }
        });
    $('#form-' + operacion).addClass('was-validated');
}

class Tabla {
    constructor(idTabla, archivo) {
        this.id = idTabla;
        this.archivo = archivo;
    }

    generarTabla(myData, sumas) {
        this.table = $(this.id).DataTable({
            ajax: ({
                url: "php/bd/" + this.archivo,
                type: 'POST',
                data: function (d) {
                    return $.extend(d, myData);
                }
            }),
            ordering: false,
            info: false,
            language: {
                paginate: {
                    first: "Primera",
                    last: "Ultimo",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                search: "Buscar:",
                buttons: {
                    excel: "Excel",
                    copy: "Copiar",
                    copyTitle: "Copiado correctamente",
                    copySuccess: {
                        _: '%d registros copiados',
                        1: '1 registro copiado'
                    }
                },
                zeroRecords: "No hay resultados",
                lengthMenu: 'Mostrar <select>' +
                    '<option value="10">10</option>' +
                    '<option value="25">25</option>' +
                    '<option value="50">50</option>' +
                    '<option value="100">100</option>' +
                    '<option value="-1">Todos</option>' +
                    '</select> resultados'
            },
            stateSave: true,
            dom: "Blfrtip",
            buttons: [
                { extend: "copy", footer: true },
                { extend: "excel", footer: true },
                { extend: "csv", footer: true }
            ],
            footerCallback: function (row, data, start, end, display) {
                if (sumas != null)
                    for (let i = 0; i < sumas.length; i++) {
                        var api = this.api(), data;
                        var total = api
                            .column(sumas[i])
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);

                        var pageTotal = api
                            .column(sumas[i], { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);

                        $(api.column(sumas[i]).footer()).html(
                            pageTotal + ' (' + total + ')'
                        );
                    }
            }
        });
        return this.table;
    }

    getTable() {
        return this.table;
    }

    actualizar(operacion) {
        $('#modal-' + operacion).modal('hide');
        document.getElementById('form-' + operacion).reset();
        this.table.ajax.reload(null, false);
    }
}