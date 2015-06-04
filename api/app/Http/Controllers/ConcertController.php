<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function index()
    {
        $concerts = Concert::all();

        return response()->json($concerts);
    }

    public function getConcert($id)
    {
        $concert = Concert::find($id);

        return response()->json($concert);
    }

    public function saveConcert(Request $request)
    {
        $concert = Concert::create($request->json()->all());

        return response()->json($concert);
    }

    public function deleteConcert($id)
    {
        $concert = Concert::find($id);

        $concert->delete();

        return response('', 204);
    }

    public function updateConcert(Request $request, $id)
    {
        $concert = Concert::find($id);

        $concert->title = $request->json()->get('title');
        $concert->description = $request->json()->get('description');
        $concert->date = $request->json()->get('date');

        $concert->save();

        return response()->json($concert);
    }
}
