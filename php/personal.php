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
}

function eliminar($bd)
{
    $tabla = 'personal';
    $campo = 'id';
    $valor = $_POST['id-personal'];

    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $tabla = 'personal';
    $campos = array('id', 'nombre', 'apellidos', 'escolaridad', 'situacion');
    $valores = array(
        $_POST['id-personal'],
        $_POST['nombre-personal'],
        $_POST['apellidos-personal'],
        $_POST['escolaridad'],
        $_POST['situacion']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'personal';
    $campos = array('id', 'nombre', 'apellidos', 'escolaridad', 'situacion');
    $valores = array(
        $_POST['numero-de-personal'],
        $_POST['nombre-personal'],
        $_POST['apellidos-personal'],
        $_POST['escolaridad'],
        $_POST['situacion']
    );
    $valor = $_POST['id-personal'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}
