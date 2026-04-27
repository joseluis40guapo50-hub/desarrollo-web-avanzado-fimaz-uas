<?php
// JOSE LUIS LIZARRAGA URIBE

spl_autoload_register(function($class){
    require_once str_replace('\\', '/', $class) . '.php';
});

$controller = new \controllers\ProductoController();
$productoEditar = null;

if (isset($_GET['eliminar'])) {
    $controller->eliminar($_GET['eliminar']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['editar'])) {
    $productoEditar = $controller->obtenerPorId($_GET['editar']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $existencia = (int)$_POST['existencia'];
    $precio = (float)$_POST['precio'];

    $producto = new \models\Producto();
    $producto->setNombre($nombre);
    $producto->setDescripcion($descripcion);
    $producto->setExistencia($existencia);
    $producto->setPrecio($precio);

    if ($id) {
        $producto->setId($id);
        $controller->actualizar($producto);
    } else {
        $controller->crear($producto);
    }

    header("Location: index.php");
    exit;
}

$terminoBusqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($terminoBusqueda != '') {
    $productos = $controller->buscar($terminoBusqueda);
} else {
    $productos = $controller->listar();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema PDO Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2 class="text-center mb-4">SISTEMA CRUD DE PRODUCTOS PDO</h2>
    <h5 class="text-center mb-4">Desarrollado por JOSE LUIS LIZARRAGA URIBE</h5>

    <div class="card shadow p-4 mb-4">
        <form method="POST">

            <input type="hidden" name="id" value="<?= $productoEditar['id'] ?? '' ?>">

            <div class="mb-3">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" required value="<?= $productoEditar['nombre'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <input type="text" name="descripcion" class="form-control" required value="<?= $productoEditar['descripcion'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Existencia</label>
                <input type="number" name="existencia" class="form-control" required value="<?= $productoEditar['existencia'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" required value="<?= $productoEditar['precio'] ?? '' ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                <?= $productoEditar ? 'Actualizar Producto' : 'Registrar Producto' ?>
            </button>

            <?php if($productoEditar): ?>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>

        </form>
    </div>

    <div class="card shadow p-4">

        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar producto..." value="<?= $terminoBusqueda ?>">
            <button type="submit" class="btn btn-success">Buscar</button>
            <a href="index.php" class="btn btn-dark ms-2">Mostrar Todos</a>
        </form>

        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>DESCRIPCION</th>
                    <th>EXISTENCIA</th>
                    <th>PRECIO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>

            <?php if(count($productos) > 0): ?>
                <?php foreach($productos as $fila): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['descripcion'] ?></td>
                        <td><?= $fila['existencia'] ?></td>
                        <td>$<?= $fila['precio'] ?></td>
                        <td>
                            <a href="index.php?editar=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="index.php?eliminar=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                    <tr>
                        <td colspan="6">No hay productos registrados</td>
                    </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>