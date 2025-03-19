<?php
class Anuncio extends Model{
    
    //campos en los que se permite asignación masiva
    protected static $fillable =[
        'precio','descripcion','fecha','imagen',
        'titulo'
    ];   
    public function users()
    {
        return $this->belongsTo('User', 'idusuario', 'id');
    }
}