<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Example;

class ExampleController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(Request $request) {
        $examples = $request->user()->examples()->get();
        $converted = [];
        foreach($examples as $example) {
            $converted[] = [
                'example'=>$example->example,
                'created_at'=>$example->created_at->diffForHumans()
            ];
        }
        return ['examples' => $converted];
    }

    public function exampleByDate(Request $request) {
        Config::set("app.timezone", $request->tz);
        $date = $request->date;
        $example = $request->user()->examples()->whereDate('example.created_at', "=", $date)->get()->first();
        $example = [
            'example'=>$example->example,
            'created_at'=>$example->created_at->diffForHumans()
        ];
        return ['example' => $example];
    }

    public function save(Request $request) {
        $this->validate($request, [
            'example' => 'required',
        ]);
        $example = new Example;
        $example->example = $request->example;
        $request->user()->examples()->save($example);

        return response()
            ->json([
                'composed'=>true
            ]);
    }
}
