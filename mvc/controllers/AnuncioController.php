<?php
class AnuncioController extends Controller{
    public function index(){
        
        return $this->list();
    }
    
    
    public function list(int $page = 1){
        
        $filtro = Filter::apply('anuncios');
        $limit = RESULTS_PER_PAGE;
        
        if ($filtro) {
            $total = Anuncio::filteredResults($filtro);
            $paginator = new Paginator('/anuncio/list', $page, $limit, $total);
            $anuncios = Anuncio::filter($filtro, $limit, $paginator->getOffset());
        } else {
            $total = Anuncio::total();
            $paginator = new Paginator('/anuncio/list', $page, $limit, $total);
            $anuncios = Anuncio::orderBy('titulo', 'ASC', $limit, $paginator->getOffset());
        }
        
        return view('anuncio/list', [
            'anuncios' => $anuncios,
            'paginator' => $paginator,
            'filtro' => $filtro
        ]);
    }
    public function show(int $id = 0){
        
        $anuncio = Anuncio::findOrFail($id, "No se encontro el anuncio indicado");
        
        
            return view('anuncio/show',[
                'anuncio' => $anuncio
            ]);
    }
    public function create(){
        Auth::check(); // Solo usuarios autenticados pueden crear anuncios
        return view('anuncio/create');
    }
    public function store(){
        
        Auth::check();
        if (!request()->has('guardar')) {
            throw new FormException('No se recibió el formulario');
        }
        
        $anuncio = new Anuncio();
        $anuncio->titulo = request()->post('titulo');
        $anuncio->descripcion = request()->post('descripcion');
        $anuncio->precio = request()->post('precio');
        $anuncio->idusuario = Login::user()->id;
        
        try {
            if ($errores = $anuncio->validate()) {
                throw new ValidationException("<br>" . arrayToString($errores, false, false, ".<br>"));
            }
            
            $anuncio->saneate();
            $anuncio->save();
            
            $file = request()->file('imagen', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                $anuncio->imagen = $file->store('../public/' . ANUNCIO_IMAGE_FOLDER, 'anuncio_');
                $anuncio->update();
            }
            
            Session::success("Anuncio '{$anuncio->titulo}' creado correctamente.");
            return redirect('/anuncio/show/' . $anuncio->id);
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect('/anuncio/create');
        } catch (SQLException $e) {
            Session::error("No se pudo guardar el anuncio.");
            if (DEBUG) throw new SQLException($e->getMessage());
            return redirect('/anuncio/create');
        } catch (Exception $e) {
            Session::warning("Anuncio guardado, pero no se pudo subir la imagen.");
            if (DEBUG) throw new UploadException($e->getMessage());
            return redirect('/anuncio/show/' . $anuncio->id);
        }
    }
    public function edit(int $id = 0)
    {
        Auth::check();
        if (!$id) {
            throw new NothingToFindException('No se indicó el anuncio a editar');
        }
        $anuncio = Anuncio::findOrFail($id, "No se encontró el anuncio con el ID indicado");
        // Verificar que el usuario autenticado es el propietario o un admin
        if ($anuncio->idusuario !== Login::user()->id && !Login::isAdmin()) {
            Session::error("No tienes permiso para editar este anuncio.");
            return redirect('/anuncio/list');
        }
        return view('anuncio/edit', ['anuncio' => $anuncio]);
    }
    public function update()
    {
        Auth::check();
        if (!request()->has('actualizar')) {
            throw new FormException('No se recibieron datos');
        }
        
        $id = intval(request()->post('id'));
        $anuncio = Anuncio::findOrFail($id, "No se ha encontrado el anuncio.");
        
        if ($anuncio->idusuario !== Login::user()->id && !Login::isAdmin()) {
            Session::error("No tienes permiso para editar este anuncio.");
            return redirect('/anuncio/list');
        }
        
        $anuncio->titulo = request()->post('titulo');
        $anuncio->descripcion = request()->post('descripcion');
        $anuncio->precio = request()->post('precio');
        
        try {
            if ($errores = $anuncio->validate()) {
                throw new ValidationException("<br>" . arrayToString($errores, false, false, ".<br>"));
            }
            
            $anuncio->saneate();
            $anuncio->update();
            
            $file = request()->file('imagen', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                if ($anuncio->imagen) {
                    File::remove('../public/' . ANUNCIO_IMAGE_FOLDER . '/' . $anuncio->imagen);
                }
                $anuncio->imagen = $file->store('../public/' . ANUNCIO_IMAGE_FOLDER, 'anuncio_');
                $anuncio->update();
            }
            
            Session::success("Actualización del anuncio '{$anuncio->titulo}' correcta.");
            return redirect('/anuncio/edit/' . $id);
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect('/anuncio/edit/' . $id);
        } catch (SQLException $e) {
            Session::error("No se pudo actualizar el anuncio.");
            if (DEBUG) throw new SQLException($e->getMessage());
            return redirect('/anuncio/edit/' . $id);
        } catch (UploadException $e) {
            Session::warning("Cambios guardados, pero no se modificó la imagen.");
            if (DEBUG) throw new UploadException($e->getMessage());
            return redirect('/anuncio/edit/' . $id);
        }
    }
    
    public function delete(int $id = 0)
    {
        Auth::check();
        $anuncio = Anuncio::findOrFail($id, "No existe el anuncio.");
        if ($anuncio->idusuario !== Login::user()->id && !Login::isAdmin()) {
            Session::error("No tienes permiso para eliminar este anuncio.");
            return redirect('/anuncio/list');
        }
        return view('anuncio/delete', ['anuncio' => $anuncio]);
    }
    
    public function destroy()
    {
        Auth::check();
        if (!request()->has('borrar')) {
            throw new FormException('No se recibió la confirmación');
        }
        
        $id = intval(request()->post('id'));
        $anuncio = Anuncio::findOrFail($id, "No se ha encontrado el anuncio.");
        
        if ($anuncio->idusuario !== Login::user()->id && !Login::isAdmin()) {
            Session::error("No tienes permiso para eliminar este anuncio.");
            return redirect('/anuncio/list');
        }
        
        try {
            if ($anuncio->imagen) {
                File::remove('../public/' . ANUNCIO_IMAGE_FOLDER . '/' . $anuncio->imagen, true);
            }
            $anuncio->deleteObject();
            Session::success("Se ha borrado el anuncio '{$anuncio->titulo}'.");
            return redirect('/anuncio/list');
        } catch (SQLException $e) {
            Session::error("No se pudo borrar el anuncio '{$anuncio->titulo}'.");
            if (DEBUG) throw new SQLException($e->getMessage());
            return redirect('/anuncio/delete/' . $id);
        } catch (FileException $e) {
            Session::warning("Se eliminó el anuncio, pero no se pudo eliminar la imagen.");
            if (DEBUG) throw new FileException($e->getMessage());
            return redirect('/anuncio/list');
        }
    }
}