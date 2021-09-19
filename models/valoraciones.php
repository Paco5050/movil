<?php
/*
*	Clase para manejar la tabla clientes de la base de datos. Es clase hija de Validator.
*/
class Valoracion extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $comentario = null;
    private $estrellas = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setComentario($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->comentario = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstrella($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estrellas = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function getEstrella()
    {
        return $this->estrellas;
    }

    /*
    *   Métodos para gestionar la cuenta del cliente.
    */

    public function createRow()
    {
        $sql = 'INSERT INTO valoraciones(estrellas, comentario, id_producto) VALUES (?, ?, ?)';
        $params = array($this->estrellas, $this->comentario, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readTable()
    {
        $sql = 'SELECT id_producto, nombre_producto, descripcion_producto
        FROM productos p';
        $params = null;
        return Database::getRows($sql, $params);
    }
    
    // Método para finalizar un pedido por parte del cliente.
    public function read()
    {
        $sql = 'SELECT id_valoracion, estrellas, comentario
        FROM valoraciones 
        WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
    
    public function cantidadValoraciones()
    {
        $sql = 'SELECT nombre_producto, ROUND(AVG(estrellas), 1) estrellas
        FROM valoraciones INNER JOIN productos USING(id_producto)
        GROUP BY nombre_producto ORDER BY estrellas DESC LIMIT 5';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM valoraciones
                WHERE id_valoracion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
