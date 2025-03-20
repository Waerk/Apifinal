<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Edición de usuario - <?= APP_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Edición de usuarios de <?= APP_NAME ?>" />
    <meta name="author" content="Robert Sallent" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/png" />
    <?= $template->css() ?>
    <script src="/js/Preview.js"></script>
    <script src="/js/Bigpicture.js"></script>
</head>

<body>
    <?= $template->login(); ?>
    <?= $template->header('Edición de usuario'); ?>
    <?= $template->menu(); ?>
    <?= $template->breadCrumbs($isAdminEdit ? [
        'Usuarios' => '/user/list/',
        'Editar ' . $user->displayname => null
    ] : [
        'Perfil' => '/user/home',
        'Editar' => null
    ]) ?>
    <?= $template->messages(); ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Edición del usuario <b><?= $user->displayname ?></b></h2>

        <div class="flex-container gap2">
            <div class="flex2">
                <form method="POST" action="<?= $isAdminEdit ? '/user/update' : '/user/updateprofile' ?>" class="no-border" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $user->id ?>">
                    <label>Nombre</label>
                    <input type="text" name="displayname" value="<?= old('displayname', $user->displayname) ?>">
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= old('email', $user->email) ?>">
                    <br>
                    <label>Teléfono</label>
                    <input type="tel" name="phone" value="<?= old('phone', $user->phone) ?>">
                    <br>
                    <label>Población</label>
                    <input type="text" name="poblacion" value="<?= old('poblacion', $user->poblacion) ?>">
                    <br>
                    <label>Código Postal</label>
                    <input type="text" name="cp" value="<?= old('cp', $user->cp) ?>">
                    <br>
                    <?php if ($isAdminEdit) { ?>
                        <label>Contraseña</label>
                        <input type="password" name="password" value="">
                        <br>
                        <label>Repetir contraseña</label>
                        <input type="password" name="repeatpassword" value="">
                        <br>
                    <?php } ?>
                    <label>Foto</label>
                    <input type="file" name="picture" accept="image/*" id="file-with-preview">
                    <br>
                    <div class="centrado mt2">
                        <input class="button" type="submit" name="actualizar" value="Actualizar">
                        <input class="button" type="reset" name="reset" value="Reset">
                    </div>
                </form>
            </div>

            <div class="flex1 centrado">
                <figure>
                    <img src="<?= USER_IMAGE_FOLDER . '/' . ($user->foto ?? DEFAULT_USER_IMAGE) ?>"
                        class="cover enlarge-image" alt="Foto de <?= $user->displayname ?>"
                        id="preview-image">
                    <figcaption>Foto de <?= $user->displayname ?></figcaption>
                    <?php if ($user->imagen) { ?>
                        <form method="POST" action="<?= $isAdminEdit ? '/user/droppicture' : '/user/dropfoto' ?>" class="no-border">
                            <input type="hidden" name="id" value="<?= $user->id ?>">
                            <input type="submit" class="button-danger" name="borrar" value="Borrar">
                        </form>
                    <?php } ?>
                </figure>
            </div>
        </div>

        <!-- Sección de Roles (solo para administradores) -->
        <?php if ($isAdminEdit) { ?>
            <section class="mt2">
                <h2>Roles de <b><?= $user->displayname ?></b></h2>

                <section class="flex-container space-between mb2">
                    <div class="flex-container gap1">
                        <?php if (!empty($availableRoles)) : ?>
                            <form class="m0 no-border" method="POST" action="/user/addRole">
                                <input type="hidden" name="iduser" value="<?= $user->id ?>" />
                                <select name="role" class="mr1">
                                    <option value="">Selecciona un rol</option>
                                    <?php foreach ($availableRoles as $role) : ?>
                                        <option value="<?= $role ?>"><?= array_search($role, USER_ROLES) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input class="button-success" type="submit" name="add" value="Añadir rol" />
                            </form>
                        <?php endif; ?>
                    </div>
                </section>

                <?php if (empty($user->roles)) : ?>
                    <div class="warning p2">No hay roles asociados a este usuario.</div>
                <?php else : ?>
                    <table class="table w100 centered-block">
                        <tr>
                            <th>Rol</th>
                            <th class="centrado">Operaciones</th>
                        </tr>
                        <?php foreach ($user->roles as $role) : ?>
                            <tr>
                                <td><?= array_search($role, USER_ROLES) ?></td>
                                <td class="centrado">
                                    <form method="POST" action="/user/removeRole" class="no-border inline">
                                        <input type="hidden" name="iduser" value="<?= $user->id ?>" />
                                        <input type="hidden" name="role" value="<?= $role ?>" />
                                        <input class="button-danger" type="submit" name="remove" value="Quitar Rol"
                                            onclick="return confirm('¿Está seguro de que desea quitar este rol?')" />
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </section>
        <?php } ?>

        <div class="centrado mt2">
            <a class="button" onclick="history.back()">Atrás</a>
            <?php if ($isAdminEdit) { ?>
                <a class="button" href="/user/list">Lista de usuarios</a>
                <a class="button" href="/user/show/<?= $user->id ?>">Ver usuario</a>
                <?php if (Login::role('ROLE_ADMIN')) { ?>
                    <a class="button-danger" href="/user/delete/<?= $user->id ?>">Eliminar usuario</a>
                <?php } ?>
            <?php } else { ?>
                <a class="button" href="/user/home">Volver al perfil</a>
            <?php } ?>
        </div>
    </main>

    <?= $template->footer(); ?>
    <script>
        window.addEventListener('load', function() {
            const fileInput = document.getElementById('file-with-preview');
            const previewImage = document.getElementById('preview-image');
            fileInput.addEventListener('change', function() {
                previewImage.src = URL.createObjectURL(this.files[0]);
            });
        });
    </script>
</body>

</html>