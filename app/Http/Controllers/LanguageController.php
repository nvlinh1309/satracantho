<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    public function update($locale)
    {
        $translations = Translation::where('locale', $locale)->get();
        return view('update')->with('translations', $translations);
    }

    public function delete(Request $request){
        Translation::where('id', $request->id)->orWhere('parent_id', $request->id)->delete();

        $getLocale = DB::table('ltm_translations')->where('parent_id','<>',0)->select('locale')->distinct()->get(); //lấy được locale

        foreach ($getLocale as $getLoc) {

            $getTranslationsKey = Translation::where('parent_id', '<>', 0)->where('locale', $getLoc->locale)->get();
            $arrar_start = "<?php return[";
            $arrar_body = '';
            foreach ($getTranslationsKey as $getTran) {
                $arrar_body = $arrar_body . "'" . $getTran->value . "'" . "=>'" . $getTran->translation . "',";
            }
            $arrar_end = "];";
            $arra_lang = $arrar_start . $arrar_body . $arrar_end;
            $myfile = fopen(resource_path('lang/' . $getTran->locale . '/front.php'), 'w');
            fwrite($myfile, $arra_lang);
        }

    }

    public function postInsert(Request $request)
    {
        $locale=$request->input('locale');

        $arrays=Translation::where('parent_id',0)->get();
        $arrar_start = "<?php return[";
        $arrar_body = '';
        foreach ($arrays as $arr) {
            $tran = new Translation();
            $tran->status = 1;
            $tran->locale = $locale;
            $tran->value = $arr->value;
            $tran->translation = '';
            $tran->parent_id=$arr->id;
            $tran->save();
            $arrar_body = $arrar_body . "'" . $arr->value . "'" . "=>'',";
        }
        $arrar_end = "];";

        $arra_lang = $arrar_start . $arrar_body . $arrar_end;

        mkdir(resource_path('lang/'.$locale), 0777, true);
        $myfile = fopen(resource_path('lang/'.$locale.'/front.php'), 'w');
        fwrite($myfile, $arra_lang);
        return redirect()->back();
    }

    public function postUpdate(Request $request){
        $locale=$request->locale;
        $id = $request->id;
        $value = $request->value;
        $tran = Translation::find($id);
        $tran->translation = $value;
        $tran->save();


        $translations=Translation::where('locale',$locale)->get();

        $arrar_start = "<?php return[";
        $arrar_body = '';

        foreach ($translations as $arr) {
            $arrar_body = $arrar_body . "'" . $arr->value . "'" . "=>'".$arr->translation."',";
        }
        $arrar_end = "];";
        $arra_lang = $arrar_start . $arrar_body . $arrar_end;
        $myfile = fopen(resource_path('lang/'.$locale.'/front.php'), 'w');
        fwrite($myfile, $arra_lang);

    }
    public function updateKey(Request $request){
        $id = $request->id;
        $value = $request->value;
        Translation::where(function($query) use ($id){
            $query->where('id', $id)
                ->orWhere('parent_id', $id);
        })
        ->update(['value'=>$value]);


        $getLocale = DB::table('ltm_translations')->where('parent_id','<>',0)->select('locale')->distinct()->get(); //lấy được locale

        foreach ($getLocale as $getLoc) {

            $getTranslationsKey = Translation::where('parent_id', '<>', 0)->where('locale', $getLoc->locale)->get();
            $arrar_start = "<?php return[";
            $arrar_body = '';
            foreach ($getTranslationsKey as $getTran) {
                $arrar_body = $arrar_body . "'" . $getTran->value . "'" . "=>'" . $getTran->translation . "',";
            }
            $arrar_end = "];";
            $arra_lang = $arrar_start . $arrar_body . $arrar_end;
            $myfile = fopen(resource_path('lang/' . $getTran->locale . '/front.php'), 'w');
            fwrite($myfile, $arra_lang);
        }

    }

    public function search(){
        return view('search');
    }

    public function postSearch(Request $request){
        $key=$request->input('locale');
        $translations = Translation::where('value', 'LIKE', "%{$key}%")->where('parent_id','<>',0)->get();
        return view('search')->with('translations', $translations);
    }

    public function postSearchKey(Request $request){
        $key=$request->input('locale');
        $translations = Translation::where('value', 'LIKE', "%{$key}%")->where('parent_id',0)->get();
        return view('keyword_manage')->with('translations', $translations);
    }

    public function fetch(Request $request){
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('ltm_translations')
                ->select('value')
                ->distinct()
                ->where('value', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get();
            if(count($data)>0){
                $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
                foreach($data as $row)
                {
                    $output .= '
       <li><a href="#">'.$row->value.'</a></li>
       ';
                }
                $output .= '</ul>';
                echo $output;
            }else{
                echo $output='<ul class="dropdown-menu" style="display:block; position:relative"><span style="color: red; padding: 10px">Không tìm thấy dữ liệu.</span></ul>';
            }

        }
    }

    public function keyword_manage(){
        $translations = Translation::where('parent_id',0 )->get();
        return view('keyword_manage')->with('translations', $translations);
    }

    public function addKeyword(Request $request)
    {
        $value = $request->keyword;
        $tran = new Translation();
        $tran->status = 1;
        $tran->value = $value;
        $tran->translation = '';
        $tran->save();

        $getLocale = DB::table('ltm_translations')->where('parent_id','<>',0)->select('locale')->distinct()->get(); //lấy được locale



        foreach ($getLocale as $getLoc){
            $tran1 = new Translation();
            $tran1->status = 1;
            $tran1->value = $value;
            $tran1->locale=$getLoc->locale;
            $tran1->translation = '';
            $tran1->parent_id = $tran->id;
            $tran1->save();

            $getTranslationsKey=Translation::where('parent_id','<>',0)->where('locale', $getLoc->locale)->get();
            $arrar_start = "<?php return[";
            $arrar_body = '';
            foreach ($getTranslationsKey as $getTran){
                $arrar_body = $arrar_body . "'" . $getTran->value . "'" . "=>'".$getTran->translation."',";
            }
            $arrar_end = "];";
            $arra_lang = $arrar_start . $arrar_body . $arrar_end;
            $myfile = fopen(resource_path('lang/'.$getTran->locale.'/front.php'), 'w');
            fwrite($myfile, $arra_lang);
        }
        return redirect()->back();

    }

}
