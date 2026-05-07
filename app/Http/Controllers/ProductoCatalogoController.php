<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoCatalogoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();

        return view('catalogo', compact('productos'));
    }
}