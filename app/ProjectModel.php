<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    protected $table="projects";
    protected $primaryKey="project_id";
    protected $fillable=['project_name','project_description'];

    public function validation_rules($id=0)
    {
    	return [
            'project_name'=>'required|max:50|unique:projects,project_name,'.$id.',project_id',
    		'project_description'=>'required'
    	];
    }
}
