<?php
include_once('../bd.php');

try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $ciclo = $_COOKIE['ciclo'];
    $tabla = 'materias';
    $campos = array(
        'SUM((horasTeoricas+horasPracticas)*grupos) AS totalHoras',
        'SUM(grupos*(honorarios*(horasTeoricas+horasPracticas))) AS necesidad',
    );
    $campos2 = array(
        "SUM(asi.horasTeoricas+asi.horasPracticas) AS horasSemanales",
        "SUM(asi.bc) AS bc",
        "SUM(asi._as) AS _as",
        "SUM(asi.cpa) AS cpa"
    );
    $join = array(
        array(
            'asignaturas',
            'materias.clave = asignaturas.idMateria'
        ),
        array(
            'clases',
            'clases.idAsignatura = asignaturas.id'
        )
    );
    $arreglo = array();
    foreach ($conexion->query("SELECT id, nombre, grupos, alumnos, alumnosPromedio, matricula, posgrado FROM carreras") as $carrera) {
        $where = "asignaturas.idCarrera = '{$carrera['id']}' AND asignaturas.ciclo = '{$ciclo}'";
        $tabla2 = "(SELECT a.id, a.bc, a.cpa, a._as, m.horasTeoricas, m.horasPracticas, c.honorarios, c.grupos FROM 
        asignaturas AS a RIGHT JOIN clases AS c ON a.id = c.idAsignatura INNER JOIN materias AS m ON 
        m.clave = a.idMateria WHERE a.idCarrera = '{$carrera['id']}' AND a.ciclo = '{$ciclo}' GROUP BY a.id) AS asi";
        $sumas = $bd->seleccionar($tabla, $campos, $where, $join, false);
        $sumas2 = $bd->seleccionar($tabla2, $campos2, null, null, false);
        $arreglo[] = cuerpoTabla($sumas, $sumas2, $carrera);
    }
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

function cuerpoTabla($sumas, $sumas2, $carrera)
{
    return array(
        $carrera['id'],
        $carrera['nombre'],
        ($sumas2['horasSemanales'] != null) ? $sumas2['horasSemanales'] : 0,
        $carrera['grupos'],
        ($sumas['totalHoras'] != null) ? $sumas['totalHoras'] : 0,
        $carrera['alumnos'],
        $carrera['alumnosPromedio'],
        $carrera['matricula'],
        ($sumas['necesidad'] != null || $sumas['totalHoras'] != null ? $sumas['totalHoras'] - $sumas['necesidad'] : 0),
        ($sumas['necesidad'] != null ? $sumas['necesidad'] : 0),
        ($sumas['necesidad'] != null ? $sumas['necesidad'] : 0),
        ($sumas2['_as'] != null ? $sumas2['_as'] : 0),
        ($sumas2['bc'] != null ? $sumas2['bc'] : 0),
        ($sumas2['cpa'] != null ? $sumas2['cpa'] : 0),
        ($sumas['necesidad'] != null ? $sumas['necesidad'] : 0),
        "<a id='boton-editar' href='#modal-editar' data-id='{$carrera['id']}' 
        data-nombre='{$carrera['nombre']}' 
        data-grupos='{$carrera['grupos']}' 
        data-alumnos='{$carrera['alumnos']}' 
        data-alumnosp='{$carrera['alumnosPromedio']}' 
        data-matricula='{$carrera['matricula']}' 
        data-posgrado='{$carrera['posgrado']}'
        data-toggle='modal' class='editar badge badge-warning'>
            <i class='far fa-edit fa-2x'></i>
        </a>",
        "<a id='boton-eliminar' href='#modal-eliminar' data-id='{$carrera['id']}' 
        data-nombre='{$carrera['nombre']}' 
        data-toggle='modal' class='eliminar badge badge-danger'>
            <i class='fas fa-trash-alt fa-2x'></i>
        </a>"
    );
}
