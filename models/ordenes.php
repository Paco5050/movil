<?php
/*
*	Clase para manejar la tabla clientes de la base de datos. Es clase hija de Validator.
*/
class Ordenes extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombres = null;
    private $apellidos = null;
    private $correo = null;

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

    public function setNombres($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->nombres = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setApellidos($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->apellidos = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
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

    public function getNombres()
    {
        return $this->nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    /*
    *   Métodos para gestionar la cuenta del cliente.
    */
 
    public function readAll()
    {
        $sql = 'SELECT c.nombres_cliente, fecha_pedido, p.id_pedido
        FROM pedidos p, clientes c WHERE p.id_cliente = c.id_cliente AND p.id_cliente = c.id_cliente AND  estado_pedido =1';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finish()
    {
        $this->estado = 2;
        $sql = 'UPDATE pedidos
                SET estado_pedido = ?
                WHERE id_pedido = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_detalle, s.nombre_producto, d.cantidad_producto, d.precio_producto, c.nombres_cliente, d.id_pedido
        FROM detalle_pedido d, pedidos p, clientes c, productos s WHERE d.id_pedido=p.id_pedido AND p.id_cliente = c.id_cliente AND d.id_producto=s.id_producto AND p.id_pedido=?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readOrdenes()
    {
        $sql = 'SELECT id_detalle, s.nombre_producto, d.cantidad_producto, d.precio_producto, c.nombres_cliente, d.id_pedido, ROUND(d.cantidad_producto*d.precio_producto, 2) AS subtotal
        FROM detalle_pedido d, pedidos p, clientes c, productos s WHERE d.id_pedido=p.id_pedido AND p.id_cliente = c.id_cliente AND d.id_producto=s.id_producto AND p.id_pedido=?
                ORDER BY s.nombre_producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    public function total()
    {
        $sql = 'SELECT ROUND(SUM(d.cantidad_producto*d.precio_producto),2) AS total, ROUND(SUM((d.cantidad_producto*d.precio_producto)*1.13),2) AS totaliva
        FROM detalle_pedido d WHERE d.id_pedido=?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
