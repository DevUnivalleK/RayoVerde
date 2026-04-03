<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreguntaChatbot extends Model
{
    protected $table = 'preguntas_chatbot';
    protected $primaryKey = 'id_pregunta';
    public $timestamps = false;

    protected $fillable = [
        'id_conversacion',
        'categoria',
        'pregunta',
        'respuesta',
        'activa'
    ];

    public function conversacion()
    {
        return $this->belongsTo(ConversacionChatbot::class, 'id_conversacion');
    }
}
