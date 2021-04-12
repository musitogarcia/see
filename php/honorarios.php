<?php
include_once("bd.php");

$bd = new BD("root", "");
$operacion = $_POST['operacion'];
switch ($operacion) {
    case "insertar":
        insertar($bd);
        break;
    case "editar":
        editar($bd);
        break;
    case 'eliminar':
        eliminar($bd);
        break;
    default:
        docentes($bd);
        break;
}

function insertar($bd)
{
    $tabla = 'honorarios';
    $campos = array(
        'id',
        'ingreso'
    );
    $valores = array(
        $_POST['id-personal'],
        $_POST['fecha-ingreso'],
    );

    echo $bd->insertar($tabla, $campos, $valores);
}

function eliminar($bd)
{
    $campo = 'id';
    $valor =  $_POST['id-personal'];
    if ($_POST['honorario'] == 1)
        $tabla = 'personal';
    else
        $tabla = 'honorarios';
    echo $bd->eliminar($tabla, $campo, $valor);
}

function editar($bd)
{
    $tabla = 'honorarios';
    $campos = array(
        'ingreso'
    );
    $valores = array(
        $_POST['fecha-ingreso']
    );
    $valor = $_POST['id-personal'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}

function docentes($bd)
{
    $tabla = 'honorarios';
    $campos = array(
        'id',
        'ingreso',
        'honorario'
    );
    $docentes = 0;
    $valores = array(
        $_POST['id-personal'],
        $_POST['fecha-ingreso'],
        $docentes
    );
    echo $bd->insertar($tabla, $campos, $valores);
}
