<?php

namespace App\Http\Controllers;

use App\Models\BoardingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\String_;
use PHPUnit\Framework\Constraint\Count;

class BoardingImageController extends Controller
{
    public function deleteImage(Request $request)
    {
        
        $currImage = BoardingImage::where('id','=',$request->id)->first();
        $currImage_path = $currImage->image;
        $currImage_path = explode('/storage/', $currImage_path);

        if(Count($currImage_path) > 1){
            Storage::delete('public/'.$currImage_path[1]);  
        }
        $currImage -> delete();
        return redirect()->back();
    }
}
