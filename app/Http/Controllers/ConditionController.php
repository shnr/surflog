<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Condition;
use App\Repositories\ConditionRepository;

use Intervention\Image\Facades\Image;

use DateTime;

// Photo trait
use App\Http\Traits\PhotoTrait;


class ConditionController extends Controller
{

    use PhotoTrait;


    /**
     * The condition repository instance.
     *
     * @var ConditionRepository
     */
    protected $condition;

    public function __construct(ConditionRepository $condition)
    {
        $this->middleware('auth');
        $this->condition = $condition;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $results = [];
        $conditions = $this->condition->forUser($request->user());

        // add photos
        foreach ($conditions as $key => $value) {
          $photos = $this->getPhotos($value->id);
          $value->images = $photos;
          $results[] = $value;
        }

        // get images
        return $results;
    }


    private function fileUploading($file, $photo_type, $condition_id) {

      // file upload sample.
      $image = Image::make(file_get_contents($file->getRealPath()));
      $orgfileName = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $fileName = mt_rand() . '.' . $extension;
      $date = new DateTime();
      $imageDir = '/uploads/' . $date->format('Y-m-d_His');    
      $imagePath = public_path() . $imageDir;
      $imageName = $imagePath . '/' . $fileName;
      if(file_exists($imagePath)) {
        $image->save($imageName);
      } else {
        if(mkdir($imagePath, 0777)){
          chmod($imagePath, 0777);
          $image->save($imageName);
        } else{
          // error
          // error handlingしなきゃ
          return false;
        }
      }

      // insert into Photo table 
      $photo = $this->addPhoto($fileName, $imageDir, $photo_type, $condition_id);

      return $photo;


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validations.
        $this->validate($request, [
            'condition' => 'required|max:255',
        ]);

        $surf_condition = [];
        $surf_condition = $this->getConditionParams($surf_condition, $request);

        $condition = $request->user()->conditions()->create($surf_condition);

        // File Upload
        $fileList = $request->file('file');
        if ($fileList != null) {
          // とりあえず。リサイズ範囲が決まったら作成。
          $photos = $this->updatePhotos($fileList, $condition->id);
        }

        return $condition;
    }


    /*
      Update phosots
    */
    private function updatePhotos($fileList, $condition_id) {
      $photo_type = 'original';
      $photos = [];

      if (count($fileList) > 0) {
        foreach ($fileList as $key => $file) {
          $photos[] = $this->fileUploading($file, $photo_type, $condition_id);
        }
      }
      return $photos;
    }


    /*
      Update all of these conditions
    */
    private function getConditionParams($surf_condition, $request){

      $surf_datetime = (isset($request->surf_datetime))? $request->surf_datetime: date('Y-m-d H:i:s');

      $surf_condition['surf_datetime'] = $surf_datetime;

      // エスケープは特に考慮しなくてもOK？
      if(isset($request->location)){
        $surf_condition['location'] = $request->location;
      }
      if(isset($request->location_lat)){
        $surf_condition['location_lat'] = $request->location_lat;
      }
      if(isset($request->location_lng)){
        $surf_condition['location_lng'] = $request->location_lng;
      }
      if(isset($request->condition)){
        $surf_condition['condition'] = $request->condition;
      }
      if(isset($request->swell_height) && $request->swell_height !== ''){
        $surf_condition['swell_height'] = $request->swell_height;
      }
      if(isset($request->swell_direction)){
        $surf_condition['swell_direction'] = $request->swell_direction;
      }
      if(isset($request->wind_strength) && $request->swell_height !== ''){
        $surf_condition['wind_size'] = $request->wind_strength;
      }
      if(isset($request->wind_direction)){
        $surf_condition['wind_direction'] = $request->wind_direction;
      }
      if(isset($request->quiver)){
        $surf_condition['surfboard'] = $request->quiver;
      }
      if(isset($request->wetsuits)){
        $surf_condition['wetsuits'] = $request->wetsuits;
      }
      if(isset($request->comment)){
        $surf_condition['comment'] = $request->comment;
      }


      return $surf_condition;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $condition = Condition::findOrFail($id);
        $photos = $this->getPhotos($id);
        $condition->images = $photos;

        return $condition;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $condition = Condition::findOrFail($id);

        $this->authorize('update', $condition);
       
        $surf_condition = $this->getConditionParams($condition, $request);
    
        $surf_condition->save();

        // File Upload
        $fileList = $request->file('file');
        if ($fileList != null) {
          // とりあえず。リサイズ範囲が決まったら作成。
          $photos = $this->updatePhotos($fileList, $condition->id);
        }

        return $surf_condition;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $condition = Condition::findOrFail($id);

        $this->authorize('destroy', $condition);

        // Delete The Condition...
        $condition->delete();

        return $condition;
    }
}
