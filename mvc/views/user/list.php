<!-- LISTA DE USUARIOS -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Lista de usuarios - <?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Listado de usuarios de <?= APP_NAME ?>" />
    <meta name="author" content="Robert Sallent" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />
    <?= $template->css() ?>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Lista de usuarios'); ?>
    <?= $template->menu(); ?>
   <?= $template->breadCrumbs(["Usuarios" => "/User/list"]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Listado de usuarios</h2>

        <!-- FILTRO DE BÚSQUEDA -->
        <?php
        if ($filtro) {
            echo $template->removeFilterForm($filtro, '/user/list');
        } else {
            echo $template->filterForm(
                ['Nombre' => 'displayname', 'Email' => 'email', 'Teléfono' => 'phone'],
                ['Nombre' => 'displayname', 'Email' => 'email', 'Teléfono' => 'phone'],
                'Nombre',
                'Nombre'
            );
        }
        ?>

        <!-- Listado de usuarios -->
        <?php if ($users) { ?>
            <div class="right">
                <?= $paginator->stats() ?>
            </div>

            <table class="table w100">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="centrado">Operaciones</th>
                </tr>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td class="centrado">
                            <a href="/user/show/<?= $user->id ?>">
                                <img src="<?= USER_IMAGE_FOLDER . '/' . ($user->foto ?? DEFAULT_USER_IMAGE) ?>"
                                    class="table-image" alt="Foto de <?= $user->displayname ?>"
                                    title="Foto de <?= $user->displayname ?>">
                            </a>
                        </td>
                        <td><a href="/user/show/<?= $user->id ?>"><?= $user->displayname ?></a></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->phone ?? '__' ?></td>
                        <td>
                            <?php
                            $roleValue = $user->roles[0] ?? 'Sin rol';
                            $roleName = array_search($roleValue, USER_ROLES) ?: $roleValue;
                            echo $roleName;
                            ?>
                        </td>
                        <td><?= $user->blocked_at ? 'Bloqueado' : 'Activo' ?></td>
                        <td class="centrado">
                            <a class="button" href="/user/show/<?= $user->id ?>">Ver</a>
                            <a class="button-danger" href="/user/delete/<?= $user->id ?>">Borrar</a>
                            <?php if ($user->blocked_at) { ?>
                                <a class="button-success" href="/user/unblock/<?= $user->id ?>">Desbloquear</a>
                            <?php } else { ?>
                                <a class="button-warning" href="/user/block/<?= $user->id ?>">Bloquear</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <?= $paginator->ellipsisLinks() ?>
        <?php } else { ?>
            <div class="danger p2">
                <p>No hay usuarios que mostrar.</p>
            </div>
        <?php } ?>

        <div class="centrado">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/user/create">Nuevo usuario</a>
        </div>
    </main>

    <?= $template->footer(); ?>
</body>

</html>