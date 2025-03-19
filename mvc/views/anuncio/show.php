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
			<section>
			<h2><?= $anuncio->titulo ?></h2>
			
			<p><b>Descripcion:</b>	<?= $anuncio->descripcion?></p>
			<p><b>Precio:</b>	<?= $anuncio->precio?></p>
			<p><b>Fecha:</b>	<?= $anuncio->fecha?></p>	
			</section>
			
		<div class="centrado">
			<a class="button" onclick="history.back()">Atras</a>
			<a class="button" href="/Anuncio/list">Lista de anuncios</a>
			<a class="button" href="/Anuncio/edit/<?= $anuncio->id ?>">Editar</a>
			<a class="buttoN" href="/Anuncio/delete/<?= $anuncio->id?>">Borrar</a>
		</div>
		</main>
			
			