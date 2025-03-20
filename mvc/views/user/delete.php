<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Delete - <?= APP_NAME ?></title>

    <!-- META -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Eliminación de usuarios de <?= APP_NAME ?>" />
    <meta name="author" content="Robert Sallent" />

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />

    <!-- CSS -->
    <?= $template->css() ?>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Borrar usuario'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs([
        'Usuarios' => '/user/list/',
        $user->displayname => null
    ]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrar Usuario</h2>

        <form method="POST" class="centered" action="/user/destroy">
            <p>¿Estas seguro de que quieres eliminar el usuario <b><?= $user->displayname ?></b>?</p>
            <input type="hidden" name="id" value="<?= $user->id ?>">
            <input class="button-danger" type="submit" name="borrar" value="Borrar">
        </form>

        <div class="centered">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/user/list">Lista de usuarios</a>
            <a class="button" href="/user/show/<?= $user->id ?>">Detalles</a>
            <a class="button" href="/user/edit/<?= $user->id ?>">Edición</a>
        </div>
    </main>
    <?= $template->footer(); ?>
</body>

</html>