<script setup>
import axios from 'axios';
import { ref, onMounted, watch, computed } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';

const toast = useToast();
const token = useLocalStorage('token')
const documents = ref([]);
const isLoading = ref(false)
const isHide = ref(false)
const isEdit = ref(false)
const isSubmit = ref(false)
const errors = ref({});
const errTime = ref(false);
const attached_file = ref(null);
const comment= ref('');
const document_id =ref('');
const perPage = ref("");
const search_key = ref("");
function loadData(url, per_page = null) {
	      let cxr = "?";
            url.includes("?") ? (cxr = "&") : "";
            per_page == null ? (per_page = perPage.value) : "";
            url = url + cxr + "per_page=" + per_page;
           isLoading.value  = true;
            axios.get(url)
                .then((res) => {
                   documents.value = res.data;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    setTimeout(() => {
                       isLoading.value = false;
                    }, 500);
                });
}

watch(perPage,(newVal)=>{
	    loadData(
              documents.value.path + "?page=" + documents.value.current_page,
                newVal
            );
})

const filterByStatus = computed(()=>{
	if (search_key.value != "") {
                 isLoading.value = true
                setTimeout(()=>{
                    isLoading.value =false
                },500)
                return documents.value.data.filter(
                    (item) => (item.title.toLowerCase().includes(search_key.value.toLowerCase()))
                  
                );
            } else {
                isLoading.value = true
                setTimeout(()=>{
                 isLoading.value =false
                },500)
                return documents.value.data;
            }
})

function approveDocument(id) {

	Swal.fire({
		title: 'Are you sure?',
		text: "You Want to Change Document Status",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes'
	}).then((result) => {
		if (result.isConfirmed) {

			axios.defaults.headers.common['Authorization'] = token.value;
			axios.get('api/approve-document-researchOffice/' + id)
				.then(() => {
					loadData('api/get-researcher-document');
					toast.success("Document Status Changed Successfully!!", {
						timeout: 2000
					})
				}).catch(err => {
					toast.error("You cann't Approve this document!", {
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
	axios.post('api/comment-on-document-deptHead', {
		'comment': comment.value,
		'attached_file': attached_file.value,
		'document_id':document_id.value
			}).then(() => {
			isHide.value = false
		loadData('api/get-researcher-document');
			toast.success("Comment Added Successfully!!", {
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
attached_file.value = e.target.files[0];
}


onMounted(() => {
	loadData('api/get-researcher-document');
})

function resetState() {
	
     	  
     	   comment.value = '',
		   attached_file.value = null
			document_id.value = ''
		}
		
watch(isHide,(newValue)=>{
			if(newValue===false){
				resetState();
				isEdit.value = false,
				document_id.value =''
	}
})

function setValue(id){
	isHide.value = true;
	document_id.value = id;
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
							List of Uploaded Documents 
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
							<textarea v-model="comment" class="form-control border-primary " cols="50" rows="10"></textarea> 
							
						</div>
						<div class="text-danger" v-if="errors.comment && errTime == true">* {{ errors.comment[0] }}</div>
					</div>
					<div class="col-md-6">
						<label for="validationCustom01" class="form-label">Attach File</label>
						<input @change="onDocumentSelected"  type="file" class="form-control border-primary" 
							placeholder="select document">
						<div class="text-danger" v-if="errors.attached_file && errTime == true">*{{ errors.attached_file[0] }} </div>
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
							<div class="row g-3">
								<div class="col-12 col-lg-10 col-xl-10 d-flex">
	
								</div>
						
	
								
								</div>
								<hr>
									<div class="row mb-3">
										<div class="col-12 col-lg-6 col-xl-6 d-flex">
											<select id="inputState" v-model="perPage"
												class="form-select border-primary text-primary">
												<option value="" selected disabled>Per Page</option>
												<option value="10">10</option>
												<option value="20">20</option>
												<option value="30">30</option>
											</select>
										</div>
									   
	
										<div class="col-12 col-lg-6 col-xl-6 d-flex position-relative">
									   <input v-model="search_key" type="search" class="form-control ps-5 border-primary text-primary" placeholder="Search..." >
											<span class="position-absolute top-50 product-show translate-middle-y"><i
													class="bx bx-search"></i></span>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<table id="example2" class="table table-striped dataTable text-primary">
												<thead>
													<tr role="row">
														<th>#</th>
													<th></th>
													<th>Title</th>
													<th>Abstract</th>
													<th>Uploader</th>
													<th>Author</th>
													<th>Year</th>
													<th>Uploaded On</th>
													<th>Status</th>
													<th width="5%">Actions</th>
													   
													</tr>
												</thead>
												<template v-if="isLoading">
													<tbody>
														<div class="spinner-border text-primary mt-4" role="status">
															<span class="visually-hidden">Loading...</span>
														</div>
													</tbody>
												</template>
	
												<template v-else>
											   <tbody v-if="documents.data !=  null">
									<tr role="row" v-for="(document,index) in filterByStatus" :key="document.id">
									<th scope="row">{{ documents.from +index }} </th>
									<td v-if="document.cover_page"><img :src="document.cover_page" style="height: 70px;width: 100px;" alt="No Image"></td>
									<td v-else><img style="height: 70px;width: 100px;" :src="'/document/no_image.jpg'" alt="no_image"></td>
											<td> <RouterLink :to="{name :'DocumentDetail',params:{id:document.id}}"> {{ document.title.toUpperCase() }}</RouterLink> </td>
											<td v-if="document.advisor != null">{{ document.advisor.full_name }}</td>
											<td v-else>No Advisor</td>
											<td>{{ document.user.full_name }}</td>
											<td>{{ document.author }}</td>
											<td>{{ document.year.name }}</td>
											<td>{{ format(new Date(document.created_at), 'MMMM do, yyyy') }}</td>
											<td><span :class="document.status == 0 ? 'badge bg-danger' : document.status == 4? 'badge bg-success':'badge bg-danger'">{{
												document.status == 0 ? 'Pending...' :document.status == 4 ? 'Approved':'Rejected' }}</span></td>

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
															<li v-if="document.status <=5"><button @click.prevent="setValue(document.id)" class="btn btn-outline-info dropdown-item "><i
																		class="fadeIn animated bx bx-edit"></i>Add Comment</button>
															</li>
															<li v-else>
																<RouterLink :to="{name:'ViewCommentdeptHead',params:{id:document.id}}" class="btn btn-outline-primary dropdown-item">
																	<i class="lni lni-eye"></i> View Comment
																</RouterLink>
															</li>
															<li ><button  @click.prevent="deleteData(document.id)"
																	class="btn btn-outline-primary dropdown-item"><i class="lni lni-eye"></i>View</button>
															</li>
															<li v-if="document.status<=6" ><button  @click.prevent="approveDocument(document.id)"
																	class="btn btn-outline-danger dropdown-item"><i
																		:class="document.status == 1 ? 'fadeIn animated bx bx-plus' : 'fadeIn animated bx bx-minus'"></i>{{
																			document.status == 1 ? 'Approve' : 'Reject' }}</button>
															</li>
														
														</ul>
													</div>
												</div>
											</td>
														</tr>
													
													</tbody>
													<tbody v-else>
														<div valign="top" colspan="6" class="text-center dataTables_empty mt-4"
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
					Showing {{ documents.from }} to {{ documents.to }} of {{ documents.total }} entries
				</div>
			</div>
					<div class="col-sm-12 col-md-4">
					  <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
							<ul class="pagination">
							<li @click="documents.current_page ==1? '': loadData(documents.first_page_url)" :class="{disabled:documents.current_page == 1,'paginate_button page-item next': true,}" id="example2_previous">
									<a  aria-controls="example2" data-dt-idx="0" tabindex="0"
										 class="page-link"><i class="fadeIn animated bx bx-chevrons-left"></i></a>
							</li>
							<li @click="documents.prev_page_url !=null? loadData       (documents.prev_page_url): ''" :class="{disabled:documents.prev_page_url ==null,'paginate_button page-item previous': true,}" id="simpletable_next">
								   <a  aria-controls="example2" data-dt-idx="1" tabindex="0"
										class="page-link"><i class="fadeIn animated bx bx-chevron-left"></i></a>
							 </li>
							<li @click="loadData(documents.path +'?page=' +documents.current_page)" class="paginate_button page-item active">
								   <a aria-controls="example2" data-dt-idx="6" tabindex="0"
										class="page-link"> {{ documents.current_page }}</a>
							 </li>
							<li v-if="documents.last_page >documents.current_page +1"
							@click="loadData(documents.path +'?page=' +(documents.current_page +1))"  class="paginate_button page-item">                    
									<a  aria-controls="example2" data-dt-idx="6" tabindex="0"
										class="page-link"> {{documents.current_page + 1 }}
														</a>
							 </li>
							<li v-if="documents.last_page >documents.current_page +2" 
							@click="loadData(documents.path +'?page=' +(documents.current_page +2))" class="paginate_button page-item"><a  aria-controls="example2" data-dt-idx="6" tabindex="0"  class="page-link">{{ documents.current_page + 2 }} </a>
							</li>
							  <li @click="documents.next_page_url !=null ? loadData(documents.next_page_url): ''" :class="{disabled:documents.next_page_url ==null,
								'paginate_button page-item next': true,}" id="simpletable_next">
									<a  aria-controls="example2" data-dt-idx="6" tabindex="0"
										class="page-link"><i class="fadeIn animated bx bx-chevron-right"></i>
									</a>
							</li>
							<li @click="documents.current_page == documents.last_page? '': loadData(documents.last_page_url)" :class="{disabled:documents.current_page == documents.last_page,'paginate_button page-item next': true,}"  id="simpletable_next">
								<a  aria-controls="example2" data-dt-idx="7" tabindex="0"
									class="page-link"><i class="fadeIn animated bx bx-chevrons-right"></i>
														</a>
							 </li>
							<li @click="loadData(documents.path +'?page=' +documents.current_page)"  class="paginate_button page-item" id="simpletable_next">
								<a  aria-controls="example2" data-dt-idx="7" tabindex="0"
									class="page-link"><i class="fadeIn animated bx bx-refresh"></i>
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
