<?php
include_once("../bd.php");

try {
    $totales = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $ciclo = $_COOKIE['ciclo'];
    $arreglo = array();
    foreach ($conexion->query("SELECT a.id, m.clave, c.id AS idCarrera, a.semestre, m.nombre, m.horasTeoricas, m.horasPracticas, a.alumnos, 
    a._as, a.bc, a.cpa, c.nombre AS carrera, (SELECT SUM(grupos) FROM clases WHERE idAsignatura = a.id) AS suma 
    FROM asignaturas AS a INNER JOIN materias AS m ON a.idMateria = m.clave INNER JOIN carreras AS c ON a.idCarrera = c.id 
    WHERE a.idCarrera = '{$_POST['selected']}' AND a.ciclo = '{$ciclo}' ORDER BY a.semestre ASC, m.nombre ASC") as $asignatura)
        $arreglo[] = cuerpoTabla($conexion, $asignatura);
    echo json_encode(
        array(
            "draw"            => 1,
            "recordsTotal"    => count($arreglo),
            "recordsFiltered" => count($arreglo),
            "data"            => $arreglo
        )
    );
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function cuerpoTabla($conexion, $asignatura)
{
    $cubiertas = 0;
    $necesidad = 0;
    $array = array(
        $asignatura['semestre'],
        $asignatura['nombre'],
        ($asignatura['horasPracticas'] + $asignatura['horasTeoricas']),
        $asignatura['suma'] != null ? $asignatura['suma'] : 0,
        (($asignatura['horasPracticas'] + $asignatura['horasTeoricas']) * $asignatura['suma']),
        $asignatura['alumnos'],
    );
    if ($asignatura['suma'] > 0) {
        foreach ($conexion->query("SELECT grupos, idPersonal, honorarios, horasTeoricas, horasPracticas FROM clases 
        INNER JOIN asignaturas ON clases.idAsignatura = asignaturas.id INNER JOIN materias ON 
        materias.clave = asignaturas.idMateria WHERE idAsignatura = '{$asignatura['id']}'") as $grupos) {
            $horas = ($grupos['horasTeoricas'] + $grupos['horasPracticas']);
            for ($i = 0; $i < $grupos['grupos']; $i++)
                if ($grupos['honorarios'] == 1) {
                    $array[] = "N"; //$grupos['idPersonal'];
                    $necesidad = $necesidad + $horas;
                } else {
                    $array[] = $grupos['idPersonal'];
                    $cubiertas = $cubiertas + $horas;
                }
        }
        for ($i = 0; $i < (6 - $asignatura['suma']); $i++)
            $array[] = "";
    } else
        for ($i = 0; $i < 6; $i++)
            $array[] = "";
    $array[] = $cubiertas; //$asignatura['horasCubiertas'];
    $array[] = $necesidad; //$asignatura['horasNecesidad'];
    $array[] = $necesidad;
    $array[] = $asignatura['_as'];
    $array[] = $asignatura['bc'];
    $array[] = $asignatura['cpa'];
    $array[] = $necesidad;
    $array[] = "<a id='boton-editar' href='#modal-editar' 
                data-id='{$asignatura['id']}' 
                data-clave='{$asignatura['clave']}'
                data-carrera='{$asignatura['idCarrera']}' 
                data-semestre='{$asignatura['semestre']}' 
                data-alumnos='{$asignatura['alumnos']}' 
                data-as='{$asignatura['_as']}'
                data-bc='{$asignatura['bc']}'
                data-cpa='{$asignatura['cpa']}'
                data-toggle='modal' class='badge badge-warning'>
                    <i class='far fa-edit fa-2x' data-toggle='tooltip' data-placement='bottom' title='Editar materia'></i>
                </a>";
    $array[] = "<a id='boton-eliminar' href='#modal-eliminar'
                data-id='{$asignatura['id']}' 
                data-materia='{$asignatura['nombre']}'
                data-carrera='{$asignatura['carrera']}' 
                data-toggle='modal' class='badge badge-danger'>
                    <i class='fas fa-trash-alt fa-2x' data-toggle='tooltip' data-placement='bottom' title='Eliminar materia'></i>
                </a>";
    return $array;
}
