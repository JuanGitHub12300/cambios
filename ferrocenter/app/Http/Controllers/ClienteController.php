<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCliente;

/**
 * Class ClienteController
 * @package App\Http\Controllers
 */
class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::paginate(10);

        return view('cliente.index', compact('clientes'))
            ->with('i', (request()->input('page', 1) - 1) * $clientes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = new Cliente();
        return view('cliente.create', compact('cliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCliente $request)
    {
        $cliente = new Cliente();
        $cliente->nombre_cliente = $request->nombre_cliente;
        $cliente->apellido_cliente  = $request->apellido_cliente;
        $cliente->direccion_cliente = $request->direccion_cliente;
        $cliente->telefono_cliente  = $request->telefono_cliente;
        $cliente->email_cliente  = $request->email_cliente;
        $cliente->save();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = Cliente::find($id);

        return view('cliente.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);

        return view('cliente.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Cliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        // request()->validate(Cliente::$rules);
        // $cliente->update($request->all());
        $cliente->nombre_cliente = $request->nombre_cliente;
        $cliente->apellido_cliente  = $request->apellido_cliente;
        $cliente->direccion_cliente = $request->direccion_cliente;
        $cliente->telefono_cliente  = $request->telefono_cliente;
        $cliente->email_cliente  = $request->email_cliente;
        $cliente->save();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($cliente_id)
{
    $cliente = Cliente::where('cliente_id', $cliente_id)->firstOrFail();
    $cliente->delete();

    return redirect()->route('clientes.index')->with('success', 'Cliente deleted successfully');
}
}
