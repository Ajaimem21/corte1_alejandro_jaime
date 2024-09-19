<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    public function index()
    {
        $products = Product::all();

        if($products->isEmpty()){
            $data = ['message' => 'No se encontraron productos',
                     'status'=> 404];
            return response()->json($data, 404);
        }

        return response()->json($products, 200);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:calzado,ropa,joyería,electronico,electrodomestico', // Valores válidos
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            
        ]);

        if($validator-> fails()){
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        
        $product = Product::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'product' => $product,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $product->delete();

        $data = [
            'message' => 'Producto eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $product = product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:calzado,ropa,joyería,electronico,electrodomestico', // Valores válidos
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product->codigo = $request->codigo;
        $product->nombre = $request->nombre;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $product->save();

        $data = [
            'message' => 'Producto actualizado',
            'product' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);

    }
}
