<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transportadora;

class TransportadoraController extends Controller
{
    function index() {
        return Transportadora::all();               
    }

    function store(Request $request) {
        //valida os campos
        $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'UF' => 'required',
            'limite_credito' => 'required',
            'data_analise_credito' => 'required',
        ], [
            'required' => 'O campo :attribute é obrigatório'
        ]);
        $request->merge(['data_analise_credito' => date('Y-m-d', strtotime($request->data_analise_credito))]);
        $transportadora = Transportadora::create($request->all());
        return response()->json($transportadora, 201);
    }

    function update(Request $request, $id) {
        $transportadora = Transportadora::find($id);
        if ($transportadora == null) {
            return response()->json(['erro' => 'Transportadora não encontrada'], 404);
        }
        $request->validate([
            'nome' => 'required',
            'endereco' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'UF' => 'required',
            'limite_credito' => 'required',
            'data_analise_credito' => 'required',
        ], [
            'required' => 'O campo :attribute é obrigatório'
        ]);
        $request->merge(['data_analise_credito' => date('Y-m-d', strtotime($request->data_analise_credito))]);
        $transportadora->update($request->all());
        return response()->json($transportadora);
    }

    function destroy($id) {
        $transportadora = Transportadora::find($id);
        if ($transportadora == null) {
            return response()->json(['erro' => 'Transportadora não encontrada'], 404);
        }
        $transportadora->delete();
        return response()->json(['sucesso' => 'Transportadora excluída com sucesso']);
    }
}
