<?php
class AnuncioController extends Controller{
    public function index(){
        
        return $this->list();
    }
    
    
    public function list(){
        
        $anuncios = Anuncio::all();
        
        return view('anuncio/list',[
            'anuncios' => $anuncios]);
    }
    public function show(int $id = 0){
        
        $anuncio = Anuncio::findOrFail($id, "No se encontro el anuncio indicado");
        
        
            return view('anuncio/show',[
                'anuncio' => $anuncio
            ]);
    }
    public function create(){
        return view('anuncio/create');
    }
}