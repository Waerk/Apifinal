<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Borrar Anuncio - <?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Eliminación de anuncios de <?= APP_NAME ?>" />
    <meta name="author" content="Robert Sallent" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />
    <?= $template->css() ?>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Borrar Anuncio'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs(['Anuncios' => '/anuncio/list/', $anuncio->titulo => null]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrar Anuncio</h2>
        <form method="POST" class="centered" action="/anuncio/destroy">
            <p>¿Confirmar el borrado del anuncio <b><?= $anuncio->titulo ?></b>?</p>
            <input type="hidden" name="id" value="<?= $anuncio->id ?>">
            <input class="button-danger" type="submit" name="borrar" value="Borrar">
        </form>
        <div class="centered">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/anuncio/list">Lista de Anuncios</a>
            <a class="button" href="/anuncio/show/<?= $anuncio->id ?>">Detalles</a>
            <a class="button" href="/anuncio/edit/<?= $anuncio->id ?>">Edición</a>
        </div>
    </main>
    <?= $template->footer(); ?>
</body>

</html>