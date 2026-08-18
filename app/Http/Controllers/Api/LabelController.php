<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use DB;

class labelController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //берем 100 самых последних меток
        // $last_labels = Label::visibility(Auth::check())->orderBy('updated_at', 'desc')->take(100)->get();
        // return $last_labels->pluck('title');
        //берем 100 самых популярных меток
        // $most_labels = Label::visibility(Auth::check())->withCount('bookmarks')->orderBy('bookmarks_count', 'desc')->take(100)->get();
        // return $most_labels->pluck('title');

        $labels = Label::visibility(Auth::check())->orderBy('updated_at', 'desc')->get(); //надо добавить колонку last_used_at и писать туда дату последнего использования, и по частоте выводить
        return $labels->pluck('title');
    }

    public function search(Request $request)
    {
        DB::enableQueryLog();
        $q = $request->q;
        // $bookmarks = Bookmark::visibility(Auth::check())->where('title','LIKE', "%$q%")->orWhere('url','LIKE', "%$q%")->haveLabel($q)->with('labels')->orderBy('updated_at', 'desc')->paginate(20);
        $bookmarks = Bookmark::visibility(Auth::check())->haveLabel($q)->with('labels')->orderBy('updated_at', 'desc')->paginate(20);
        // dd(DB::getQueryLog());
        return $bookmarks;
    }

    public function stats()
    {
        $labels = Label::visibility(Auth::check())->orderBy('title', 'asc')->withCount('bookmarks')->get();
        return $labels;
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
        
        $label = Label::visibility(Auth::check())->where('title','LIKE', $request->q)->firstOrFail();
        
        if($request->q === $request->new_label) { //если старое и новое название одинаковые
            // print("обновляем приватность");
            $label->private = $request->private;
            $label->save();
            return $label;
        }


        //если старое и новое название отличаются
        $new_label = Label::visibility(Auth::check())->where('title','LIKE', $request->new_label)->first();

        if($new_label) { //если новое название уже существует
            print("всем ссылкам меняем связь на новый ид");

            // not work Duplicate entry '2958-1293' for key 'bookmark_label_bookmark_id_label_id_unique'
            // update sql 1062 Duplicate entry for key _unique
            
            $affected = DB::table('bookmark_label')->where('label_id', '=', $label->id)->update(array('label_id' => $new_label->id));

            // $bookmarks = Bookmark::searchLabel($request->q, Auth::check())->with('labels')->get();
            
            // foreach($bookmarks as $bookmark)
            // {
            //     DB::enableQueryLog();
            //     $bookmark->labels()->updateExistingPivot($label->id, ['label_id' => $new_label->id]);
            //     dump(DB::getQueryLog());
            //     echo "меняем ид для записи " . $bookmark->title . PHP_EOL;
            // }


            return "изменено {$bookmarks->count()} записей";
        }
        else { //если нового названия не существует
            $label = Label::visibility(Auth::check())->where('title','LIKE', $request->q)->first();
            print("изменяем title записи без передачи ссылок");
        }
        
     }

    public function show(Request $request)
    {
        $q = $request->q;
        $label = Label::visibility(Auth::check())->where('title','LIKE', "$q")->first();
        return $label;
    }
}
