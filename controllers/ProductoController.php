<?php
namespace controllers;

// JOSE LUIS LIZARRAGA URIBE

class ProductoController {
    private $connection;

    public function __construct() {
        $database = new \config\Database();
        $this->connection = $database->getConnection();
    }

    public function crear(\models\Producto $producto) {
        try {
            $sql = "INSERT INTO productos (nombre, descripcion, existencia, precio) VALUES (?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                $producto->getNombre(),
                $producto->getDescripcion(),
                $producto->getExistencia(),
                $producto->getPrecio()
            ]);
        } catch (\PDOException $e) {
            die("Error al crear: " . $e->getMessage());
        }
    }

    public function listar() {
        try {
            $sql = "SELECT * FROM productos ORDER BY id DESC";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            die("Error al listar: " . $e->getMessage());
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM productos WHERE id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            die("Error al obtener: " . $e->getMessage());
        }
    }

    public function actualizar(\models\Producto $producto) {
        try {
            $sql = "UPDATE productos SET nombre=?, descripcion=?, existencia=?, precio=? WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                $producto->getNombre(),
                $producto->getDescripcion(),
                $producto->getExistencia(),
                $producto->getPrecio(),
                $producto->getId()
            ]);
        } catch (\PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM productos WHERE id = ?";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            die("Error al eliminar: " . $e->getMessage());
        }
    }

    public function buscar($termino) {
        try {
            $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ? ORDER BY id DESC";
            $stmt = $this->connection->prepare($sql);
            $busqueda = "%" . $termino . "%";
            $stmt->execute([$busqueda, $busqueda]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            die("Error al buscar: " . $e->getMessage());
        }
    }
}
?>