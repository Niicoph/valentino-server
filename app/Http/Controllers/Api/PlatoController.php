<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;

class PlatoController extends Controller {

    public function __construct() {
        $this->middleware('auth:api' , ['except' => ['index', 'show' , 'platosPorCategoria']]);
    }

    public function index() { 
        return response()->json(Plato::all() , 200);
    }

    public function store(Request $request) {
        $plato = $request->validate([
            'nombre' => 'required|min:3|max:30',
            'descripcion' => 'required|min:3|max:255',
            'valor' => 'required|numeric',
            'categoria_id' => 'required|numeric',
        ]);

        $plato = new Plato($request->all());
        $plato->save();

        return response()->json($plato, 201);
    }

    public function show($id) {
        $plato = Plato::findOrFail($id);
        return response()->json($plato);
    } 
    public function update(Request $request, $id) {
        $newData = $request->validate([
            'nombre' => 'min:3|max:30',
            'descripcion' => 'min:3|max:255',
            'valor' => 'numeric',
            'categoria_id' => 'numeric',
        ]);

        $plato = Plato::findOrFail($id);
        $plato->update($newData);
        return response()->json($plato, 200);
    }

    // controlador para recuperar todos los platos que pertenecen a una categoria
    public function platosPorCategoria($id) {
        $platos = Plato::where('categoria_id', $id)->get();
        return response()->json($platos, 200);
    }

    public function destroy($id) {
        Plato::destroy($id);
        return response()->json(null, 204);
    }


}
