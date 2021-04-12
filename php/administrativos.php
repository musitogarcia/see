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
}

function insertar($bd)
{
    $tabla = 'administrativos';
    $campos = array('id', 'mov', 'ingreso', 'plaza', 'puesto', 'idAdministrativas');
    $valores = array(
        $_POST['id-personal'],
        $_POST['movimiento'],
        $_POST['ingreso'],
        $_POST['plaza'],
        $_POST['puesto'],
        $_POST['area']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'administrativos';
    $campos = array('mov', 'ingreso', 'plaza', 'puesto', 'idAdministrativas');
    $valores = array(
        $_POST['movimiento'],
        $_POST['ingreso'],
        $_POST['plaza'],
        $_POST['puesto'],
        $_POST['area']
    );
    $valor = $_POST['id-personal'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}
