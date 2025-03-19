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
			<h2>Lista completa de anuncios</h2>
			
			<?php if($anuncios) {?>
			<table class="table w100">
				<tr>
					<th>Foto</th>
					<th>Titulo</th>
					<th>Descripcion</th>
					<th>Precio</th>
					<th>Fecha</th>
					<th class="centrado">Operaciones</th>
				</tr>
	
			<?php foreach($anuncios as $anuncio){ ?>
			<tr>
				<td><a href='/Anuncio/show/<?= $anuncio->id ?>'><?= $anuncio->foto ?></a></td>
				<td><a href='/Anuncio/show/<?= $anuncio->id ?>'><?= $anuncio->titulo ?></a></td>.
				<td><?= $anuncio->descripcion?></td>
				<td><?= $anuncio->precio?></td>
				<td><?= $anuncio->fecha?></td>
				<td class="centrado">
					<a href='/Anuncio/show/<?= $anuncio->id ?>'>Ver</a>-
					<a href='/Anuncio/edit/<?= $anuncio->id ?>'>Editar</a>-
					<a href='/Anuncio/delete/<?= $anuncio->id ?>'>Borrar</a>
				</td>
			</tr>
			<?php } ?>	
			</table>
			<?php }else{ ?>
			<div class="danger p2">
				<p>No hay anuncios que mostrar.</p>
			</div>
			<?php } ?>
			
			<div class="centered">
				<a class="button" onclick="history.back()">Atras</a>
			</div>
			
		</main>
		<?= $template->footer()?>
	</body>
</html>