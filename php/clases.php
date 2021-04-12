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
        clases($bd);
        break;
}

function clases($bd)
{
    $tabla = 'clases';
    echo $bd->eliminarTodo($tabla);
}

function eliminar($bd)
{
    $tabla = 'clases';
    $campo = 'id';
    $valor = $_POST['id'];

    echo $bd->eliminar($tabla, $campo, $valor);
}

function insertar($bd)
{
    $ciclo = $_COOKIE['ciclo'];
    $idAsignatura = "{$_POST['materia']}-{$_POST['carrera']}-{$ciclo}";
    $tabla = 'clases';
    $campos = array('grupos');
    $where = "idAsignatura = '$idAsignatura'";
    $gruposExistentes = $bd->seleccionar($tabla, $campos, $where, null, false);

    if ($gruposExistentes[0] < 6) {
        $idPersonal = $_POST['profesor'];
        $grupos = $_POST['grupos'];

        if ($_POST['tipos'] == "docentes")
            $honorarios = 0;
        else
            $honorarios = 1;

        $tabla = 'clases';
        $campos = array('idAsignatura', 'idPersonal', 'grupos', 'honorarios');
        $valores = array($idAsignatura, $idPersonal, $grupos, $honorarios);
        echo $bd->insertar($tabla, $campos, $valores);
    } else {
        $result = array(
            'response' => array(
                'status' => 'error',
                'code' => '1',
                'message' => "No puedes agregar mas de 6 grupos"
            )
        );
        echo json_encode($result);
    }
}

function editar($bd)
{
    $ciclo = $_COOKIE['ciclo'];
    $idAsignatura = "{$_POST['materia']}-{$_POST['carrera']}-{$ciclo}";

    $tabla = 'clases';
    $campos = array('grupos');
    $where = "idAsignatura = '$idAsignatura'";
    $gruposExistentes = $bd->seleccionar($tabla, $campos, $where, null, false);

    $grupos = $_POST['grupos'];

    if (($gruposExistentes[0] + $grupos) < 6) {
        $idPersonal = $_POST['profesor'];
        $id = $_POST['id'];
        if ($_POST['tipos'] == "docentes")
            $honorarios = 0;
        else
            $honorarios = 1;
        $tabla = 'clases';
        $campos = array('idAsignatura', 'idPersonal', 'grupos', 'honorarios');
        $valores = array($idAsignatura, $idPersonal, $grupos, $honorarios);
        $where = "id = '$id'";
        echo $bd->editar($tabla, $campos, $valores, $where);
    } else {
        $result = array(
            'response' => array(
                'status' => 'error',
                'code' => '1',
                'message' => "No puedes agregar mas de 6 grupos"
            )
        );
        echo json_encode($result);
    }
}
