<?php
include_once("../bd.php");
try {
    $ciclo = $_COOKIE['ciclo'];
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $declaracion = "SELECT p.id as idProfesor, p.nombre as nombreProfesor, p.apellidos as apellidosProfesor, 
    m.clave as idMateria, m.nombre as materia, carreras.id as idCarrera, carreras.nombre as carrera, c.id, 
    c.grupos, c.honorarios FROM clases AS c LEFT JOIN personal AS p ON c.idPersonal = p.id LEFT JOIN asignaturas AS a
    ON c.idAsignatura = a.id LEFT JOIN materias AS m ON 
    a.idMateria = m.clave LEFT JOIN carreras ON a.idCarrera = carreras.id WHERE a.ciclo = '$ciclo'";
    $arreglo = array();
    if (isset($_POST['selected']) && $_POST['selected'] != "")
        foreach ($conexion->query("{$declaracion} AND carreras.id = '{$_POST['selected']}' ORDER BY p.apellidos ASC") as $clases)
            $arreglo[] = construirTabla($clases);
    else
        foreach ($conexion->query("{$declaracion} ORDER BY p.apellidos ASC") as $clases)
            $arreglo[] = construirTabla($clases);
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

function construirTabla($clases)
{
    return array(
        $clases['idProfesor'],
        "{$clases['apellidosProfesor']} {$clases['nombreProfesor']}",
        $clases['materia'],
        $clases['carrera'],
        $clases['grupos'],
        "<a id='boton-editar' href='#modal-editar' 
        data-id='{$clases['id']}' 
        data-profesor='{$clases['idProfesor']}'
        data-materia='{$clases['idMateria']}' 
        data-carrera='{$clases['idCarrera']}' 
        data-grupos='{$clases['grupos']}'
        data-honorarios='{$clases['honorarios']}'
        data-toggle='modal' class='badge badge-warning'>
            <i class='far fa-edit fa-2x' data-toggle='tooltip' data-placement='bottom' title='Editar materia'></i>
        </a>",
        "<a id='boton-eliminar' href='#modal-eliminar' 
        data-id='{$clases['id']}' 
        data-profesor='{$clases['idProfesor']} {$clases['apellidosProfesor']} {$clases['nombreProfesor']}'
        data-materia='{$clases['materia']}' 
        data-carrera='{$clases['carrera']}' 
        data-grupos='{$clases['grupos']}'
        data-toggle='modal' class='badge badge-danger'>
            <i class='fas fa-trash-alt fa-2x' data-toggle='tooltip' data-placement='bottom' title='Eliminar materia'></i>
        </a>"
    );
}
