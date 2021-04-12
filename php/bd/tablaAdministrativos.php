<?php
include_once("../bd.php");
try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $declaracion = "SELECT * FROM administrativos AS pnd INNER JOIN personal AS p ON pnd.id = p.id";
    $arreglo = array();
    foreach ($conexion->query("{$declaracion} WHERE idAdministrativas =  '{$_POST['selected']}' ORDER BY p.apellidos ASC") as $administrativos) {
        $horas = seleccionarHoras($bd, $administrativos['id']);
        $arreglo[] = cuerpoTabla($administrativos, $horas);
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
    print "Error!: " . $e->getMessage() . "<br />";
    die();
}

function cuerpoTabla($administrativos, $horas)
{
    return array(
        $administrativos['id'],
        ("{$administrativos['apellidos']} {$administrativos['nombre']}"),
        $administrativos['mov'],
        $administrativos['ingreso'],
        $administrativos['plaza'],
        $administrativos['puesto'],
        $horas[0] != null ? $horas[0] : 0,
        0,
        $administrativos['escolaridad'],
        $administrativos['situacion'],
        "<a id='boton-editar' href='#modal-editar' data-id='{$administrativos['id']}' 
        data-nombre='{$administrativos['nombre']}' 
        data-apellidos='{$administrativos['apellidos']}' 
        data-mov='{$administrativos['mov']}' 
        data-ingreso='{$administrativos['ingreso']}' 
        data-plaza='{$administrativos['plaza']}' 
        data-puesto='{$administrativos['puesto']}' 
        data-escolaridad='{$administrativos['escolaridad']}' 
        data-situacion='{$administrativos['situacion']}' 
        data-area='{$administrativos['idAdministrativas']}'
         data-toggle='modal' class='badge badge-warning'>
            <i class='far fa-edit fa-2x' data-toggle='tooltip' data-placement='bottom' title='Editar carrera'></i>
        </a>",
        "<a id='boton-eliminar' href='#modal-eliminar' data-id='{$administrativos['id']}' 
        data-nombre='{$administrativos['nombre']}' 
        data-apellidos='{$administrativos['apellidos']}' 
        data-toggle='modal' class='badge badge-danger'>
        <i class='fas fa-trash-alt fa-2x' data-toggle='tooltip' data-placement='bottom' title='Eliminar carrera'></i>
        </a>"
    );
}

function seleccionarHoras($bd, $id)
{
    $campos = array("(SUM(m.horasTeoricas)+SUM(m.horasPracticas))*c.grupos AS horas");
    $join = array(
        array(
            "clases AS c",
            "admin.id = c.idPersonal"
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
    $where = "admin.id = '{$id}'";
    return $bd->seleccionar("administrativos AS admin", $campos, $where, $join, false);
}
