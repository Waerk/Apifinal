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
			<!-- FILTRO -->
			 <?php
                if ($filtro) {
                    echo $template->removeFilterForm($filtro, '/anuncio/list');
                } else {
                    echo $template->filterForm(
                        ['Título' => 'titulo', 'Precio' => 'precio'],
                        ['Título' => 'titulo', 'Precio' => 'precio'],
                        'Título',
                        'Título'
                    );
                }
                ?>
			<!-- Lista de anuncios -->
			<?php if($anuncios) {?>
			<div class="right">
				<?= $paginator->stats()?>
			</div>
			
			<table class="table w100">
				<tr>
					<th>Foto</th>
					<th>Titulo</th>
					<th>Descripcion</th>
					<th>Precio</th>
					<th class="centrado">Operaciones</th>
				</tr>
	
			<?php foreach($anuncios as $anuncio){ ?>
			<tr>
				<td class="centrado">
                    <a href="/anuncio/show/<?= $anuncio->id ?>">
                       <img src="<?= ANUNCIO_IMAGE_FOLDER . '/' . ($anuncio->imagen ?? DEFAULT_ANUNCIO_IMAGE) ?>"
                        class="table-image" alt="Imagen de <?= $anuncio->titulo ?>"
                        title="Imagen de <?= $anuncio->titulo ?>">
                   </a>
               </td>
				<td><a href='/Anuncio/show/<?= $anuncio->id ?>'><?= $anuncio->titulo ?></a></td>.
				<td><?= $anuncio->descripcion?></td>
				<td><?= $anuncio->precio?></td>
				<td class="centrado">
					<a href='/Anuncio/show/<?= $anuncio->id ?>'>Ver</a>-
					<a href='/Anuncio/edit/<?= $anuncio->id ?>'>Editar</a>-
					<a href='/Anuncio/delete/<?= $anuncio->id ?>'>Borrar</a>
				</td>
			</tr>
			<?php } ?>	
			</table>
			<?= $paginator->ellipsisLinks()?>
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