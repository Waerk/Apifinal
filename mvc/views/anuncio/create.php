<!DOCTYPE HTML>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Lista de Anuncios - <?= APP_NAME ?></title>
		
		<meta name="viewport" content="width=device-width, intial-scale=1.0">
		<meta name="description" content="Lista de libros en <?= APP_NAME ?>">
		<meta name="author" content="Robert Sallent">
		
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">
		<script src="/js/BigPicture.js"></script>
		<?= $template->css()?>
		<script src="/js/Preview.js"></script>
	</head>
	
	<body>
		<?= $template->login()?>
		<?= $template->header('Lista de Anuncios')?>
		<?= $template->menu()?>
		<?= $template->breadCrumbs([
		    'Anuncios' => '/Anuncio/list'
		])?>
		<?= $template->messages()?>
		
		<main>
			<h1><?= APP_NAME?></h1>
			<h2>Nuevo anuncio</h2>
			
			<form method="POST" enctype="multipart/form-data" action="/Anuncio/store">
			
				<div class="flex2">
					<label>Titulo</label>
					<input type="text" name="titulo" value="<?= old('titulo')?>">
					<br>
					<label>Descripcion</label>
					<textarea name="descripcion"><?= old('descripcion')?></textarea>
					<br>
					<label>Precio</label>
					<input type="number" name="precio" value="<?= old('precio')?>">
					<br>
					<label>Imagen</label>
                    <input type="file" name="imagen" accept="image/*" id="file-with-preview">
					<div class="centered mt2">
						<input type="submit" class="button" name="guardar" value="Guardar">
						<input type="reset" class="button" value="Reset">
					</div>
					 <div class="flex1 centrado">
                    <figure>
                        <img src="<?= ANUNCIO_IMAGE_FOLDER . '/' . DEFAULT_ANUNCIO_IMAGE ?>"
                            class="cover" id="preview-image" alt="Previsualización de la imagen">
                        <figcaption>Previsualización de la imagen</figcaption>
                    </figure>
                </div>
				</div>
			</form>
			<div class="centrado my2">
				<a class="button" onclick="history.back()">Atrás</a>
				<a class="button" href="/Anuncio/list">Lista de anuncios</a>
			</div>
		</main>
		<?= $template->footer(); ?>
		</body>
		</html>