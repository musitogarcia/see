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
    $tabla = 'asignaturas';
    $campo = 'id';
    $valor = $_POST['id'];
    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $ciclo = $_COOKIE['ciclo'];
    $tabla = 'asignaturas';
    $campos = array('id', 'idMateria', 'idCarrera', 'semestre', 'alumnos', 'ciclo');
    $valores = array(
        "{$_POST['materia']}-{$_POST['carrera']}-{$ciclo}",
        $_POST['materia'],
        $_POST['carrera'],
        $_POST['semestre'],
        $_POST['alumnos'],
        $_COOKIE['ciclo']
    );
    echo $bd->insertar($tabla, $campos, $valores);
}

function editar($bd)
{
    $ciclo = $_COOKIE['ciclo'];
    $tabla = 'asignaturas';
    $campos = array('id', 'idMateria', 'idCarrera', 'semestre', 'alumnos', '_as', 'bc', 'cpa');
    $valores = array(
        "{$_POST['materia']}-{$_POST['carrera']}-{$ciclo}",
        $_POST['materia'],
        $_POST['carrera'],
        $_POST['semestre'],
        $_POST['alumnos'],
        // $_POST['cn'],
        $_POST['as'],
        $_POST['bc'],
        $_POST['cpa']
        // $_POST['h']
    );
    $valor = $_POST['id'];
    $where = "id = '$valor'";
    echo $bd->editar($tabla, $campos, $valores, $where);
}
