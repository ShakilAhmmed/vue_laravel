<template>
	<div>
		<div class="row">
			<div class="col-sm-4">
					<input type="text" class="form-control" v-model="project_name_search">
			</div>
			<div class="col-sm-4">
					<input type="text" class="form-control" v-model="project_description_search">
			</div>
			<div class="col-sm-4">
				<button class="btn btn-success"  @click.prevent="getResults"><i class="fa fa-search"></i></button>
			</div>
		</div>
		<table class="table" style="margin-top: 5%;">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">Sl No</th>
		      <th scope="col">Project Name</th>
		      <th scope="col">Description</th>
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
			      <td>
			      	<button class="btn btn-danger" @click="Delete(projectlist_value.project_id,index)"><i class="fa fa-trash"></i></button>
			      	<button class="btn btn-info" @click="Edit(projectlist_value.project_id,index)"><i class="fa fa-pen"></i></button>
			      </td>
		    </tr>
		  </tbody>
		</table>
		<button class="btn btn-primary float-right" @click.prevent="getPrev" v-if="project_name_search || project_description_search">Back To All</button>
		<pagination :data="projectlist" @pagination-change-page="getResults"></pagination>
		
	</div>
</template>
<script>
	export default{
		props:['projectlist'],
		data(){
			return{
				project_name_search:'',
				project_description_search:''
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
			}
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