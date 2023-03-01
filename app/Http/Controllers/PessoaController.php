<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PessoaRequest;

class PessoaController extends Controller
{
    public function index(Request $request) : JsonResponse
    {
        if($request->itemsPerPage === '-1'){
            $request->itemsPerPage = Pessoa::count();
        }

        $pessoasQuery = Pessoa::select();

        if(isset($request->sortBy)) {
            for ($i = 0; $i < count($request->sortBy); $i++) {
                $pessoasQuery->orderBy($request->sortBy[$i], ($request->sortDesc[$i] === 'true') ? 'DESC' : 'ASC');
            }
        }
        else{
            $pessoasQuery->orderBy('nome', 'DESC');
        }

        if($request->search){
            $search = explode(' ', $request->search);
            foreach ($search as $valSearch) {
                $pessoasQuery->where('nome', 'LIKE', "%" . $valSearch . "%");
            }
        }

        $pessoas = $pessoasQuery->paginate($request->itemsPerPage ?? 25);
        return response()->json($pessoas, 200);
    }

    public function store(PessoaRequest $request) : JsonResponse
    {
        $data = $request->all();

        $pessoa = Pessoa::create($data);

        if(isset($data['telefones']) && count($data['telefones'])) {
            $pessoa->telefones()->createMany($data['telefones']);
        }

        return  response()->json($pessoa, 201);
    }

    public function show($id) : JsonResponse
    {
        return response()->json(Pessoa::with(['telefones'])->findOrFail($id), 200);
    }

    public function update(PessoaRequest $request, Pessoa $pessoa) : JsonResponse
    {
        $data = $request->all();

        $pessoa->update($data);

        return  response()->json($pessoa, 201);
    }

    public function destroy(Pessoa $pessoa) : JsonResponse
    {
        $pessoa->delete();

        return response()->json([], 200);
    }
}
