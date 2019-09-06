<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    protected $table="projects";
    protected $primaryKey="project_id";
    protected $fillable=['project_name','project_description'];

    public function validation_rules()
    {
    	return [
    		'project_name'=>'required|unique:projects',
    		'project_description'=>'required'
    	];
    }
}
