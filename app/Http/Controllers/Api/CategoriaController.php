<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class CategoriaController extends Controller {
    
    public function __construct() {
        $this->middleware('auth.jwt', ['except' => ['index', 'show']]);
    }

    public function index() {
        return response()->json(Categoria::all());
    }

    public function store(Request $request) {
        $categoria = $request->validate([
            'nombre' => 'required|min:3|max:30',
            'img_light' => 'required|image|dimensions:max_width=200,max_height=200',
            'img_light_selected' => 'required|image|dimensions:max_width=200,max_height=200',
            'img_dark' => 'required|image|dimensions:max_width=200,max_height=200',
            'img_dark_selected' => 'required|image|dimensions:max_width=200,max_height=200',
        ]);
    
        $categoria = new Categoria($request->all());
    
        // Genera un nombre único para cada imagen usando hashName()
        $imgLightName = $request->img_light->hashName();
        $imgLightSelectedName = $request->img_light_selected->hashName();
        $imgDarkName = $request->img_dark->hashName();
        $imgDarkSelectedName = $request->img_dark_selected->hashName();
    
        // Guarda las imágenes en la carpeta 'public/categorias' dentro de 'storage'
        $pathLight = $request->img_light->storeAs('public/categorias', $imgLightName);
        $pathLightSelected = $request->img_light_selected->storeAs('public/categorias', $imgLightSelectedName);
        $pathDark = $request->img_dark->storeAs('public/categorias', $imgDarkName);
        $pathDarkSelected = $request->img_dark_selected->storeAs('public/categorias', $imgDarkSelectedName);
    
        // Almacena las rutas relativas en la base de datos
        $categoria->img_light = $pathLight;
        $categoria->img_light_selected = $pathLightSelected;
        $categoria->img_dark = $pathDark;
        $categoria->img_dark_selected = $pathDarkSelected;
    
        $categoria->save();
    
        return response()->json($categoria, 201);
    }
    
    
    

    public function show($id) {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria);
    }

    public function update(Request $request, $id) {
        // Obtener la categoría a actualizar
        $categoria = Categoria::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'nullable|string|min:3|max:30',
            'img_light' => 'nullable|image|dimensions:max_width=200,max_height=200',
            'img_light_selected' => 'nullable|image|dimensions:max_width=200,max_height=200',
            'img_dark' => 'nullable|image|dimensions:max_width=200,max_height=200',
            'img_dark_selected' => 'nullable|image|dimensions:max_width=200,max_height=200',
        ]);
        if (isset($validated['nombre'])) {
            $categoria->nombre = $validated['nombre'];
        }
        if ($request->hasFile('img_light')) {
            if ($categoria->img_light && Storage::exists($categoria->img_light)) {
                Storage::delete($categoria->img_light);
            }
            $path = $request->file('img_light')->store('categorias');
            $categoria->img_light = $path;
        }
    
        if ($request->hasFile('img_light_selected')) {
            if ($categoria->img_light_selected && Storage::exists($categoria->img_light_selected)) {
                Storage::delete($categoria->img_light_selected);
            }
            $path = $request->file('img_light_selected')->store('categorias');
            $categoria->img_light_selected = $path;
        }
    
        if ($request->hasFile('img_dark')) {
            if ($categoria->img_dark && Storage::exists($categoria->img_dark)) {
                Storage::delete($categoria->img_dark);
            }
            $path = $request->file('img_dark')->store('categorias');
            $categoria->img_dark = $path;
        }
    
        if ($request->hasFile('img_dark_selected')) {
            if ($categoria->img_dark_selected && Storage::exists($categoria->img_dark_selected)) {
                Storage::delete($categoria->img_dark_selected);
            }
            $path = $request->file('img_dark_selected')->store('categorias');
            $categoria->img_dark_selected = $path;
        }
        $categoria->save();
        return response()->json($categoria, 200);
    }
    

    public function destroy($id) {
        // eliminamos el archivo y ademas eliminamos la categoria
       $categoria = Categoria::findOrFail($id);
       if ($categoria) {
            if ($categoria->img_light && Storage::exists($categoria->img_light)) {
                Storage::delete($categoria->img_light);
            }
            if ($categoria->img_light_selected && Storage::exists($categoria->img_light_selected)) {
                Storage::delete($categoria->img_light_selected);
            }
            if ($categoria->img_dark && Storage::exists($categoria->img_dark)) {
                Storage::delete($categoria->img_dark);
            }
            if ($categoria->img_dark_selected && Storage::exists($categoria->img_dark_selected)) {
                Storage::delete($categoria->img_dark_selected);
            }
            $categoria->delete();
       }
        return response()->json(null, 204);
    }

}
