<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Detalles de usuario - <?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Detalles de usuarios de <?= APP_NAME ?>" />
    <meta name="author" content="Xavi Quesada" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />
    <?= $template->css() ?>

    <!-- JS para ampliar imagen -->
    <script src="/js/Bigpicture.js"></script>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Detalles de usuario'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs([
        'Usuarios' => '/user/list/',
        $user->displayname => null
    ]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>

        <!-- Sección de Detalles del usuario -->
        <section id="detalles" class="flex-container gap2">
            <div class="flex2">
                <h2><?= $user->displayname ?></h2>
                <p><b>Nombre:</b> <?= $user->displayname ?></p>
                <p><b>Email:</b> <?= $user->email ?? '__' ?></p>
                <p><b>Teléfono:</b> <?= $user->phone ?? '__' ?></p>
                <p><b>Roles:</b> <?= implode(', ', array_map(fn($role) => array_search($role, USER_ROLES), $user->roles ?? ['Sin roles'])) ?></p>
                <p><b>Bloqueado:</b> <?= $user->blocked_at ? date('Y-m-d H:i:s', strtotime($user->blocked_at)) : 'No' ?></p>
            </div>
            <figure class="flex1 centrado p2">
                <img src="<?= USER_IMAGE_FOLDER . '/' . ($user->picture ?? DEFAULT_USER_IMAGE) ?>"
                    class="cover enlarge-image"
                    alt="Foto de <?= $user->displayname ?>"
                    title="Foto de <?= $user->displayname ?>">
                <figcaption>Foto de <?= $user->displayname ?></figcaption>
            </figure>
        </section>

        <!-- Después de la sección de detalles -->
        <section class="mt2">
            <h2>Anuncios de <?= $user->displayname ?></h2>
            <?php
            $anuncios = $user->anuncios();
            if ($anuncios) { ?>
                <table class="table w100 centered-block">
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                    </tr>
                    <?php foreach ($anuncios as $anuncio) { ?>
                        <tr>
                            <td><?= $anuncio->titulo ?></td>
                            <td><?= $anuncio->descripcion ?></td>
                            <td><?= $anuncio->precio ?> €</td>
                            <td>
                                <?php if ($anuncio->imagen) { ?>
                                    <img src="/images/anuncios/<?= $anuncio->imagen ?>" class="table-image" alt="Imagen de <?= $anuncio->titulo ?>">
                                <?php } else { ?>
                                    Sin imagen
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <div class="warning p2">Este usuario no tiene anuncios publicados.</div>
            <?php } ?>
        </section>

        <!-- Botones de acción -->
        <div class="centrado">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/user/list">Lista de usuarios</a>
            <?php if (Login::isAdmin()): ?>
                <a class="button" href="/user/edit/<?= $user->id ?>">Editar</a>
            <?php endif; ?>
        </div>
    </main>

    <?= $template->footer(); ?>
</body>

</html>