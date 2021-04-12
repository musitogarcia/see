<?php
include_once("bd.php");

$bd = new BD("root", "");
$operacion = $_POST['operacion'];
switch ($operacion) {
    case "eliminar":
        eliminar($bd);
        break;
    case "insertar":
        insertar($bd);
        break;
    case "editar":
        editar($bd);
        break;
    default:
        honorarios($bd);
        break;
}

function eliminar($bd)
{
    $tabla = 'plazas';
    $campo = 'id';
    $valor = $_POST['id'];

    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $tabla = 'plazas';
    $campos = array(
        'id',
        'idPersonal',
        'mov',
        'uniSub',
        'categoria',
        'horas',
        'diagonal'
    );
    $valores = array(
        "{$_POST['diagonal']}-{$_POST['id-personal']}",
        $_POST['id-personal'],
        $_POST['movimiento'],
        $_POST['unisub'],
        $_POST['categoria'],
        $_POST['horas'],
        $_POST['diagonal']
    );

    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'plazas';
    $campos = array(
        'id',
        'idPersonal',
        'mov',
        'uniSub',
        'categoria',
        'horas',
        'diagonal'
    );
    $valores = array(
        $_POST['id'],
        $_POST['id-personal'],
        $_POST['movimiento'],
        $_POST['unisub'],
        $_POST['categoria'],
        $_POST['horas'],
        $_POST['diagonal']
    );
    $where = "id = '{$_POST['id']}'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}


function honorarios($bd)
{
    $idDocente = $_POST['honorario'];
    $tabla = 'docentes';
    $campos = array(
        'COUNT(id)'
    );
    $where = "id = '$idDocente'";
    $docente = $bd->seleccionar($tabla, $campos, $where, null, false);

    $idPlaza = $_POST['plaza'];
    $tabla = 'plazas';
    $campos = array(
        'mov',
        'uniSub',
        'categoria',
        'horas',
        'diagonal',
        'idPersonal'
    );
    $where = "id = '$idPlaza'";
    $plaza = $bd->seleccionar($tabla, $campos, $where, null, false);

    if ($docente <= 0) {
        $idDocente = $plaza[5];
        $tabla = 'docentes';
        $campos = array(
            'idEducativas'
        );
        $where = "id = '$idDocente'";
        $area = $bd->seleccionar($tabla, $campos, $where, null, false);

        $esDocente = 0;
        $tabla = 'docentes';
        $campos = array(
            'id',
            'idEducativas',
            'docente'
        );
        $valores = array(
            $_POST['honorario'],
            $area[0],
            $esDocente
        );
        $json = $bd->insertar($tabla, $campos, $valores);
        $arr = json_decode($json, true);
        if ($arr['response']['status'] == 'success') {
            insertarPlaza($bd, $plaza);
        } else
            echo $json;
    } else
        insertarPlaza($bd, $plaza);
}

function insertarPlaza($bd, $plaza)
{
    $tabla = 'plazas';
    $campos = array(
        'id',
        'idPersonal',
        'mov',
        'uniSub',
        'categoria',
        'horas',
        'diagonal'
    );
    $valores = array(
        "{$plaza['diagonal']}-{$_POST['honorario']}",
        $_POST['honorario'],
        $plaza['mov'],
        $plaza['uniSub'],
        $plaza['categoria'],
        $plaza['horas'],
        $plaza['diagonal']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}
