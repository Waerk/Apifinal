<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario - <?= APP_NAME ?></title>

    <!-- META -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perfil de usuario en <?= APP_NAME ?>">
    <meta name="author" content="Robert Sallent">

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">

    <!-- CSS -->
    <?= $template->css() ?>

    <!-- JS para ampliar imagen -->
    <script src="/js/Bigpicture.js"></script>
</head>

<body>
    <?= $template->login() ?>
    <?= $template->header('Perfil de Usuario') ?>
    <?= $template->menu() ?>
    <?= $template->breadCrumbs(["Home" => "/User/home"]) ?>
    <?= $template->messages() ?>

    <main>
        <div class="flex-container gap2">
            <section class="flex1" id="user-data">
                <div class="flex2">
                    <h2><?= $user->displayname ?></h2>
                </div>
                <p><b>Nombre:</b> <?= $user->displayname ?></p>
                <p><b>Email:</b> <?= $user->email ?></p>
                <p><b>Teléfono:</b> <?= $user->phone ?></p>
                <p><b>Fecha de alta:</b> <?= $user->created_at ?></p>
                <p><b>Última modificación:</b> <?= $user->updated_at ?? '-' ?></p>
                <!-- Botón Editar solo para el usuario autenticado -->
                <?php if (Login::user()->id === $user->id) { ?>
                    <div class="centrado mt2">
                        <a class="button" href="/user/editprofile">Editar mi perfil</a>
                    </div>
                <?php } ?>
            </section>

            <section class="flex1">
                <h2>Imagen de perfil</h2>
                <div class="flex1 centrado">
                    <figure>
                        <img src="<?= USER_IMAGE_FOLDER . '/' . ($user->picture ?? 'default.png') ?>"
                            class="cover enlarge-image"
                            alt="Imagen de perfil de <?= $user->displayname ?>"
                            title="Imagen de perfil de <?= $user->displayname ?>"
                            id="preview-image">
                        <figcaption>Imagen de perfil de <?= $user->displayname ?></figcaption>
                        <?php if ($user->picture && Login::user()->id === $user->id) { ?>
                            <form method="POST" action="/user/dropfoto" class="no-border">
                                <input type="hidden" name="id" value="<?= $user->id ?>">
                                <input type="submit" class="button-danger" name="borrar" value="Eliminar foto">
                            </form>
                        <?php } ?>
                    </figure>
                </div>
            </section>
        </div>

        <!-- Formulario para cambiar contraseña -->
        <section class="mt2">
            <h3>Cambiar contraseña</h3>
            <form method="POST" action="/user/changepassword" class="no-border">
                <label>Contraseña actual</label>
                <input type="password" name="currentpassword" value="" required>
                <br>
                <label>Nueva contraseña</label>
                <input type="password" name="newpassword" value="" required>
                <br>
                <label>Repetir nueva contraseña</label>
                <input type="password" name="repeatpassword" value="" required>
                <br>
                <div class="centrado mt2">
                    <input class="button" type="submit" name="cambiar" value="Cambiar contraseña">
                    <input class="button" type="reset" name="reset" value="Reset">
                </div>
            </form>
        </section>
        <div class="centrado mt2">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/logout">Cerrar sesión</a>
        </div>
    </main>

    <?= $template->footer() ?>
    <?= $template->version() ?>
</body>

</html>