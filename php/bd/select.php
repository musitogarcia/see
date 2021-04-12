<?php
include("../bd.php");

try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $tabla = $_POST['tabla'];
    $campos = $_POST['campos'];
    $consulta = "SELECT $campos FROM $tabla";
    if (isset($_POST['inicial'])) {
        $inicial = $_POST['inicial'];
        switch ($inicial) {
            case 'Ninguna':
            case 'Todas':
            case '':
                if ($inicial == '')
                    echo '<option value="">Ninguna </option>';
                else
                    echo '<option value="">' . $inicial . '</option>';
                break;
            default:
                $campo = $_POST['campo'];
                $stmt = $conexion->prepare("SELECT $campos from $tabla WHERE $campo = '$inicial'");
                $stmt->execute();
                $area = $stmt->fetch();
                imprimirResultados($area);
                $where = "$campo != '$inicial'";
                break;
        }
        if (isset($_POST['extra'])) {
            $extra = $_POST['extra'];
            if ($inicial != null)
                echo '<option value="">' . $extra . ' </option>';
        }
    }
    if (isset($_POST['tabla2'])) {
        $tabla2 = $_POST['tabla2'];
        $join = $_POST['join'];
        $consulta = $consulta . " INNER JOIN $tabla2 ON $join";
    }
    if (isset($_POST['tabla3'])) {
        $tabla3 = $_POST['tabla3'];
        $join2 = $_POST['join2'];
        $consulta = $consulta . " INNER JOIN $tabla3 ON $join2";
    }
    if (isset($where)) {
        $consulta = $consulta . " WHERE $where";
    }
    if (isset($_POST['where'])) {
        $where = $_POST['where'];
        $consulta = $consulta . " WHERE $where";
    }
    if (isset($_POST['orden'])) {
        $orden = $_POST['orden'];
        $consulta = $consulta . " ORDER BY $orden";
    } else
        $consulta = $consulta . " ORDER BY nombre ASC";

    consultarResultados($conexion, $consulta);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function consultarResultados($conexion, $consulta)
{
    foreach ($conexion->query($consulta) as $resultados)
        imprimirResultados($resultados);
}

function imprimirResultados($resultados)
{
    if ((count($resultados) / 2) <= 2)
        echo '<option value ="' . $resultados[0] . '">' . $resultados[1] . '</option>';
    else
        echo '<option value ="' . $resultados[0] . '">' . $resultados[2] . ' ' . $resultados[1] . '</option>';
}
