<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BookmarkController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::enableQueryLog();
        $bookmarks = Bookmark::visibility(Auth::check())->with('labels')->orderBy('added_at', 'desc')->paginate(20);
        // dd(DB::getQueryLog());
        // $bookmarks = collect($bookmarks);
        // $bookmarks->first()->labels->dd();

        return $bookmarks;
    }
    
    public function search(Request $request)
    {
        DB::enableQueryLog();

        $q = $request->q;
        if(Str::contains($q, ',')) {
            $q = Str::of($q)->explode(',');
            // dd($q);
        }
        // для массива
        if(is_iterable($q)) $bookmarks = Bookmark::visibility(Auth::check())->searchLabel($q, Auth::check())->with('labels')->orderBy('added_at', 'desc')->paginate(20);
        // для строки
        else $bookmarks = Bookmark::visibility(Auth::check())->where('title','LIKE', "%$q%")->orWhere('url','LIKE', "%$q%")->searchLabel($q, Auth::check())->with('labels')->orderBy('added_at', 'desc')->paginate(20);
        // dump(DB::getQueryLog());
        return $bookmarks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->labels);
        $bookmark = Bookmark::where('url', '=', $request->url)->with('labels')->first();
        Log::debug($request);
        if(!$bookmark) {
            $bookmark = new Bookmark;
            $bookmark->title = $request->title;
            $bookmark->url = $request->url;
            $bookmark->description = $request->description;
            $bookmark->google_timestamp = $request->google_timestamp ? $request->google_timestamp : 0;
            $bookmark->google_id = $request->google_id ? $request->google_id : 0;
            $bookmark->added_at = Carbon::now();
        }
        else {
            $bookmark->title = $request->title;
            $bookmark->description = $request->description;
        }
        $bookmark->save();

        $labels = collect();
        foreach ($request->labels as $title)
        {
            $label = Label::firstOrCreate(['title' => $title]);
            $label->touch();
            $labels->push($label);
        }
        // dd($labels->pluck('id'));
        $bookmark->labels()->sync($labels->pluck('id'));
        $bookmark->refresh();
        return $bookmark;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
        $q = $request->q;

        //удаляем префиксы и последний слеш для совместимости со старыми ссылками, которые могли быть добавлены с ними
        $q = preg_replace('/^http(s)?:/', '', $q);
        $q = preg_replace('/^\/\//', '', $q);
        $q = preg_replace('/^www\./', '', $q);
        $q = preg_replace('/\/$/', '', $q);
        // return $q;
        $bookmark = Bookmark::visibility(Auth::check())->where('url','LIKE', "%$q%")->with('labels')->first();
        return $bookmark;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookmark $bookmark)
    {
        //
    }
}
