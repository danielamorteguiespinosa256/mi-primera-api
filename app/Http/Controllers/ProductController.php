<?php

namespace App\Http\Controllers;

// Importa el modelo Product (representa la tabla products)
use App\Models\Product;

// Permite manejar datos de peticiones HTTP
use Illuminate\Http\Request;

// Importa el Resource que define cómo se devuelve el JSON
use App\Http\Resources\ProductResource;

// Importa la validación para actualizar productos
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Lista los productos existentes en la base de datos
     */
    public function index()
    {
        // Consulta los productos ordenados por el más reciente
        // y los pagina en grupos de 10
        $products = Product::query()
            ->latest()
            ->paginate(10);

        // Devuelve la colección transformada con ProductResource
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     * Crea un nuevo producto
     */
    public function store(Request $request)
    {
        // Crea un producto usando los datos recibidos en la petición
        // (los campos deben estar definidos en fillable en el modelo)
        $product = Product::create($request->all());

        // Devuelve el producto creado transformado en JSON
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     * Muestra un producto específico
     */
    public function show(Product $product)
    {
        // Laravel busca automáticamente el producto por su ID
        // y lo devuelve usando el Resource
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * Actualiza un producto existente
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Actualiza el producto con los datos validados
        $product->update($request->validated());

        // refresh() vuelve a consultar el modelo actualizado
        return new ProductResource($product->refresh());
    }

    /**
     * Remove the specified resource from storage.
     * Elimina un producto
     */
    public function destroy(Product $product)
    {
        // Elimina el producto de la base de datos
        $product->delete();

        // Devuelve respuesta HTTP 204 (sin contenido)
        return response()->json(null, 204);
    }
}