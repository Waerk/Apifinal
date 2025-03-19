<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Edición de Anuncio - <?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Edición de anuncios de <?= APP_NAME ?>" />
    <meta name="author" content="Robert Sallent" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />
    <?= $template->css() ?>
    <script src="/js/Preview.js"></script>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Edición de Anuncio'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs(['Anuncios' => '/anuncio/list/', 'Editar ' . $anuncio->titulo => null]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Edición del anuncio <b><?= $anuncio->titulo ?></b></h2>
        <form method="POST" enctype="multipart/form-data" action="/anuncio/update">
            <input type="hidden" name="id" value="<?= $anuncio->id ?>">
            <div class="flex-container gap2">
                <div class="flex2">
                    <label>Título</label>
                    <input type="text" name="titulo" value="<?= old('titulo', $anuncio->titulo) ?>">
                    <br>
                    <label>Descripción</label>
                    <textarea name="descripcion"><?= old('descripcion', $anuncio->descripcion) ?></textarea>
                    <br>
                    <label>Precio</label>
                    <input type="number" name="precio" step="0.01" value="<?= old('precio', $anuncio->precio) ?>">
                    <br>
                    <label>Imagen</label>
                    <input type="file" name="imagen" accept="image/*" id="file-with-preview">
                    <br>
                    <div class="centrado mt2">
                        <input class="button" type="submit" name="actualizar" value="Actualizar">
                        <input class="button" type="reset" value="Reset">
                    </div>
                </div>
                <div class="flex1 centrado">
                    <figure>
                        <img src="<?= ANUNCIO_IMAGE_FOLDER . '/' . ($anuncio->imagen ?? DEFAULT_ANUNCIO_IMAGE) ?>"
                            class="cover enlarge-image" id="preview-image" alt="Imagen de <?= $anuncio->titulo ?>">
                        <figcaption>Imagen de <?= $anuncio->titulo ?></figcaption>
                        <?php if ($anuncio->imagen) { ?>
                            <form method="POST" action="/anuncio/dropimage" class="no-border">
                                <input type="hidden" name="id" value="<?= $anuncio->id ?>">
                                <input class="button-danger" type="submit" name="borrar" value="Eliminar imagen">
                            </form>
                        <?php } ?>
                    </figure>
                </div>
            </div>
        </form>
        <div class="centrado mt2">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/anuncio/list">Lista de Anuncios</a>
            <a class="button" href="/anuncio/show/<?= $anuncio->id ?>">Ver Anuncio</a>
            <?php if (Login::isAdmin() || Login::user()->id === $anuncio->idusuario) { ?>
                <a class="button-danger" href="/anuncio/delete/<?= $anuncio->id ?>">Eliminar Anuncio</a>
            <?php } ?>
        </div>
    </main>
    <?= $template->footer(); ?>
</body>

</html>