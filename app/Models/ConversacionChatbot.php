<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversacionChatbot extends Model
{
    protected $table = 'conversaciones_chatbot';
    protected $primaryKey = 'id_conversacion';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'finalizada_en',
        'estado',
        'derivada_a_agente'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function preguntas()
    {
        return $this->hasMany(PreguntaChatbot::class, 'id_conversacion');
    }
}