<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProjectModel;
use Validator;
use Image;
use Arr;
use Helper;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $where=[];
        // if($request->project_name_search)
        // {
        //     $where['project_name']=$request->project_name_search;
        // }
        // if($request->project_description_search)
        // {
        //      $where['project_description']=$request->project_description_search;
        // }
        // 
        $projects=ProjectModel::where(function($projects) use($request){
                if($request->project_name_search)
                {
                   $projects->where('project_name','LIKE','%'.$request->project_name_search.'%');
                }
                if($request->project_description_search)
                {
                   $projects->where('project_description','LIKE','%'.$request->project_description_search.'%');
                }
        })->paginate(5);
       

        return $projects;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project=new ProjectModel;
        $validation=Validator::make($request->all(),$project->validation_rules());
        if($validation->fails())
        {
            $response=[
                'errors'=>$validation->errors(),
                'status'=>400
            ];
        }
        else 
        {
            $requested_data=$request->all();
            if($request->project_logo)
            {
                $position=strpos($request->project_logo, ';');
                $sub=substr($request->project_logo, 0,$position);
                $ext=explode('/',$sub);
                $allowed=Helper::ImageExtension($ext[1]);
                if($allowed=="Allowed")
                {
                    $imageName = time().'.'.$ext[1];
                    $file_name='images/'.$imageName;
                    $img =Image::make($request->project_logo)->resize(300, 200);
                    $img->save($file_name);
                    Arr::set($requested_data,'project_logo',$file_name);
                    $project->fill($requested_data)->save();
                    $response=[
                        'data'=>$project,
                        'status'=>201
                    ];   
                }
                else
                {
                    $response=[
                        'errors'=>['project_logo_ext'=>$allowed],
                        'status'=>400
                    ];
                }

            }
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $project=ProjectModel::findOrFail($id);
        $validation=Validator::make($request->all(),$project->validation_rules($id));
        if($validation->fails())
        {
            $response=[
                'errors'=>$validation->errors(),
                'status'=>400
            ];
        }
        else 
        {
            $project->fill($request->all())->save();
            $response=[
                'status'=>201
            ];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete=ProjectModel::findOrFail($id)->delete();
        if($delete)
        {
            return response()->json(['status'=>200]);
        }
        else
        {
            return response()->json(['status'=>400]);
        }
    }
}
