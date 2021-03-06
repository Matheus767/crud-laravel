<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request) {
        $series = Serie::query()->orderBy('nome')->get();
        $mensagem = $request->session()->get('mensagem');
        

        return view('series.index', compact('series', 'mensagem') );
    }
    public function create()
    {
        return view('series.create');
    }   
    public function store(SeriesFormRequest $request)
    {
        $serie = Serie::create(['nome' => $request->nome]);
        $qtdTemporadas = $request->qtd_temporadas;
        for ($i=1; $i <= $qtdTemporadas; $i++) {
            $temporada = $serie->temporadas()->create(['numero' => $i]);

            for ($j=1; $j <= $request->ep_por_temporada; $j++ ) {
                $episodio = $temporada->episodios()->create(['numero' => $j]);
            }
        }

        $request->session()->flash("mensagem", "Série {$serie->id} e suas temporadas e episódios criados com sucesso {$serie->nome}!");

        return redirect()-> route('listar_series');
    }

    public function destroy(Request $request)
    {
        Serie::destroy($request->id);
        $request->session()->flash("mensagem", "A série foi removida com sucesso     !");

        return redirect()-> route('listar_series');
    }
}