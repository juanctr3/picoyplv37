<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { header("Location: ../user/login.php"); exit; }

require_once '../config-ciudades.php';
$file = '../data/excepciones.json';
$excepciones = json_decode(file_get_contents($file), true) ?: [];

if (isset($_POST['add'])) {
    $data_nueva = [
        'fecha' => $_POST['f'],
        'ciudad' => $_POST['c'],
        'tipo' => $_POST['t'],
        'motivo' => $_POST['m']
    ];
    $excepciones[] = $data_nueva;
    file_put_contents($file, json_encode(array_values($excepciones), JSON_PRETTY_PRINT));
}

if (isset($_GET['del'])) {
    unset($excepciones[$_GET['del']]);
    file_put_contents($file, json_encode(array_values($excepciones), JSON_PRETTY_PRINT));
    header("Location: manage_exceptions.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Excepciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-4">
    <div class="container">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Añadir Nueva Excepción</h4>
            </div>
            <div class="card-body">
                <form method="POST" class="row">
                    <div class="col-md-3 mb-2">
                        <label>Fecha del evento</label>
                        <input type="date" name="f" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label>Ciudad</label>
                        <select name="c" class="form-control" required id="ciudadSelect">
                            <?php foreach($ciudades as $slug => $c): if(is_array($c) && isset($c['nombre'])): ?>
                                <option value="<?= $slug ?>"><?= $c['nombre'] ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label>Tipo de Vehículo</label>
                        <select name="t" class="form-control" required>
                            <option value="todos">Todos los vehículos</option>
                            <option value="particulares">Particulares</option>
                            <option value="taxis">Taxis</option>
                            <option value="motos">Motos</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label>Motivo (Aparecerá al usuario)</label>
                        <input type="text" name="m" class="form-control" placeholder="Ej: Paro de Transportadores" required>
                    </div>
                    <div class="col-12 mt-3">
                        <button name="add" class="btn btn-success btn-block">Guardar Excepción</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover bg-white shadow-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Ciudad</th>
                        <th>Vehículo</th>
                        <th>Motivo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($excepciones as $idx => $ex): ?>
                    <tr>
                        <td><?= $ex['fecha'] ?></td>
                        <td><?= ucfirst($ex['ciudad']) ?></td>
                        <td><?= $ex['tipo'] ?></td>
                        <td><?= $ex['motivo'] ?></td>
                        <td><a href="?del=<?= $idx ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="index.php" class="btn btn-link mt-3">← Volver al Administrador</a>
    </div>
</body>
</html>
