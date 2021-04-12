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
    $tabla = 'materias';
    $campo = 'clave';
    $valor = $_POST['clave'];
    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $tabla = 'materias';
    $campos = array(
        'clave',
        'nombre',
        'horasTeoricas',
        'horasPracticas',
        'idEducativas'
    );
    $valores = array(
        $_POST['clave'],
        $_POST['nombre-asignatura'],
        $_POST['horas-teoricas'],
        $_POST['horas-practicas'],
        $_POST['area']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $tabla = 'materias';
    $campos = array(
        'clave',
        'nombre',
        'horasTeoricas',
        'horasPracticas',
        'idEducativas'
    );
    $valores = array(
        $_POST['id'],
        $_POST['nombre-asignatura'],
        $_POST['horas-teoricas'],
        $_POST['horas-practicas'],
        $_POST['area']
    );
    $valor = $_POST['clave'];
    $where = "clave = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}
