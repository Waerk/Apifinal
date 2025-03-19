<!-- NUEVO USUARIO -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Create - <?= APP_NAME ?></title>

    <!-- META -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Formulario para crear un nuevo usuario en <?= APP_NAME ?>" />
    <meta name="author" content="Xavi Quesada" />

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />

    <!-- CSS -->
    <?= $template->css() ?>
    <!-- JS -->
    <script src="/js/Preview.js"></script>
    <script>
        window.addEventListener('load', function() {
            email.addEventListener('change', function() {

                fetch("/User/checkemail/" + this.value, {
                        "method": "GET"
                    })
                    .then(function(respuesta) {
                        return respuesta.json();
                    })
                    .then(function(json) {
                        if (json.status == 'OK')
                            comprobacion.innerHTML =
                            json.data.found ? 'Este email ya está registrado' : '';
                        else
                            comprobacion.innerHTML = 'No se pudo comprobar';
                    });
            });
        });
    </script>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Nuevo Usuario'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs([
        'Usuarios' => '/user/list/',
        'Nuevo Usuario' => null
    ]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Nuevo usuario</h2>

        <form method="POST" enctype="multipart/form-data" action="/user/store" onsubmit="return validateForm()">
            <div class="flex-container gap2">
                <div class="flex2">
                    <label>Nombre</label>
                    <input type="text" name="displayname" value="<?= old('displayname') ?>">
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" id="email" value="<?= old('email') ?>">
                    <span id="comprobacion" class="mini"></span>
                    <br>

                    <label>Teléfono</label>
                    <input type="text" name="phone" value="<?= old('phone') ?>">
                    <br>
                    <label>Población</label>
                    <input type="text" name="poblacion" value="<?= old('poblacion') ?>">
                    <br>
                    <label>Código Postal</label>
                    <input type="text" name="cp" value="<?= old('cp') ?>">
                    <br>
                    <label>Password</label>
                    <input type="password" name="password" value="">
                    <br>
                    <label>Repetir</label>
                    <input type="password" name="repeatpassword" value="">
                    <br>

                    <label>Imagen de perfil</label>
                    <input type="file" name="picture" accept="image/*" id="file-with-preview">
                    <br>
                    <label>Rol</label>
                    <select name="roles">
                        <?php foreach (USER_ROLES as $roleName => $roleValue) { ?>
                            <option value="<?php echo $roleValue; ?>" <?= oldSelected('roles', $roleValue) ?>><?php echo $roleName; ?></option>
                        <?php } ?>
                    </select>
                    <br>

                    <div class="centered mt2">
                        <input type="submit" class="button" name="guardar" value="Guardar">
                        <input type="reset" class="button" value="Reset">
                    </div>
                </div>

                <div class="flex1 centrado">
                    <figure>
                        <img src="<?= USER_IMAGE_FOLDER . '/' . DEFAULT_USER_IMAGE ?>"
                            class="cover" id="preview-image" alt="Previsualización de la imagen de perfil">
                        <figcaption>Previsualización de la imagen de perfil</figcaption>
                    </figure>
                </div>
            </div>
        </form>
        <div class="centrado">
            <a class="button" onclick="history.back()">Atrás</a>
            <a class="button" href="/user/list/">Lista de usuarios</a>
        </div>
    </main>

    <?= $template->footer(); ?>
</body>

</html>