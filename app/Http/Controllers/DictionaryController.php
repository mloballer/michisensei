<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dictionary;

class DictionaryController extends Controller
{
    public function index()
    {
        //load the dictionary search homepage
        return view('index');
    }

    //this method uses a raw query instead of a model
    public function lookupWordRaw(Request $request)
    {
        //get the search word and options from the request
        $word   = $request->input('word');
        $option = $request->input('option');

        //if empty search return
        if(!$word) return;

        //modify SQL based on the option
        switch ($option) {
            case "contain":
                $where_caluse = " WHERE BINARY word LIKE '%$word%' OR BINARY reading LIKE '%$word%' OR meaning LIKE '%$word%' ";
                break;
            case "start":
                $where_caluse = " WHERE BINARY word LIKE '$word%' OR BINARY reading LIKE '$word%' OR meaning LIKE '%$word%' ";
                break;
            case "end":
                $where_caluse = " WHERE BINARY word LIKE '%$word' OR BINARY reading LIKE '%$word' OR meaning LIKE '%$word%' ";
                break;
            case "are":
                $where_caluse = " WHERE BINARY word = '$word' OR BINARY reading = '$word' OR meaning LIKE '%$word%' ";
                break;
            default:
                $where_caluse = " ";
        }

        //run a raw query, get the results, limit to 9,999
        $results = DB::select("SELECT * FROM name_dict $where_caluse LIMIT 9999");

        //convert from an object into an array
        $results = convertToArray($results);

        return $results;
    }

    public function lookupWord(Request $request)
    {
        $word   = $request->input('word');
        $option = $request->input('option');

        if (!$word) {
            return;
        }

        $dictionary = new Dictionary();

        switch ($option) {
            case "contain":
                $results = $dictionary->where(function ($query) use ($word) {
                    $query->where(DB::raw("BINARY word"), 'like', "%$word%")
                        ->orWhere(DB::raw("BINARY reading"), 'like', "%$word%")
                        ->orWhere(DB::raw(" meaning"), 'like', "%$word%");
                })->limit(9999)->get();
                break;
            case "start":
                $results = $dictionary->where(function ($query) use ($word) {
                    $query->where(DB::raw("BINARY word"), 'like', "$word%")
                        ->orWhere(DB::raw("BINARY reading"), 'like', "$word%")
                        ->orWhere(DB::raw(" meaning"), 'like', "%$word%");
                })->limit(9999)->get();
                break;
            case "end":
                $results = $dictionary->where(function ($query) use ($word) {
                    $query->where(DB::raw("BINARY word"), 'like', "%$word")
                        ->orWhere(DB::raw("BINARY reading"), 'like', "%$word")
                        ->orWhere(DB::raw(" meaning"), 'like', "%$word%");
                })->limit(9999)->get();
                break;
            case "are":
                $results = $dictionary->where(function ($query) use ($word) {
                    $query->where(DB::raw("BINARY word"), '=', $word)
                        ->orWhere(DB::raw("BINARY reading"), '=', $word)
                        ->orWhere(DB::raw(" meaning"), 'like', "%$word%");;
                })->limit(9999)->get();
                break;
            default:
                $results = $dictionary->limit(9999)->get();
        }

        return $results->toArray();
    }

    

}
