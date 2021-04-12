<?php
include_once("../bd.php");
try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $arreglo = array();
    $declaracion = "SELECT d.id, p.nombre, p.apellidos, d.ingreso, d.causa, p.escolaridad, 
    p.situacion, d.idEducativas, d.docente FROM docentes AS d INNER JOIN personal AS p 
    ON p.id = d.id";
    foreach ($conexion->query("{$declaracion} WHERE idEducativas = '{$_POST['selected']}' ORDER BY p.apellidos ASC") as $docentes)
        $arreglo = crearArreglo($bd, $docentes, $arreglo);
    echo json_encode(
        array(
            "draw"            => 1,
            "recordsTotal"    => count($arreglo),
            "recordsFiltered" => count($arreglo),
            "data"            => $arreglo
        )
    );
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br />";
    die();
}

function crearArreglo($bd, $docentes, $arreglo)
{
    $contadorClases = contarClases($bd, $docentes['id'], "clases")[0];
    $contadorPlazas = contarPlazas($bd, $docentes['id'], "plazas")[0];
    if ($contadorClases < $contadorPlazas)
        $contador = $contadorPlazas;
    else
        $contador = $contadorClases;
    if ($contador > 0) {
        if ($contadorClases > 0)
            $clases = seleccionarClases($bd, $docentes['id']);
        if ($contadorPlazas > 0)
            $plazas = seleccionarPlazas($bd, $docentes['id']);
        for ($i = 0; $i < $contador; $i++) {
            if ($i == 0) {
                if (!isset($clases) && isset($plazas))
                    $arreglo[] = crearTabla($i, $docentes, $plazas, null);
                else if (isset($clases) && !isset($plazas))
                    $arreglo[] = crearTabla($i, $docentes, null, $clases);
                else
                    $arreglo[] = crearTabla($i, $docentes, $plazas, $clases);
            } else {
                if ($contadorClases > $i && $contadorPlazas > $i)
                    $arreglo[] = crearTabla($i, $docentes, $plazas, $clases);
                else if ($contadorClases > $i && $contadorPlazas <= $i)
                    $arreglo[] = crearTabla($i, $docentes, null, $clases);
                else if ($contadorClases <= $i && $contadorPlazas > $i)
                    $arreglo[] = crearTabla($i, $docentes, $plazas, null);
            }
        }
    } else
        $arreglo[] = crearTabla(0, $docentes, null, null);
    return $arreglo;
}

function crearTabla($i, $docentes, $plazas, $clases)
{
    $array = array();
    if ($i == 0) {
        $array[] = $docentes['id'];
        $array[] = "{$docentes['apellidos']} {$docentes['nombre']}";
    } else {
        $array[] = ""; //"<p hidden>{$docentes['id']}</p>";
        $array[] = ""; //"<p hidden>{$docentes['apellidos']} {$docentes['nombre']}</p>";
    }

    if (isset($plazas))
        $array[] = $plazas[$i]['mov'];
    else
        $array[] = "";

    if ($i == 0)
        $array[] = $docentes['ingreso'];
    else
        $array[] = ""; //"<p hidden>{$docentes['ingreso']}</p>";

    if (isset($plazas)) {
        $array[] = $plazas[$i]['uniSub'];
        $array[] = $plazas[$i]['categoria'];
        $array[] = $plazas[$i]['horas'];
        $array[] = $plazas[$i]['diagonal'];
        $array[] = $plazas[$i]['horas'];
    } else {
        $array[] = "";
        $array[] = "";
        $array[] = "";
        $array[] = "";
        $array[] = 0;
    }

    if (isset($clases)) {
        if ($clases[$i]['posgrado'] == 0) {
            $array[] = $clases[$i]["grupos"] * ($clases[$i]['horasTeoricas'] + $clases[$i]['horasPracticas']);
            $array[] = 0;
        } else {
            $array[] = 0;
            $array[] = $clases[$i]["grupos"] * ($clases[$i]['horasTeoricas'] + $clases[$i]['horasPracticas']);
        }
    } else {
        $array[] = 0;
        $array[] = 0;
    }

    if ($i == 0)
        $array[] = $docentes['causa'];
    else
        $array[] = ""; //"<p hidden>{$docentes['causa']}</p>";

    if (isset($clases)) {
        $array[] = $clases[$i]['clave'];
        $array[] = $clases[$i]['nombre'];
    } else {
        $array[] = "";
        $array[] = "";
    }
    if ($i == 0)
        $array[] = $docentes['idEducativas'];
    else
        $array[] = ""; //"<p hidden>{$docentes['idEducativas']}</p>";

    if (isset($clases)) {
        $array[] = $clases[$i]['horasTeoricas'];
        $array[] = $clases[$i]['horasPracticas'];
        $array[] = $clases[$i]['grupos'];
        $array[] = $clases[$i]['carrera'];
    } else {
        $array[] = "";
        $array[] = "";
        $array[] = "";
        $array[] = "";
    }

    if ($i == 0) {
        $array[] = $docentes['escolaridad'];
        $array[] = $docentes['situacion'];
        $array[] = "<a id='boton-editar' href='#modal-editar' data-id='{$docentes['id']}'
            data-nombre= '{$docentes['nombre']}'
            data-apellidos= '{$docentes['apellidos']}'
            data-ingreso= '{$docentes['ingreso']}'
            data-escolaridad= '{$docentes['escolaridad']}'
            data-situacion= '{$docentes['situacion']}'
            data-area= '{$docentes['idEducativas']}'
            data-causa= '{$docentes['causa']}'
            data-toggle= 'modal' class= 'badge badge-warning'>
                <i class= 'far fa-edit fa-2x' data-toggle= 'tooltip' data-placement= 'bottom' title= 'Editar docente'></i>
            </a>";
        $array[] = "<a id='boton-eliminar' href= '#modal-eliminar' data-id= '{$docentes['id']}'
            data-nombre= '{$docentes['nombre']}'
            data-apellidos= '{$docentes['apellidos']}'
            data-docente= '{$docentes['docente']}'
            data-toggle= 'modal' class= 'badge badge-danger'>
                <i class= 'fas fa-trash-alt fa-2x' data-toggle= 'tooltip' data-placement= 'bottom' title= 'Eliminar docente'></i>
            </a>";
    } else {
        $array[] = ""; //"<p hidden>{$docentes['escolaridad']}</p>";
        $array[] = ""; //"<p hidden>{$docentes['situacion']}</p>";
        $array[] = "";
        $array[] = "";
    }
    return $array;
}


function seleccionarClases($bd, $id)
{
    $ciclo = $_COOKIE['ciclo'];
    $where = "idPersonal = '{$id}' AND honorarios = '0' AND ciclo = '{$ciclo}'";
    $campos = array(
        "clases.grupos",
        "carreras.nombre as carrera",
        "carreras.posgrado",
        "materias.*"
    );
    $join = array(
        array(
            "asignaturas",
            "clases.idAsignatura = asignaturas.id"
        ),
        array(
            "materias",
            "asignaturas.idMateria = materias.clave"
        ),
        array(
            "carreras",
            "asignaturas.idCarrera = carreras.id"
        )
    );
    return $bd->seleccionar("clases", $campos, $where, $join, true);
}

function seleccionarPlazas($bd, $id)
{
    $where = "idPersonal = '{$id}'";
    $campos = array(
        "mov",
        "uniSub",
        "categoria",
        "horas",
        "diagonal"
    );
    return $bd->seleccionar("plazas", $campos, $where, null, true);
}

function contarClases($bd, $id, $tabla)
{
    $ciclo = $_COOKIE['ciclo'];
    $campos = array("count(*)");
    $where = "idPersonal = '{$id}' AND honorarios = '0' AND ciclo = '{$ciclo}'";
    $inner = array(
        array(
            "asignaturas",
            "clases.idAsignatura = asignaturas.id"
        )
    );
    return $bd->seleccionar($tabla, $campos, $where, $inner, false);
}

function contarPlazas($bd, $id, $tabla)
{
    $campos = array("count(*)");
    $where = "idPersonal = '{$id}'";
    return $bd->seleccionar($tabla, $campos, $where, null, false);
}
