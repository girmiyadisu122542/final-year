<script setup>
import axios from 'axios';
import { ref, onMounted, watch, computed } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useBackendStore } from '../../../stores/useBackendStore'
    const toast = useToast();
    const token = useLocalStorage('token')
    const back = useBackendStore();
    const departments = ref([]);
    const isLoading = ref(false)
    const isHide = ref(false)
    const isSubmit = ref(false)
    const errors = ref({});
    const errTime = ref(false);
    const name = ref('');
    const college = ref('');
    const admssion_type = ref('');
	const studyLevel = ref('');
    const duration = ref('');
    const isEdit = ref(false)
    const dataId= ref('');
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
                   departments.value = res.data;
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
              departments.value.path + "?page=" + departments.value.current_page,
                newVal
            );
})

const filterByStatus = computed(()=>{
	if (search_key.value != "") {
                 isLoading.value = true
                setTimeout(()=>{
                    isLoading.value =false
                },500)
                return departments.value.data.filter(
                    (item) => (item.name.toLowerCase().includes(search_key.value.toLowerCase()))||
						(item.college.name.toLowerCase().includes(search_key.value.toLowerCase()))|| (item.admission.name.toLowerCase().includes(search_key.value.toLowerCase()))||
						(item.study.name.toLowerCase().includes(search_key.value.toLowerCase()))
                );
            } else {
                isLoading.value = true
                setTimeout(()=>{
                 isLoading.value =false
                },500)
                return departments.value.data;
            }
})
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
			axios.get('api/delete-department/' + id)
				.then(() => {
					departments.value.data = departments.value.data.filter(role => {
						return role.id != id;
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
	axios.post('api/add-department', {
		'name': name.value,
        'college':college.value,
        'admssion_type':admssion_type.value,
		'studyLevel':studyLevel.value,
        'duration':duration.value
		}).then(() => {
			isHide.value = false
			loadData('/api/get-departments');
			toast.success("Data Successfully Added!!", {
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
				resetState();
			}, 700);
			setTimeout(() => {
				errTime.value = false
				errors.value = {}
			}, 3000)
		})
}

function editUser(){
	if (isSubmit.value == true) return;
	axios.defaults.headers.common['Authorization'] = token.value
	isSubmit.value = true
	axios.post('api/update-department/'+dataId.value, {
		'name': name.value,
        'college':college.value,
        'admssion_type':admssion_type.value,
		'studyLevel':studyLevel.value,
        'duration':duration.value
	}).then(() => {
			isHide.value = false
			isEdit.value = false
			resetState();
			loadData('/api/get-departments');
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
	loadData('/api/get-departments');
})

function resetState() {
	
     	   name.value =''
            
        }
        
        watch(isHide,(newValue)=>{
            if(newValue===false){
                resetState();
                isEdit.value = false
	}
})

function showData(id){

	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/get-department/'+id)
	.then(res=>{
		isHide.value = true
		isEdit.value = true
		dataId.value = id
		name.value = res.data[0].name
		college.value =res.data[0].college_id
		admssion_type.value = res.data[0].admission_type_id
		studyLevel.value = res.data[0].study_level_id
		duration.value = res.data[0].duration
	
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
			<div class="breadcrumb-title pe-3">Departments</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Departments
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
					<div><i class="fadeIn animated bx bx-plus"></i>
					</div>
					<h5 class="mb-0 text-primary">Department Registration</h5>
				</div>
				<hr>
				<form class="row g-3 ">
					<div class="col-md-6">
						<label for="validationCustom01" class="form-label">Department Name</label>
						<input v-model="name" type="text" class="form-control border-primary" id="validationCustom01"
							placeholder="Enter Department name">
						<div class="text-danger" v-if="errors.name && errTime == true">*{{ errors.name[0] }} </div>
					</div>
                    <div class="col-md-6">
						<label for="validationCustom03" class="form-label">College</label>
						<select v-model="college" id="inputState "
							class="form-select  border-primary text-primary">
							<i class="lni lni-funnel"></i>
							<option selected="" disabled ="" value="">select college</option>
							<option v-for="college in back.colleges" :value="college.id">{{ college.name }}</option>


						</select>
						<div class="text-danger" v-if="errors.college && errTime == true"> *{{ errors.college[0] }}</div>

					</div>
                    <div class="col-md-6">
                        <label for="validationCustom01" class="form-label">Duration</label>
						<input v-model="duration" type="number" class="form-control border-primary " id="validationCustom01" min="1">
						<div class="text-danger" v-if="errors.duration && errTime == true">*{{ errors.duration[0] }} </div>
					</div>
					
                    <div class="col-md-6">
                        <label for="validationCustom03" class="form-label">Admission Type</label>
                        <select v-model="admssion_type" id="inputState "
                            class="form-select  border-primary text-primary">
                            <option selected="" disabled ="" value="">select admission type</option>
                            <option v-for="admssion in back.admissionTypes" :value="admssion.id">{{ admssion.name }}</option>


                        </select>
                        <div class="text-danger" v-if="errors.admssion_type && errTime == true"> *{{ errors.admssion_type[0] }}</div>

                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom03" class="form-label">Study Levels</label>
                        <select v-model="studyLevel" id="inputState "
                            class="form-select  border-primary text-primary">
                            <option selected="" disabled ="" value="">select study levels</option>
                            <option v-for="study in back.studyLevels" :value="study.id">{{ study.name }}</option>
                        </select>
                        <div class="text-danger" v-if="errors.study_level && errTime == true"> *{{ errors.study_level[0] }}</div>

                    </div>
                    
					<div class="col-12">
						<button :disabled="isSubmit" @click.prevent="isEdit?editUser():addData()" class="btn btn-primary"
							type="submit"><i :class="isEdit?'fadeIn animated bx bx-edit-alt':'fadeIn animated bx bx-plus-circle'"></i> <span v-if="isSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ isSubmit ?  ' Loading...' : isEdit? 'Update'  :'Add' }}</button>
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
							<div class="col-12 col-lg-2 col-xl-2 d-flex">
								<div class="dt-buttons btn-group">
									<button v-if="!isHide" @click="isHide = true" class="btn btn-outline-warning "
										type="button">
										<span><i class="fadeIn animated bx bx-plus-circle"></i>Create</span>
									</button>
								</div>
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
													<th>Department name</th>
													<th>College</th>
													<th>Admission Type</th>
													<th>Study Level</th>
													<th>Duration</th>
													<th>Created Date</th>
													<th>Updated Date</th>
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
                                           <tbody v-if="departments.data !=  null">
                                            <tr role="row" v-for="(dept,index) in filterByStatus" :key="dept.id">
                                            <th scope="row">{{ departments.from +index }} </th>
                                            <td>{{ dept.name }}</td>
											<td>{{ dept.college.name }}</td>
											<td>{{ dept.admission.name }}</td>
											<td>{{ dept.study.name }}</td>
											<td>{{ dept.duration }}</td>
                                            <td>{{ format(new Date(dept.created_at), 'MMMM do, yyyy') }}</td>
                                        <td>{{ format(new Date(dept.updated_at), 'MMMM do, yyyy') }}</td>

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
			<li><button @click.prevent="showData(dept.id)" class="btn btn-outline-info dropdown-item "><i
						class="fadeIn animated bx bx-edit"></i>Edit</button>
			</li>
			<li><button @click.prevent="deleteData(dept.id)"
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
                Showing {{ departments.from }} to {{ departments.to }} of {{ departments.total }} entries
            </div>
        </div>
                <div class="col-sm-12 col-md-4">
                  <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        <ul class="pagination">
                        <li @click="departments.current_page ==1? '': loadData(departments.first_page_url)" :class="{disabled:departments.current_page == 1,'paginate_button page-item next': true,}" id="example2_previous">
                                <a  aria-controls="example2" data-dt-idx="0" tabindex="0"
                                     class="page-link"><i class="fadeIn animated bx bx-chevrons-left"></i></a>
                        </li>
                        <li @click="departments.prev_page_url !=null? loadData       (departments.prev_page_url): ''" :class="{disabled:departments.prev_page_url ==null,'paginate_button page-item previous': true,}" id="simpletable_next">
                               <a  aria-controls="example2" data-dt-idx="1" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-left"></i></a>
                         </li>
                        <li @click="loadData(departments.path +'?page=' +departments.current_page)" class="paginate_button page-item active">
                               <a aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{ departments.current_page }}</a>
                         </li>
                        <li v-if="departments.last_page >departments.current_page +1"
                        @click="loadData(departments.path +'?page=' +(departments.current_page +1))"  class="paginate_button page-item">                    
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{departments.current_page + 1 }}
                                                    </a>
                         </li>
                        <li v-if="departments.last_page >departments.current_page +2" 
                        @click="loadData(departments.path +'?page=' +(departments.current_page +2))" class="paginate_button page-item"><a  aria-controls="example2" data-dt-idx="6" tabindex="0"  class="page-link">{{ departments.current_page + 2 }} </a>
                        </li>
                          <li @click="departments.next_page_url !=null ? loadData(departments.next_page_url): ''" :class="{disabled:departments.next_page_url ==null,
                            'paginate_button page-item next': true,}" id="simpletable_next">
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-right"></i>
                                </a>
                        </li>
                        <li @click="departments.current_page == departments.last_page? '': loadData(departments.last_page_url)" :class="{disabled:departments.current_page == departments.last_page,'paginate_button page-item next': true,}"  id="simpletable_next">
                            <a  aria-controls="example2" data-dt-idx="7" tabindex="0"
                                class="page-link"><i class="fadeIn animated bx bx-chevrons-right"></i>
                                                    </a>
                         </li>
                        <li @click="loadData(departments.path +'?page=' +departments.current_page)"  class="paginate_button page-item" id="simpletable_next">
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
