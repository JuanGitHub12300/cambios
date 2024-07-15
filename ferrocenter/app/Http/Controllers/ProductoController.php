<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProducto;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

/**
 * Class ProductoController
 * @package App\Http\Controllers
 */
class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('can:view.products')->only('index', 'show');
        $this->middleware('can:create.products')->only('create', 'store');
        $this->middleware('can:edit.products')->only('edit', 'update');
        $this->middleware('can:delete.products')->only('destroy');
    }
    public function index()
    {
        $productos = Producto::paginate(10);

        return view('producto.index', compact('productos'))
            ->with('i', (request()->input('page', 1) - 1) * $productos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $producto = new Producto();
        $categorias = Categoria::all();
        return view('producto.create', compact('producto', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_producto' => 'required|string|max:100',
                'descripcion_producto' => 'nullable|string',
                'precio_unitario' => 'required|numeric',
                'categoria_id' => 'required|exists:categorias,categoria_id',
            ]);

            $producto = Producto::create([
                'nombre_producto' => $request->nombre_producto,
                'descripcion_producto' => $request->descripcion_producto,
                'precio_unitario' => $request->precio_unitario,
                'categoria_id' => $request->categoria_id,
            ]);

            // Actualizar inventario
            $this->updateInventory($producto->producto_id, 0);  // Stock inicial en 0

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'producto' => [
                        'producto_id' => $producto->producto_id,
                        'nombre_producto' => $producto->nombre_producto,
                    ],
                ]);
            }

            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el producto: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Error al crear el producto: ' . $e->getMessage()]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::with('categoria')->find($id);

        return view('producto.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        $categorias = Categoria::all();

        return view('producto.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:100',
            'descripcion_producto' => 'nullable|string',
            'precio_unitario' => 'required|numeric',
            'categoria_id' => 'required|exists:categorias,categoria_id',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Producto::find($id)->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto deleted successfully');
    }
    public function exportPdf()
    {
        $productos = Producto::all();
        $pdf = PDF::loadView('producto.pdf', compact('productos'));
        $currentDate = Carbon::now()->format('d-m-Y'); // Formato de fecha: dd-mm-yyyy
        $filename = 'Productos-' . $currentDate . '.pdf';
        return $pdf->download($filename);
    }
    private function updateInventory($producto_id, $cantidad)
    {
        $inventario = Inventario::where('producto_id', $producto_id)->first();

        if ($inventario) {
            $inventario->stock += $cantidad;
            $inventario->fecha_movimiento = now();
            $inventario->save();
        } else {
            Inventario::create([
                'producto_id' => $producto_id,
                'stock' => $cantidad,
                'fecha_ingreso' => now(),
                'fecha_movimiento' => now(),
                'tipo_movimiento' => 'compra',
            ]);
        }
    }
}
