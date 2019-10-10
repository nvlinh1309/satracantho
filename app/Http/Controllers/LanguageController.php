<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function update($locale)
    {
        $translations = Translation::where('locale', $locale)->get();
        return view('update')->with('translations', $translations);
    }

    public function postInsert(Request $request)
    {
        $locale=$request->input('locale');

        include(resource_path('lang/lang_template.php'));

        $arrar_start = "<?php return[";
        $arrar_body = '';
        foreach ($arrays as $arr) {
            $tran = new Translation();
            $tran->status = 1;
            $tran->locale = $locale;;
            $tran->value = $arr;
            $tran->translation = '';
            $tran->save();
            $arrar_body = $arrar_body . "'" . $arr . "'" . "=>'',";
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
        $value = addslashes($request->value);

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
}
