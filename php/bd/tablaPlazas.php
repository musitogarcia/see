<?php
include_once('../bd.php');
try {
    $bd = new BD("root", "");
    $conexion = $bd->getConexion();
    $arreglo = array();
    if (isset($_POST['selected']) && $_POST['selected'] != "")
        foreach ($conexion->query("SELECT p.id, p.idPersonal, pl.nombre, pl.apellidos, p.mov, p.uniSub, p.categoria, p.horas, p.diagonal from plazas AS p INNER JOIN personal AS pl ON p.idPersonal = pl.id INNER JOIN docentes AS d ON pl.id = d.id WHERE d.idEducativas = '{$_POST['selected']}' ORDER BY apellidos ASC") as $plazas)
            $arreglo[] = construirTabla($plazas);
    else
        foreach ($conexion->query('SELECT p.id, p.idPersonal, pl.nombre, pl.apellidos, p.mov, p.uniSub, p.categoria, p.horas, p.diagonal from plazas AS p INNER JOIN personal AS pl on p.idPersonal = pl.id ORDER BY apellidos ASC') as $plazas)
            $arreglo[] = construirTabla($plazas);
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

function construirTabla($plazas)
{
    return array(
        $plazas['idPersonal'],
        "{$plazas['apellidos']} {$plazas['nombre']}",
        $plazas['mov'],
        $plazas['uniSub'],
        $plazas['categoria'],
        $plazas['horas'],
        $plazas['diagonal'],
        "<a id='boton-editar' href='#modal-editar' 
        data-id='{$plazas['id']}' 
        data-idp='{$plazas['idPersonal']}' 
        data-mov='{$plazas['mov']}' 
        data-unisub='{$plazas['uniSub']}' 
        data-categoria='{$plazas['categoria']}' 
        data-horas='{$plazas['horas']}' 
        data-diagonal='{$plazas['diagonal']}' 
        data-toggle='modal' class='badge badge-warning'>
            <i class='far fa-edit fa-2x' data-toggle='tooltip' data-placement='bottom' title='Editar carrera'></i>
        </a>",
        "<a id='boton-eliminar' href='#modal-eliminar' 
        data-id='{$plazas['id']}' 
        data-idp='{$plazas['idPersonal']}' 
        data-apellidos='{$plazas['apellidos']}'
        data-nombre='{$plazas['nombre']}'
        data-unisub='{$plazas['uniSub']}' 
        data-categoria='{$plazas['categoria']}' 
        data-diagonal='{$plazas['diagonal']}' 
        data-toggle='modal' class='badge badge-danger'>
            <i class='fas fa-trash-alt fa-2x' data-toggle='tooltip' data-placement='bottom' title='Eliminar carrera'></i>
        </a>"
    );
}
