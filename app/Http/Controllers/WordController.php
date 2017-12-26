<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Config;

use Illuminate\Http\Request;
use App\User;
use App\Word;
use App\Definition;
use App\Example;

class WordController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    // TODO: save the word with history function
    public function save(Request $request) {
        // Save bookmarked word by the user to the db
            // only attach the word to the user if the word already exists
            if($oldWord = Word::where('word', $request->word)->first()) {
                $request->user()->words()->save($oldWord, ['type' => 'marked']);
                return response()
                ->json([
                    'saved'=>true,
                    'message'=>'Word Saved!',
                    'word' => $oldWord,
                ]);
            }
            
            // save word and attach to the user
            $word = new Word();
            $word->word = $request->word;
            $word->pronunciation = $request->pronunciation;
            $request->user()->words()->save($word, ['type' => 'marked']);


            // save definitions
            $definitions = [];
            foreach($request->definitions as $definition) {
                $newDefinition = new Definition($definition);
                $definitions[] = $newDefinition;
            }
            $word->definitions()->saveMany($definitions);


        return response()
            ->json([
                'saved'=>true,
                'message'=>'Word Saved!',
                'word' => $word,
            ]);
    }

    public function destroy($word, Request $request) {
        // detach the word from the user, don't delete the word
        $foundWord = Word::where('word', $word)->first();
        $request->user()->words()->detach($foundWord->id);
        return response()
            ->json([
                    'word' => $word,
                    $foundWord->word
                ]);
    }

    public function isliked(Request $request) {
        // Check if the user like the word
        if($likedWord = $request->user()->words()->where('word', $request->word)->first()) {
            return response()->json(['userLiked'=>true]);
        } else {
            return response()->json(['userLiked' => false]);
        }
    }

    public function userword(Request $request) {
        // Read bookmarked words from the user
        Config::set("app.timezone", $request->tz);
        $date = $request->date;
        $words = $request->user()->words()->whereDate('user_word.created_at','=', $date)->get();
        $converted = [];
        foreach($words as $word) {
            $converted[] = [
                'id'=>$word->id,
                'word'=>$word->word,
                'definition'=>$word->definitions()->get()->first(),
                'created'=>$word->pivot->updated_at->diffForHumans()
            ];
        }
        return ['userwords'=>$converted];
    }
}
