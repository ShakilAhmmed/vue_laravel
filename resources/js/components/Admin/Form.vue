<template>
	<div>
		<button type="button" class="btn btn-primary float-right" style="margin-bottom: 2%;" data-toggle="modal" data-target="#exampleModal">
		 Add Project
		</button>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Add Project</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		     <form>
		      <div class="modal-body">
		        <div class="form-group">
				    <label for="exampleInputPassword1">Project Name</label>
				    <input type="text" class="form-control" v-model="ProjectData.project_name" id="exampleInputPassword1" placeholder="Project Name">
				    <span class="text-danger" v-if="AllError.project_name" v-text="AllError.project_name[0]"></span>
				 </div>	
				 <div class="form-group">
				    <label for="exampleInputPassword2">Project Description</label>
				   <textarea class="form-control" v-model="ProjectData.project_description"></textarea>
				   <span class="text-danger" v-if="AllError.project_description" v-text="AllError.project_description[0]"></span>
				 </div>	

		      </div>
		      <div class="modal-footer">
		        <button type="button" @click="ClearForm()" class="btn btn-secondary close" data-dismiss="modal">Close</button>
		        <button type="button"  @click.prevent="AddProject()" class="btn btn-primary">Save changes</button>
		      </div>
		  	</form>
		      
		    </div>
		  </div>
		</div>
		<TableView :projectlist="ProjectList"></TableView>


	</div>
</template>
<script>
import TableView from './Table';
export default{
	props:["projectlist"],
	components:{
		TableView
	},
	data(){
		return {
			ProjectData:{
				project_name:'',
				project_description:''
			},
			ProjectList:[],
			AllError:[]
		}
	},
	methods:{
		AddProject:function(){
			const _this=this;
			//Store Data
			axios.post(baseUrl+'project',_this.ProjectData)
			.then((response)=>{
				if(response.data.status===201)
				{
					// console.log(response.data.data);
					// console.log(_this.ProjectList);
					_this.ProjectList.data.push(response.data.data);
					$(".close").click();
					swal.fire("Success","Project Added Successfully","success");
					_this.ClearForm();
				}
				if(response.data.status===400)
				{
					_this.AllError=response.data.errors
					
				}
			})
			.catch((error)=>{
				console.log(error)
			})

		},
		ClearForm:function()
		{
			const _this=this;
			_this.AllError=[];
			_this.ProjectData.project_name='';
			_this.ProjectData.project_description='';

		},
	},
	created(){

		//Fetch Data
		axios.get(baseUrl+'project?page='+1)
		.then((response)=>{
			this.ProjectList=response.data
			console.log(response.data)
		})
		.catch((error)=>{
			console.log(error);
		})
	}

}
</script>