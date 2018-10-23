<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;

use App\Photo;

trait PhotoTrait {


    /**
     * 
     * @param $condition_id
     * @return $photo object
     */
    public function getPhotos($condition_id, $photo_type) {

        // $photos = Photo::where('condition_id', $condition_id)->all();

        // single where state
        // $photos = DB::table('photos')->where('condition_id', '=', $condition_id)->get();

        // multiple where state
        $photos = DB::table('photos')
                ->where('condition_id', '=', $condition_id)
                ->where('type', '=', $photo_type)
                ->get();

        return $photos;
    }


    /**
     * 
     * @param $photo_name
     * @param $photo_path
     * @param $photo_type
     * @param $condition_id
     * @return $photo object
     */
    public function addPhoto($photo_name, $photo_path, $photo_type, $condition_id) {

        $photo = new Photo;
        $photo->name = $photo_name;
        $photo->type = $photo_type;
        $photo->path = $photo_path;
        $photo->condition_id = $condition_id;
        $photo->save();

        return $photo;

    }

}
