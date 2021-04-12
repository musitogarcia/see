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
        administrativos($bd);
        break;
}

function eliminar($bd)
{
    $campo = 'id';
    $valor =  $_POST['id-personal'];
    if ($_POST['docente'] == 1)
        $tabla = 'personal';
    else
        $tabla = 'docentes';
    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $tabla = 'docentes';
    $campos = array(
        'id',
        'ingreso',
        'causa',
        'idEducativas'
    );
    $valores = array(
        $_POST['id-personal'],
        $_POST['ingreso'],
        $_POST['causa'],
        $_POST['area']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'docentes';
    $campos = array(
        'ingreso',
        'causa',
        'idEducativas'
    );
    $ingreso = $_POST['ingreso'];
    if ($ingreso == '')
        $ingreso = null;
    $causa = $_POST['causa'];
    if ($causa == '')
        $causa = null;
    $valores = array(
        $ingreso,
        $causa,
        $_POST['area']
    );
    $valor = $_POST['id-personal'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}

function administrativos($bd)
{
    $tabla = 'docentes';
    $campos = array(
        'id',
        'idEducativas',
        'docente'
    );
    $docente = 0;
    $valores = array(
        $_POST['id-personal'],
        $_POST['area'],
        $docente
    );
    echo $bd->insertar($tabla, $campos, $valores);
}
