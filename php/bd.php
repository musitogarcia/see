<?php
class BD
{
    var $conexion;

    function getConexion()
    {
        return $this->conexion;
    }

    function __construct($usuario, $contrasena)
    {
        $this->conectarBD($usuario, $contrasena);
    }

    function conectarBD($usuario, $contrasena)
    {
        try {
            $this->conexion = new PDO('mysql:host=localhost;dbname=itc', $usuario, $contrasena, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
            $this->conexion->query("SET NAMES 'utf8'");
            return $this->conexion;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    function eliminar($tabla, $campo, $valor)
    {
        try {
            $eliminar = $this->conexion->prepare("DELETE FROM $tabla WHERE $campo = ?");
            $eliminar->bindParam(1, $valor);
            $eliminar->execute();
            $result = array(
                'response' => array(
                    'status' => 'success',
                    'code' => '1',
                    'message' => "Eliminación correcta"
                )
            );
            return json_encode($result);
        } catch (PDOException $e) {
            $result = array(
                'response' => array(
                    'status' => 'error',
                    'code' => '1',
                    'message' => "{$e->getMessage()}"
                )
            );
            return json_encode($result);
            die();
        }
    }

    function eliminarTodo($tabla)
    {
        try {
            $eliminar = $this->conexion->prepare("DELETE FROM $tabla WHERE 1");
            $eliminar->execute();
            $result = array(
                'response' => array(
                    'status' => 'success',
                    'code' => '1',
                    'message' => "Eliminación correcta"
                )
            );
            return json_encode($result);
        } catch (PDOException $e) {
            $result = array(
                'response' => array(
                    'status' => 'error',
                    'code' => '1',
                    'message' => "{$e->getMessage()}"
                )
            );
            return json_encode($result);
            die();
        }
    }

    function insertar($tabla, $campos, $valores)
    {
        try {
            $tamano = count($campos);
            $camposDeclaracion = "";
            $valoresDeclaracion = "";
            for ($i = 0; $i < $tamano; $i++)
                if ($i + 1 < $tamano) {
                    $camposDeclaracion = $camposDeclaracion . $campos[$i] . ', ';
                    $valoresDeclaracion = $valoresDeclaracion . '?, ';
                } else {
                    $camposDeclaracion = $camposDeclaracion . $campos[$i];
                    $valoresDeclaracion = $valoresDeclaracion . '?';
                }
            $stmt = $this->conexion->prepare("INSERT INTO $tabla ($camposDeclaracion) VALUES ($valoresDeclaracion)");
            for ($i = 0; $i < $tamano; $i++)
                $stmt->bindParam($i + 1, $valores[$i]);
            $stmt->execute();
            $result = array(
                'response' => array(
                    'status' => 'success',
                    'code' => '1',
                    'message' => "Inserción correcta"
                )
            );
            return json_encode($result);
        } catch (PDOException $e) {
            $result = array(
                'response' => array(
                    'status' => 'error',
                    'code' => '1',
                    'message' => "{$e->getMessage()} {$valores[0]}"
                )
            );
            return json_encode($result);
            die();
        }
    }

    function editar($tabla, $campos, $valores, $where)
    {
        try {
            $tamano = count($campos);
            $declaracion = "";
            for ($i = 0; $i < $tamano; $i++)
                if ($i + 1 < $tamano)
                    $declaracion = $declaracion . $campos[$i] . ' = ?, ';
                else
                    $declaracion = $declaracion . $campos[$i] . ' = ?';
            $stmt = $this->conexion->prepare("UPDATE $tabla SET $declaracion WHERE $where");
            for ($i = 0; $i < $tamano; $i++)
                $stmt->bindParam($i + 1, $valores[$i]);
            $stmt->execute();
            $result = array(
                'response' => array(
                    'status' => 'success',
                    'code' => '1',
                    'message' => "Edición correcta"
                )
            );
            return json_encode($result);
        } catch (PDOException $e) {
            $result = array(
                'response' => array(
                    'status' => 'error',
                    'code' => '1',
                    'message' => "{$e->getMessage()}"
                )
            );
            return json_encode($result);
            die();
        }
    }

    function seleccionar($tabla, $campos, $where, $join, $varios)
    {
        $tamano = count($campos);
        $camposDeclaracion = "";
        for ($i = 0; $i < $tamano; $i++)
            if ($i + 1 < $tamano)
                $camposDeclaracion = $camposDeclaracion . $campos[$i] . ', ';
            else
                $camposDeclaracion = $camposDeclaracion . $campos[$i];
        $declaracion = "SELECT $camposDeclaracion FROM $tabla";
        if (isset($join)) {
            $tamanoJoin = count($join);
            $i = 0;
            while ($i < $tamanoJoin)
                $declaracion = $declaracion . ' INNER JOIN ' . $join[$i][0] . ' ON ' . $join[$i++][1];
        }
        if (isset($where))
            $declaracion = $declaracion . " WHERE $where";
        $stmt = $this->conexion->prepare($declaracion);
        $stmt->execute();
        if ($varios)
            return $stmt->fetchAll();
        else
            return $stmt->fetch();
    }
}
