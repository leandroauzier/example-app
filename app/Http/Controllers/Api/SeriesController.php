<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Season;
use App\Models\Series;

class SeriesController extends Controller
{
    public function index()
    {
        return Series::all();
    }
    public function store(SeriesFormRequest $request)
    {
        return response()->json($serie = Series::create($request->all()), 201);
    }
    public function show(int $series)
    {
        $seriesModel = Series::with('seasons.episodes')->find($series);
        if ($seriesModel === null) {
            return response()->json(['message' => 'Series not found'], 404);
        }
        return $seriesModel;
    }
    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();
        return $series;
    }
    public function destroy(int $series)
    {
        Series::destroy($series);
        return response()->noContent();
    }
}