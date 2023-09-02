<script setup>
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useRoute } from 'vue-router';
const toast = useToast();
const token = useLocalStorage('token')
const comments = ref([]);
const isLoading = ref(false)
const isHide = ref(false)
const isEdit = ref(false)
const isSubmit = ref(false)
const errors = ref({});
const errTime = ref(false);
const attached_file = ref(null);
const comment= ref('');
const document_id =ref('');
const route = useRoute();
const isUser = ref(false);
const comment_id= ref('')
function loadData() {
	let id = route.params.id;
	isLoading.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/view-document-comment/'+id)
		.then(res => {
			comments.value = res.data
			setTimeout(() => {
				isLoading.value = false
			}, 500);

			//console.log(res.data)
		}).catch(err => {
			toast.error("Something Went Wrong!!", {
						timeout: 2000
					})
		}).finally(() => {
			isLoading.value = false
		})
}


onMounted(() => {
	
	loadData();

})

function resetState() {
	
     	  
     	   comment.value = '',
		   attached_file.value = null,
		   document_id.value ='',
			comment_id.value = ''
		}
		
watch(isHide,(newValue)=>{
			if(newValue===false){
				resetState();
				isEdit.value = false,
				document_id.value ='',
				isUser.value = false
	}
})



function showData(id){
	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/show-comment/'+id)
	.then(res=>{
		isHide.value = true
		isEdit.value = true,
		comment_id.value = id;
		//console.log(res.data[0].full_name)
		attached_file.value = res.data[0].attached_file,
		comment.value = res.data[0].comment,
		document_id.value = res.data[0].document_id
		
	
	}).catch(err=>{
		     toast.error("Somethig Went Wrong!!!", {
					timeout: 2000
				})
	})
}


</script>
<template>
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
			<div class="breadcrumb-title pe-3">Comments</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Document Comments
						</li>

					</ol>
				</nav>
			</div>

		</div>
		<!--end breadcrumb-->
		<div v-if="isHide" class="card border-top border-0 border-4 border-primary">
			<div class="card-body p-5">
				<button style="float: right;" @click="isHide = false" type="button"
					class="btn btn-outline-danger radius-30"><i class="fadeIn animated bx bx-hide"></i>Hide Form</button>
				<div class="card-title d-flex align-items-center">
					<div><i class="fadeIn animated bx bx-plus-circle me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">Comment on  Document </h5>
				</div>
				<hr>
				<form class="row g-3 " enctype="multipart/form-data">
					<div class="col-md-12">
						<label for="validationCustomUsername" class="form-label">Comment</label>
						<div class="input-group has-validation">
							<textarea :disabled="isUser" v-model="comment" class="form-control border-primary " cols="50" rows="10"></textarea> 
							
						</div>
						<div class="text-danger" v-if="errors.comment && errTime == true">* {{ errors.comment[0] }}</div>
					</div>
					<div class="col-md-6">
						<label for="validationCustom01" class="form-label">Attached File</label>
						<input :disabled="isUser"  @change="onDocumentSelected"  type="file" class="form-control border-primary" 
							placeholder="select document">
						<div class="text-danger" v-if="errors.attached_file && errTime == true">*{{ errors.attached_file[0] }} </div>
					</div>
						
													
					
				</form>
			</div>
		</div>

		<div v-if="isHide==false"   class="card border-top border-0 border-4 border-primary">
			<div class="card-body">
				<div class="table-responsive">
					<RouterLink :to="{name:'UploadDocument'}" style="float: right;" class="btn btn-outline-warning "
									type="button">
									<span><i class="fadeIn animated bx bx-arrow-back"></i>Back</span>
								</RouterLink>
					<!-- <div class="row">

						<div class="col-12 col-lg-8 col-xl-8 d-flex">

						</div>
						
						<div class="col-12 col-lg-4 col-xl-4 d-flex">
							
						</div>
					</div> -->
					<!-- <hr> -->
					<div class="row mb-3">
						<div class="col-12 col-lg-4 col-xl-4 d-flex">
							<select id="inputState " class="form-select  border-primary text-primary">
								<i class="lni lni-funnel"></i>
								<option selected>Filter By</option>
								<option>Name</option>
								<option>Age</option>
								<option>Email</option>

							</select>
						</div>
						<div class="col-12 col-lg-3 col-xl-3 d-flex">
							<select id="inputState" class="form-select  border-primary text-primary">
								<option value="" selected disabled>Per Page</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="30">30</option>

							</select>
						</div>
						<div class="col-12 col-lg-5 col-xl-5 d-flex position-relative">
							<input type="search" class="form-control ps-5  border-primary text-primary"
								placeholder="Search..."> <span
								class="position-absolute top-50 product-show translate-middle-y "><i
									class="bx bx-search"></i></span>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<table id="example2" class="table table-striped  dataTable text-primary">
								<thead>
									<tr role="row">
										<th>#</th>
										<th></th>
										<th>Title</th>
										<th>Comment</th>
										<th>User</th>
										<th>Attached File</th>
										<th>Created At</th>
										<th>Updated At</th>
										<th width="5%">Actions</th>
									</tr>
								</thead>
								<template v-if="isLoading">
									<tbody>

										<div class="spinner-border text-primary mt-4" role="status"> <span
												class="visually-hidden">Loading...</span>
										</div>


									</tbody>

								</template>
								<template v-else>
									<tbody v-if="comments.length!=0">
										<tr role="row" v-for="(comment, index) in comments" :key="comment.index">
											<td>{{ index + 1 }}</td>
											<td>{{ comment.document.cover_page }}</td>
											<td>{{ comment.document.title.toUpperCase() }}</td>
											<td>{{ comment.comment }}</td>
											<td>{{ comment.user.full_name  }} <br> <span><i><small class="text-info">{{comment.role.name}}</small></i></span></td>
											<td v-if="comment.attached_file != null">{{ comment.attached_file }}</td>
											<td v-else><span><i><small class="text-secondary">No file</small></i></span></td>
											<td>{{ format(new Date(comment.created_at), 'MMMM do, yyyy') }}</td>
											<td>{{ format(new Date(comment.updated_at), 'MMMM do, yyyy') }}</td>
								
											<td>
												<div class="btn-group" role="group"
													aria-label="Button group with nested dropdown">
													<button type="button" class="btn btn-primary">Actions</button>
													<div class="btn-group" role="group">
														<button id="btnGroupDrop1" type="button"
															class="btn btn-white dropdown-toggle dropdown-toggle-nocaret px-1"
															data-bs-toggle="dropdown" aria-expanded="false">
															<i class="bx bx-chevron-down"></i>
														</button>
														<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
													
															<li><button @click.prevent="showData(comment.id); isUser = true" class="btn btn-outline-info dropdown-item "><i class="lni lni-eye"></i>View</button>
															</li>
															
														
															
														</ul>
													</div>
												</div>
											</td>

										</tr>
									</tbody>
									<tbody v-else>
										<div valign="top" colspan="6" class=" text-center dataTables_empty mt-4 "
											style="width: 250%;font-size: large;">

											<span class="badge bg-light text-primary">No Records Found!</span>
										</div>
									</tbody>

								</template>
							</table>
						</div>
					</div>
					<div class="row mb-0">
						<div class="col-sm-12 col-md-8">
							<div class="dataTables_info">
								Showing 1 to 10 of 57 entries
							</div>
						</div>
						<div class="col-sm-12 col-md-4">
							<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
								<ul class="pagination">
									<li class="paginate_button page-item previous disabled" id="example2_previous">
										<a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
											<i class="fadeIn animated bx bx-chevrons-left"></i> </a>
									</li>
									<li class="paginate_button page-item active">
										<a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">
											<i class="fadeIn animated bx bx-chevron-left"></i>
										</a>
									</li>
									<li class="paginate_button page-item">
										<a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">
											1
										</a>
									</li>
									<li class="paginate_button page-item">
										<a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">
											<i class="fadeIn animated bx bx-chevron-right"></i>
										</a>
									</li>
									<li class="paginate_button page-item next" id="example2_next">
										<a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">
											<i class="fadeIn animated bx bx-chevrons-right"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div></template>
