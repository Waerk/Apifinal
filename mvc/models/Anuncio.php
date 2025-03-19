<?php
class Anuncio extends Model{
    
    //campos en los que se permite asignación masiva
    protected static $fillable =[
        'precio','descripcion','fecha','foto',
        'titulo'
    ];   
}