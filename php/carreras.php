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
    $tabla = 'carreras';
    $campo = 'id';
    $valor = $_POST['id-carrera'];
    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $tabla = 'carreras';
    $campos = array('id', 'nombre', 'grupos', 'alumnos', 'matricula', 'posgrado');
    $valores = array(
        $_POST['numero-carrera'],
        $_POST['nombre-carrera'],
        $_POST['grupos-carrera'],
        $_POST['total-alumnos'],
        $_POST['matricula'],
        $_POST['posgrado']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'carreras';
    $campos = array('id', 'nombre', 'grupos', 'alumnos', 'matricula', 'posgrado');
    $valores = array(
        $_POST['numero-carrera'],
        $_POST['nombre-carrera'],
        $_POST['grupos-carrera'],
        $_POST['total-alumnos'],
        $_POST['matricula'],
        $_POST['posgrado']
    );
    $valor = $_POST['id-carrera'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}
