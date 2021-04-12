<?php
include_once("../bd.php");
try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $arreglo = array();
    foreach ($conexion->query("SELECT clave, nombre, horasTeoricas, horasPracticas, idEducativas FROM materias ORDER BY clave ASC") as $asignatura)
        $arreglo[] = construirTabla($asignatura);
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

function construirTabla($asignatura)
{
    return array(
        $asignatura['clave'],
        $asignatura['nombre'],
        $asignatura['horasTeoricas'],
        $asignatura['horasPracticas'],
        $asignatura['idEducativas'],
        " <a id='boton-editar' href='#modal-editar' 
        data-clave='{$asignatura['clave']}' 
        data-nombre='{$asignatura['nombre']}' 
        data-horast='{$asignatura['horasTeoricas']}' 
        data-horasp='{$asignatura['horasPracticas']}' 
        data-area='{$asignatura['idEducativas']}'
        data-toggle='modal' class='badge badge-warning'>
            <i class='far fa-edit fa-2x' data-toggle='tooltip' title='Editar materia'></i>
        </a>",
        "<a id='boton-eliminar' href='#modal-eliminar' 
        data-nombre='{$asignatura['nombre']}' 
        data-clave='{$asignatura['clave']}' 
        data-toggle='modal' class='badge badge-danger'>
            <i class='fas fa-trash-alt fa-2x' data-toggle='tooltip' title='Eliminar materia'></i>
        </a>"
    );
}
