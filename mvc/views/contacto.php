<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto - <?= APP_NAME ?></title>

    <!-- META -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contacto en <?= APP_NAME ?>">
    <meta name="author" content="Robert Sallent">

    <!-- FAVICON -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">

    <!-- CSS -->
    <?= $template->css() ?>
</head>

<body>
    <?= $template->login() ?>
    <?= $template->header('Contacto') ?>
    <?= $template->menu() ?>
    <?= $template->breadCrumbs(["Contacto" => "/Contacto"]) ?>
    <?= $template->messages() ?>

    <main>
        <div class="flex-container gap2">

            <section class="flex1">
                <h2>Contacto</h2>

                <form method="POST" action="/Contacto/send" class="w50 centered-block" autocomplete="off">
                    <?= csrf() ?>

                    <div class="m1 centered">
                        <label>Email</label>
                        <input type="email" name="email" required value="<?php echo old('email'); ?>"><br>
                        <label>Nombre</label>
                        <input type="text" name="nombre" required value="<?php echo old('nombre'); ?>"><br>
                        <label>Asunto</label>
                        <input type="text" name="asunto" required value="<?php echo old('asunto'); ?>"><br>
                        <label>Mensaje:</label>
                        <textarea name="mensaje" required><?php echo old('mensaje'); ?></textarea>
                    </div>

                    <div class="centered m2">
                        <input type="submit" class="button" name="enviar" value="Enviar">
                    </div>
                </form>
            </section>

            <section class="flex1">
                <h2>Ubicación y mapa</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d95540.52695655498!2d1.9757250913879072!3d41.555359587398954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x12a4952ef0b8c6e9%3A0xb6f080d2f180b111!2s08227%20Terrassa%2C%20Barcelona!3m2!1d41.555389!2d2.0581259!5e0!3m2!1ses!2ses!4v1741018755243!5m2!1ses!2ses"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                <h3>Datos</h3>
                <p>
                    <b>CIFO Sabadell</b> - Carretera Nacional 150 km. 15, 08227 Terrassa<br>
                    Teléfono: 93 736 29 10<br>
                    <a href="mailto:valles.soc@gencat.cat">valles.soc@gencat.cat</a>
                </p>
            </section>

        </div>

        <!-- Botón de atrás -->
        <div class="centred">
            <a class="button" onclick="history.back()">Atrás</a>
        </div>
    </main>

    <?= $template->footer() ?>
    <?= $template->version() ?>
</body>

</html>