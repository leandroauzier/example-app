<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SeriesImport;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Series::all();
        $mensagemSucesso = session('mensagem.sucesso');

        return view('series.index')->with('series', $series)->with('mensagemSucesso', $mensagemSucesso);
    }
    public function create()
    {
        return view('series.create');
    }
    public function store(SeriesFormRequest $request)
    {
        $serie = Series::create($request->all());
        $seasons = [];
        for ($i=1; $i <= $request->seasonsQty; $i++) {
            $seasons[] = [
                'series_id' => $serie->id,
                'number' => $i,
            ];
        }
        Season::insert($seasons);

        $episodes = [];
        foreach ($serie->seasons as $season) {
            for ($j = 1; $j <= $request->episodesPerSeason; $j++) {
                $episodes[] = [
                    'season_id' => $season->id,
                    'number' => $j,
                ];
            }
        }
        Episode::insert($episodes);

        $request->session()->flash('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
        return to_route('series.index');
    }
    public function destroy(Series $series, Request $request)
    {

        $series->delete();
        $request->session()->flash('mensagem.sucesso', "Série {$series->nome} removida com sucesso");

        return to_route('series.index');
    }
    public function edit(Series $series)
    {
        return view('series.edit')->with('serie',$series);
    }
    public function update(Series $series, SeriesFormRequest $request)
    {
        // Fill vem do fillable, e ele n faz o save como o Create faria, entao precisa do save()
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }

    public function upload(Request $request)
    {
        Excel::import(new SeriesImport, $request->file,null,\Maatwebsite\Excel\Excel::XLSX);
        
        return to_route('/series')->with('success', 'All good!');
        /*
        $start_row = 1;
        if(($csv_file = fopen($request, "r")) !== false){
            while(($read_data = fgetcsv($csv_file,1000,";")) !== false){
                $column_count = count($read_data);

                $start_row++;
                for($c= 0; $c < $column_count; $c++){
                    echo $read_data;
                }
            }
            fclose($csv_file);
        }
        var_dump($request->all());
        /*
        $serie = Series::create($request->all());
        $seasons = [];
        for ($i=1; $i <= $request->seasonsQty; $i++) {
            $seasons[] = [
                'series_id' => $serie->id,
                'number' => $i,
            ];
        }
        Season::insert($seasons);

        $episodes = [];
        foreach ($serie->seasons as $season) {
            for ($j = 1; $j <= $request->episodesPerSeason; $j++) {
                $episodes[] = [
                    'season_id' => $season->id,
                    'number' => $j,
                ];
            }
        }
        Episode::insert($episodes);

        $request->session()->flash('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
        */
    }
}