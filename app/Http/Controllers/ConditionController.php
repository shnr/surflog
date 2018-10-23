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

        $surf_datetime = (isset($request->surf_datetime))? $request->surf_datetime: date('Y-m-d H:i:s');

        $condition = $request->user()->conditions()->create([
            'surf_datetime' => $surf_datetime,
            'location' => $request->location, 
            'location_lat' => $request->location_lat,
            'location_lng' => $request->location_lng,
            'condition' => $request->condition,
            'swell_height' => $request->swell_height,
            'swell_direction' => $request->swell_direction,
            'wind_size' => $request->wind_strength,
            'wind_direction' => $request->wind_direction,
            'wetsuits' => $request->wetsuits,
            'surfboard' => $request->quiver,
            'comment' => $request->comment,
            'memo' => $request->memo,
        ]);


        // FileList
        $fileList = $request->file('file');

        // とりあえず。リサイズ範囲が決まったら作成。
        $photo_type = 'original';
        $condition_id = $condition->id;
        $photos = [];

        if (count($fileList) > 0) {
          foreach ($fileList as $key => $file) {
            $photos[] = $this->fileUploading($file, $photo_type, $condition_id);
          }
        }

        return $condition;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Condition::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        
        // $condition->condition = $request->condition;

        foreach ($request as $key => $value) {
          $condition = $this->updateCondition($condition, $value, $request);
        }
    
        $condition->save();
        return $condition;
    }

    private function updateCondition($condition, $term, $request){
        if(isset($request->$term)) {
          $condition->$term = $request->term;  
        }
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
