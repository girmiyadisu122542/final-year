<script setup>
import axios from 'axios';
import { ref, onMounted, watch, reactive } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useBackendStore } from '../../../stores/useBackendStore'

const toast = useToast();
const token = useLocalStorage('token')
const documents = ref([]);
const isLoading = ref(false)
const isHide = ref(false)
const isEdit = ref(false)
const isSubmit = ref(false)
const errors = ref({});
const errTime = ref(false);
const path = ref(null);
const author = ref('');
const cover_page = ref(null)
const abstract= ref('');
const size = ref('');
const title = ref('');
const page_number= ref('');
const accadamic_year = ref('');
const userId =ref('');
const back = useBackendStore();
function loadData() {
	isLoading.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/get-document-researcher')
		.then(res => {
			documents.value = res.data
			setTimeout(() => {
				isLoading.value = false
			}, 500);

			//console.log(res.data)
		}).catch(err => {
			console.log(err.data.response)
		}).finally(() => {
			isLoading.value = false
		})
}
function deleteData(id) {

	Swal.fire({
		title: 'Are you sure?',
		text: "You Want to Delete it!!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes'
	}).then((result) => {
		if (result.isConfirmed) {

			axios.defaults.headers.common['Authorization'] = token.value;
			axios.get('api/delete-document/' + id)
				.then(() => {
					documents.value = documents.value.filter(document => {
						return document.id != id;
					})
					toast.info("Data Successfully Deleted", {
						timeout: 2000
					})
				}).catch(err => {
					toast.error("You cann't delete this data!", {
						timeout: 2000
					})
				})
		}
	})


}
function addData() {
	if (isSubmit.value == true) return;
	isSubmit.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	let formdata = new FormData();
	formdata.append("document",path.value);
	formdata.append("author",author.value);
	formdata.append("cover_page",cover_page.value);
	formdata.append("abstract",abstract.value);
	formdata.append("size",size.value);
	formdata.append("title",title.value);
	formdata.append("page_number",page_number.value);
	formdata.append("accadamic_year",accadamic_year.value);

	axios.post('api/upload-document-researcher',formdata, {
		headers: {
               'Content-Type': 'multipart/form-data',
             },
	}).then(() => {
			isHide.value = false
			loadData();
			toast.success("Document uploaded successfully!!", {
				timeout: 2000
			})
		}).catch(err => {
			errTime.value = true
			if (err.response.status === 422) {
				errors.value = err.response.data.errors;
			} else {
				toast.error("Something Went Wrong!!!", {
					timeout: 2000
				})
			}

		}).finally(() => {
			setTimeout(() => {
				isSubmit.value = false
				resetState();
			}, 700);
			setTimeout(() => {
				errTime.value = false
				errors.value = {}
			}, 3000)
		})
}

 
function onDocumentSelected(e){
path.value = e.target.files[0];
size.value = e.target.files[0].size
}
function onCoverPageSelected(e){
	cover_page.value = e.target.files[0];
}

function editData(){
	if (isSubmit.value == true) return;
	axios.defaults.headers.common['Authorization'] = token.value
	isSubmit.value = true
	let formdata = new FormData();
	formdata.append("document",path.value);
	formdata.append("author",author.value);
	formdata.append("cover_page",cover_page.value);
	formdata.append("abstract",abstract.value);
	formdata.append("size",size.value);
	formdata.append("title",title.value);
	formdata.append("page_number",page_number.value);
	formdata.append("accadamic_year",accadamic_year.value);
	axios.post('api/update-document-researcher/'+userId.value,formdata, {
		headers: {
               'Content-Type': 'multipart/form-data',
             },
		}).then(() => {
			isHide.value = false
			isEdit.value = false
			resetState();
			loadData();
			toast.success("Data Successfully Updated!!", {
				timeout: 2000
			})
		}).catch(err => {
			errTime.value = true
			if (err.response.status === 422) {
				errors.value = err.response.data.errors;
			} else {
				toast.error("Somethig Went Wrong!!!", {
					timeout: 2000
				})
			}

		}).finally(() => {
			setTimeout(() => {
				isSubmit.value = false
			}, 700);
			setTimeout(() => {
				errTime.value = false
				errors.value = {}
			}, 3000)
		})
}

onMounted(() => {
	loadData();
	
})

function resetState() {
	
     	   path.value =null,
		   author.value = ''
     	   cover_page.value =null,
     	   abstract.value ='',
		   size.value = ''
		   title.value = ''
     	   page_number.value ='',
		   accadamic_year.value = ''
			
		}
		
		watch(isHide,(newValue)=>{
			if(newValue===false){
				resetState();
				isEdit.value = false
	}
})

function showData(id){

	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/show-document/'+id)
	.then(res=>{
		isHide.value = true
		isEdit.value = true
		userId.value = id
		//console.log(res.data[0].full_name)
		path.value = res.data[0].path,
		author.value = res.data[0].author,
		cover_page.value = res.data[0].cover_page,
		abstract.value = res.data[0].abstract,
		size.value = res.data[0].size,
		title.value = res.data[0].title,
		page_number.value = res.data[0].page_number,
		accadamic_year.value = res.data[0].year_id
	
	}).catch(err=>{
		console.log(err)
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
			<div class="breadcrumb-title pe-3">Documents</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Documents
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
					<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
					</div>
					<h5 class="mb-0 text-primary">Document Upload </h5>
				</div>
				<hr>
				<form class="row g-3 " enctype="multipart/form-data">
					<div class="col-md-6">
						<label for="validationCustom01" class="form-label">Document</label>
						<input @change="onDocumentSelected"  type="file" class="form-control border-primary" 
							placeholder="select document">
						<div class="text-danger" v-if="errors.document && errTime == true">*{{ errors.document[0] }} </div>
					</div>

					<div class="col-md-6">
						<label for="validationCustomUsername" class="form-label">Title</label>
						<div class="input-group has-validation"> 
							<input v-model="title" type="text" class="form-control border-primary"
								placeholder="Enter title ">

						</div>
						<div class="text-danger" v-if="errors.title && errTime == true">* {{ errors.title[0] }}</div>
					</div>
					<div class="col-md-6">
						<label for="validationCustomUsername" class="form-label">Author</label>
						<div class="input-group has-validation"> 
							<input v-model="author" type="text" class="form-control border-primary"
								placeholder="Author name">

						</div>
						<div class="text-danger" v-if="errors.author && errTime == true">* {{ errors.author[0] }}</div>
					</div>
					<div class="col-md-6">
						<label for="validationCustomUsername" class="form-label">Cover Page</label>
						<div class="input-group has-validation"> 
							<input @change="onCoverPageSelected"  type="file" class="form-control border-primary"
								>

						</div>
						<div class="text-danger" v-if="errors.cover_page && errTime == true">* {{ errors.cover_page[0] }}</div>
					</div>
					
				
					<div class="col-md-12">
						<label for="validationCustomUsername" class="form-label">Abstract</label>
						<div class="input-group has-validation">
							<textarea v-model="abstract" class="form-control border-primary " cols="50" rows="5"></textarea> 
							
						</div>
						<div class="text-danger" v-if="errors.abstract && errTime == true">* {{ errors.abstract[0] }}</div>
					</div>
					<div class="col-md-6">
						<label for="validationCustomUsername" class="form-label">Page Number</label>
						<div class="input-group has-validation"> 
							<input v-model="page_number" type="number" class="form-control border-primary"
								placeholder="Enter page number">

						</div>
						<div class="text-danger" v-if="errors.page_number && errTime == true">* {{ errors.page_number[0] }}</div>
					</div>
					<div class="col-md-6" >
						<label for="validationCustom03" class="form-label">Accadamic Year</label>
						<select v-model="accadamic_year" id="inputState "
							class="form-select  border-primary text-primary">
							<i class="lni lni-funnel"></i>
							<option selected="" disabled="" value="">select year</option>
							<option v-for="year in back.years" :value="year.id">{{ year.name }}</option>

						</select>
						<div class="text-danger" v-if="errors.accadamic_year && errTime == true">* {{ errors.accadamic_year[0] }}</div>
					</div>
				
					 <div class="col-12">
						<button :disabled="isSubmit" @click.prevent="isEdit?editData():addData()" class="btn btn-primary"
							type="submit"><i v-if="!isSubmit"  :class="isEdit?'fadeIn animated bx bx-edit-alt':'fadeIn animated bx bx-plus-circle'"></i><span v-if="isSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ isSubmit ? 'Loading...' : isEdit? 'Update'  :'Add' }}</button>
						<button class="btn  btn-danger" @click.prevent="resetState()"><i class="fadeIn animated bx bx-trash-alt"></i>Clear</button>	
					</div>
				</form>
			</div>
		</div>

		<div v-if="isHide==false" class="card border-top border-0 border-4 border-primary">
			<div class="card-body">
				<div class="table-responsive">
					<div class="row">

						<div class="col-12 col-lg-8 col-xl-8 d-flex">

						</div>
						<div class="col-12 col-lg-4 col-xl-4 d-flex">
							<div class="dt-buttons btn-group">
								<button class="btn btn-outline-success " type="button">
									<span>Grid</span>
								</button>
								<button class="btn btn-outline-info " type="button">
									<span>Excel</span>
								</button>
								<button class="btn btn-outline-danger " type="button">
									<span>Import</span>
								</button>
								<button v-if="!isHide" @click="isHide = true" class="btn btn-outline-warning "
									type="button">
									<span><i class="fadeIn animated bx bx-plus-circle"></i>Create</span>
								</button>
							</div>
						</div>
					</div>
					<hr>
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
										<th>Abstract</th>
										<th>Uploader</th>
										<th>Author</th>
										 <th>Year</th>
										<th>Updated At</th>
										<th>Status</th>
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
									<tbody v-if="documents.length!=0">
										<tr role="row" v-for="(document, index) in documents" :key="document.index">
											<td>{{ index + 1 }}</td>
											<td v-if="document.cover_page"><img :src="document.cover_page" style="height: 70px;width: 100px;" alt="No Image"></td>
											<td v-else><img style="height: 70px;width: 100px;" :src="'/document/no_image.jpg'" alt="no_image"></td>
											<!-- <td>{{ document.cover_page }}</td> -->
											<td>{{ document.title.toUpperCase() }}</td>
											<td>{{ document.abstract }}</td>
											<td>{{ document.user.full_name }}</td>
											<td>{{ document.author }}</td>
											<td>{{ document.year.name }}</td>
											<td>{{ format(new Date(document.updated_at), 'MMMM do, yyyy') }}</td>
											<td><span :class="document.status == 0 ? 'badge bg-danger' : document.status == 1? 'badge bg-success':'badge bg-danger'">{{
												document.status == 0 ? 'Pending...' :document.status == 1 ? 'Approved':'Rejected' }}</span></td>

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
															<li><button @click.prevent="showData(document.id)" class="btn btn-outline-info dropdown-item "><i
																		class="fadeIn animated bx bx-edit"></i>Edit</button>
															</li>
															<li>
																<RouterLink :to="{name:'ViewCommentStudent',params:{id:document.id}}" class="btn btn-outline-primary dropdown-item">
																	<i class="lni lni-eye"></i> View Comment
																</RouterLink>
															</li>
															<li ><button  @click.prevent=""
																	class="btn btn-outline-primary dropdown-item"><i class="lni lni-eye"></i>View</button>
															</li>
															<li v-if="document.status == 0"><button  @click.prevent="deleteData(document.id)"
																	class="btn btn-outline-danger dropdown-item"><i
																		class="fadeIn animated bx bx-trash"></i>Delete</button>
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
