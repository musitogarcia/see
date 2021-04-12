<?php
include_once("../bd.php");
try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $arreglo = array();
    $declaracion = "SELECT h.id, p.nombre, p.apellidos, p.escolaridad, h.ingreso,
    p.situacion, h.honorario FROM honorarios AS h INNER JOIN personal AS p ON h.id = p.id ORDER BY p.apellidos ASC";
    foreach ($conexion->query($declaracion) as $trabajador)
        $arreglo = crearArreglo($bd, $trabajador, $arreglo);
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

function crearArreglo($bd, $trabajador, $arreglo)
{
    $horas = seleccionarHoras($bd, $trabajador['id']);
    $contadorClases = contarClases($bd, $trabajador['id'], "clases")[0];
    $contador = $contadorClases;
    if ($contador > 0) {
        if ($contadorClases > 0)
            $clases = seleccionarClases($bd, $trabajador['id']);
        for ($i = 0; $i < $contador; $i++) {
            if ($i == 0)
                $arreglo[] = crearTabla($i, $trabajador, $clases, $horas);
            else {
                $arreglo[] = crearTabla($i, null, $clases, null);
            }
        }
    } else
        $arreglo[] = crearTabla(0, $trabajador, null, $horas);
    return $arreglo;
}

function crearTabla($i, $trabajador, $clases, $horas)
{
    $array = array();
    if (isset($trabajador)) {
        $array[] = $trabajador['id'];
        $array[] = "{$trabajador['apellidos']} {$trabajador['nombre']}";
        $array[] = $trabajador['ingreso'];
    } else {
        $array[] = "";
        $array[] = "";
        $array[] = "";
    }

    $array[] = $horas[0] != null ? $horas[0] : 0;

    if (isset($clases)) {
        $array[] = $clases[$i]['clave'];
        $array[] = $clases[$i]['nombre'];
        $array[] = $clases[$i]['idEducativas'];
    } else {
        $array[] = "";
        $array[] = "";
        $array[] = "";
    }

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

    if (isset($trabajador)) {
        $array[] = $trabajador['escolaridad'];
        $array[] = $trabajador['situacion'];
        $array[] = "<a id='boton-editar' href='#modal-editar' data-id='{$trabajador['id']}'
            data-nombre= '{$trabajador['nombre']}'
            data-apellidos= '{$trabajador['apellidos']}'
            data-ingreso= '{$trabajador['ingreso']}'
            data-escolaridad= '{$trabajador['escolaridad']}'
            data-situacion= '{$trabajador['situacion']}'
            data-toggle= 'modal' class= 'badge badge-warning'>
                <i class= 'far fa-edit fa-2x' data-toggle= 'tooltip' data-placement= 'bottom' title= 'Editar docente'></i>
            </a>";
        $array[] = "<a id='boton-eliminar' href= '#modal-eliminar' data-id= '{$trabajador['id']}'
            data-nombre= '{$trabajador['nombre']}'
            data-apellidos= '{$trabajador['apellidos']}'
            data-honorario= '{$trabajador['honorario']}'
            data-toggle= 'modal' class= 'badge badge-danger'>
                <i class= 'fas fa-trash-alt fa-2x' data-toggle= 'tooltip' data-placement= 'bottom' title= 'Eliminar docente'></i>
            </a>";
    } else {
        $array[] = "";
        $array[] = "";
        $array[] = "";
        $array[] = "";
    }
    return $array;
}

function seleccionarClases($bd, $id)
{
    $ciclo = $_COOKIE['ciclo'];
    $where = "idPersonal = '{$id}' AND honorarios = '1' AND ciclo = '{$ciclo}'";
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

function seleccionarHoras($bd, $id)
{
    $campos = array("(SUM(m.horasTeoricas)+SUM(m.horasPracticas))*c.grupos AS horas");
    $join = array(
        array(
            "clases AS c",
            "h.id = c.idPersonal"
        ),
        array(
            "asignaturas AS a",
            "c.idAsignatura = a.id"
        ),
        array(
            "materias AS m",
            "m.clave = a.idMateria"
        )
    );
    $where = "h.id = '{$id}'";
    return $bd->seleccionar("honorarios AS h", $campos, $where, $join, false);
}

function contarClases($bd, $id, $tabla)
{
    $ciclo = $_COOKIE['ciclo'];
    $campos = array("count(*)");
    $where = "idPersonal = '{$id}' AND honorarios = '1' AND ciclo = '{$ciclo}'";
    $inner = array(
        array(
            "asignaturas",
            "clases.idAsignatura = asignaturas.id"
        )
    );
    return $bd->seleccionar($tabla, $campos, $where, $inner, false);
}
