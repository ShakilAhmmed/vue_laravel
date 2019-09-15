<template>
	<div>
		<div class="row">
			<div class="col-sm-4">
					<input type="text" class="form-control" v-model="project_name_search" @keyup="getResults">
			</div>
			<div class="col-sm-4">
					<input type="text" class="form-control" v-model="project_description_search" @keyup="getResults">
			</div>
		</div>
		<table class="table" style="margin-top: 5%;">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Sl No</th>
		      <th scope="col">Project Name</th>
		      <th scope="col">Description</th>
		      <th scope="col">Image</th>
		      <th scope="col">Created At</th>
		      <th scope="col">Action</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr v-for="(projectlist_value,index) in projectlist.data" :key="index">
		     	  <th scope="row"></th>
			      <th scope="row" v-text="index+1"></th>
			      <td v-text="projectlist_value.project_name"></td>
			      <td v-text="projectlist_value.project_description"></td>
			      <td v-text="projectlist_value.created_at"></td>
			      <td @click="PreviewImage(projectlist_value.project_logo)" data-toggle="modal" data-target="#PreviewModal"><img :src="projectlist_value.project_logo" class="rounded-circle" style="height: 20%;"></td>
			      <td>
			      	<button class="btn btn-danger" @click="Delete(projectlist_value.project_id,index)"><i class="fa fa-trash"></i></button>
			      	<button class="btn btn-info" data-toggle="modal" data-target="#EditModalLabel" @click="Edit(projectlist_value.project_id,projectlist_value)"><i class="fa fa-pen"></i></button>
			      </td>
		    </tr>
		  </tbody>
		</table>
		<button class="btn btn-primary float-right" @click.prevent="getPrev" v-if="project_name_search || project_description_search">Back To All</button>
		<pagination :data="projectlist" @pagination-change-page="getResults"></pagination>


		<div class="modal fade" id="EditModalLabel" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Edit Project</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		     <form>
		      <div class="modal-body">
		        <div class="form-group">
				    <label for="exampleInputPassword1">Project Name</label>
				    <input type="text" class="form-control"  v-model="EditProjectData.project_name" id="exampleInputPassword1" placeholder="Project Name">
				    <span class="text-danger" v-if="AllError.project_name" v-text="AllError.project_name[0]"></span>
				 </div>	
				 <div class="form-group">
				    <label for="exampleInputPassword2">Project Description</label>
				   <textarea class="form-control" v-model="EditProjectData.project_description"></textarea>
				   <span class="text-danger" v-if="AllError.project_description" v-text="AllError.project_description[0]"></span>
				 </div>	

		      </div>
		      <div class="modal-footer">
		        <button type="button" @click="ClearForm()" class="close btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button"  @click.prevent="EditProject()" class="btn btn-primary">Update changes</button>
		      </div>
		  	</form>
		      
		    </div>
		  </div>
		</div>
		

		<div class="modal fade" id="PreviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content" style="width: 66%;margin-left: 22%;">
		      <div class="modal-body">
		        <img :src="image_preview">
		      </div>
		    </div>
		  </div>
		</div>

		
	</div>
</template>
<script>
	export default{
		props:['projectlist'],
		data(){
			return{
				project_name_search:'',
				project_description_search:'',
				EditProjectData:{
						project_id:'',
						project_name:'',
						project_description:''
				},
				AllError:[],
				image_preview:''
			}
		},
		methods:{
			Delete:function(id,index){
				const _this=this;

				swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.value) {

				  	axios.delete(baseUrl+'project/'+id)
					.then((response)=>{
						if(response.data.status===200)
						{
							_this.projectlist.data.splice(index,1);
							    swal.fire(
							      'Deleted!',
							      'Your file has been deleted.',
							      'success'
							    )
						}
						if(response.data.status===400)
						{
							swal.fire("Opps","Something Went Wrong","warning");
						}
					})
					.catch((error)=>{
						console.log(error)
					})
				  }
				})

				
			},
			PreviewImage:function(image){
				this.image_preview=image;
			},
			getResults:function(page=1){
				const _this=this;
				let main_url='';

				main_url=baseUrl+'project?project_name_search='+_this.project_name_search+'&project_description_search='+_this.project_description_search+'&page='+page
				axios.get(main_url)
				.then(response => {
					this.projectlist=response.data
					console.log(response.data)
				});
			},
			getPrev:function(){
				this.project_name_search='';
				this.project_description_search='';
				axios.get(baseUrl+'project?page='+1)
				.then(response => {
					this.projectlist=response.data
					console.log(response.data)
				});
			},
			Edit:function(id,data){
				const _this=this;
				_this.EditProjectData.project_id=id
				_this.EditProjectData=data;
			},
			EditProject:function(event){
				const _this=this;
			    //Update Data
				axios.put(baseUrl+'project/'+_this.EditProjectData.project_id,_this.EditProjectData)
				.then((response)=>{
				    if(response.data.status===201)
					{
						// console.log(response.data.data);
						// console.log(_this.ProjectList);
						//_this.projectlist.data.push(response.data.data);
						//$("#EditModalLabel").modal('hide');
						$(".close").click()
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
			ClearForm:function(){
				const _this=this;
				_this.AllError=[];
				// _this.EditProjectData.project_id='';
				// _this.EditProjectData.project_name='';
				// _this.EditProjectData.project_description='';

			},
		},
		computed:{
			Search:function(){
				return this.projectlist.data.filter(search=>{
					return search.project_name.match(this.project_name_search)
				});
			}
		}
	}
</script>