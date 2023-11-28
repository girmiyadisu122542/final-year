<script setup>
import axios from 'axios';
import { ref, onMounted, watch, computed } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
const toast = useToast();
const token = useLocalStorage('token')
const users = ref([]);
const isLoading = ref(false)
const isHide = ref(false)
const isSubmit = ref(false)
const errors = ref({});
const errTime = ref(false);
const full_name = ref('');
const email = ref('');
const isEdit = ref(false)
const userId= ref('');
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
                   users.value = res.data;
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
              users.value.path + "?page=" + users.value.current_page,
                newVal
            );
})

const filterByStatus = computed(()=>{
	if (search_key.value != "") {
                 isLoading.value = true
                setTimeout(()=>{
                    isLoading.value =false
                },500)
                return users.value.data.filter(
                    (item) => (item.full_name.toLowerCase().includes(search_key.value.toLowerCase()))||
						(item.email.toLowerCase().includes(search_key.value.toLowerCase()))|| 
						(item.role.name.toLowerCase().includes(search_key.value.toLowerCase()))
                );
            } else {
                isLoading.value = true
                setTimeout(()=>{
                 isLoading.value =false
                },500)
                return users.value.data;
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
			axios.get('api/delete-user/' + id)
				.then(() => {
					users.value.data = users.value.data.filter(user => {
						return user.id != id;
					})
					toast.info("User Successfully Deleted", {
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
function addUser() {
	if (isSubmit.value == true) return;
	isSubmit.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	axios.post('api/add-advisor', {
		'full_name': full_name.value,
		'email': email.value,
	}).then(() => {
			isHide.value = false
			loadData('api/advisors');
			toast.success("Advisor Successfully Added!!", {
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
function activeUser(id) {
	Swal.fire({
		title: 'Are you sure?',
		text: "You Want to Change User Status!!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes'
	}).then((result) => {
		if (result.isConfirmed) {

			axios.defaults.headers.common['Authorization'] = token.value;
			axios.get('api/active-user/' + id)
				.then(() => {
					loadData('api/advisors');
					toast.success("User Status Changed!", {
						timeout: 2000
					})

				}).catch(err => {
					toast.error("You cann't change User Status", {
						timeout: 2000
					})
				})
		}
	})

}

function editUser(){
	if (isSubmit.value == true) return;
	axios.defaults.headers.common['Authorization'] = token.value
	isSubmit.value = true
	axios.post('api/update-advisor/'+userId.value, {
		'full_name': full_name.value,
		'email': email.value,
		}).then(() => {
			isHide.value = false
			isEdit.value = false
			resetState();
			loadData('api/advisors');
			toast.success("User Successfully Updated!!", {
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
	loadData('api/advisors');
})

function resetState() {
	
     	   full_name.value ='',
		   email.value = ''
			
		}
		
		watch(isHide,(newValue)=>{
			if(newValue===false){
				resetState();
				isEdit.value = false
	}
})

function showUser(id){

	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/get-user/'+id)
	.then(res=>{
		isHide.value = true
		isEdit.value = true
		userId.value = id
		//console.log(res.data[0].full_name)
		full_name.value = res.data[0].full_name,
		email.value = res.data[0].email
	
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
			<div class="breadcrumb-title pe-3">Advisor</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Advisors
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
					<h5 class="mb-0 text-primary">Advisor Registration</h5>
				</div>
				<hr>
				<form class="row g-3 ">
					<div class="col-md-6">
						<label for="validationCustom01" class="form-label">Full Name</label>
						<input v-model="full_name" type="text" class="form-control border-primary" id="validationCustom01"
							placeholder="Enter full name">
						<div class="text-danger" v-if="errors.full_name && errTime == true">*{{ errors.full_name[0] }} </div>
					</div>

					<div class="col-md-6">
						<label for="validationCustomUsername" class="form-label">Email</label>
						<div class="input-group has-validation"> <span class="input-group-text"
								id="inputGroupPrepend">@</span>
							<input v-model="email" type="email" class="form-control border-primary"
								placeholder="Enter User Email">

						</div>
						<div class="text-danger" v-if="errors.email && errTime == true">* {{ errors.email[0] }}</div>
					</div>
					 <div class="col-12">
						<button :disabled="isSubmit" @click.prevent="isEdit?editUser():addUser()" class="btn btn-primary"
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
										<th>Full Name</th>
										<th>Email</th>
										<th>Department</th>
										<th>Code</th>
										<th>Status</th>
										<th>Created Date</th>
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
                                           <tbody v-if="users.data !=  null">
                                            <tr role="row" v-for="(user,index) in filterByStatus" :key="user.id">
                                            <th scope="row">{{ users.from +index }} </th>
                                           <td>{{ user.full_name.toUpperCase() }}</td>
											<td>{{ user.email }}</td>
											<td v-if="user.department_id">{{ user.department.name }}</td>
											<td v-else><span class="badge bg-light text-dark">No</span></td>
											<td>{{ user.code }}</td>
											<td><span :class="user.status == 0 ? 'badge bg-danger' : 'badge bg-success'">{{
												user.status == 0 ? 'Deactive' : 'Active' }}</span></td>

											<td>{{ format(new Date(user.created_at), 'MMMM do, yyyy') }}</td>

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
															<li><button @click.prevent="showUser(user.id)" class="btn btn-outline-info dropdown-item "><i
																		class="fadeIn animated bx bx-edit"></i>Edit</button>
															</li>
															<li><button @click.prevent="deleteData(user.id)"
																	class="btn btn-outline-danger dropdown-item"><i
																		class="fadeIn animated bx bx-trash"></i>Delete</button>
															</li>
															<li><button @click.prevent="activeUser(user.id)"
																	class="btn btn-outline-primary dropdown-item"><i
																		:class="user.status == 0 ? 'fadeIn animated bx bx-user-plus' : 'fadeIn animated bx bx-user-minus'"></i>{{
																			user.status == 0 ? 'Active' : 'Deactive' }}</button>
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
                Showing {{ users.from }} to {{ users.to }} of {{ users.total }} entries
            </div>
        </div>
                <div class="col-sm-12 col-md-4">
                  <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        <ul class="pagination">
                        <li @click="users.current_page ==1? '': loadData(users.first_page_url)" :class="{disabled:users.current_page == 1,'paginate_button page-item next': true,}" id="example2_previous">
                                <a  aria-controls="example2" data-dt-idx="0" tabindex="0"
                                     class="page-link"><i class="fadeIn animated bx bx-chevrons-left"></i></a>
                        </li>
                        <li @click="users.prev_page_url !=null? loadData       (users.prev_page_url): ''" :class="{disabled:users.prev_page_url ==null,'paginate_button page-item previous': true,}" id="simpletable_next">
                               <a  aria-controls="example2" data-dt-idx="1" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-left"></i></a>
                         </li>
                        <li @click="loadData(users.path +'?page=' +users.current_page)" class="paginate_button page-item active">
                               <a aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{ users.current_page }}</a>
                         </li>
                        <li v-if="users.last_page >users.current_page +1"
                        @click="loadData(users.path +'?page=' +(users.current_page +1))"  class="paginate_button page-item">                    
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{users.current_page + 1 }}
                                                    </a>
                         </li>
                        <li v-if="users.last_page >users.current_page +2" 
                        @click="loadData(users.path +'?page=' +(users.current_page +2))" class="paginate_button page-item"><a  aria-controls="example2" data-dt-idx="6" tabindex="0"  class="page-link">{{ users.current_page + 2 }} </a>
                        </li>
                          <li @click="users.next_page_url !=null ? loadData(users.next_page_url): ''" :class="{disabled:users.next_page_url ==null,
                            'paginate_button page-item next': true,}" id="simpletable_next">
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-right"></i>
                                </a>
                        </li>
                        <li @click="users.current_page == users.last_page? '': loadData(users.last_page_url)" :class="{disabled:users.current_page == users.last_page,'paginate_button page-item next': true,}"  id="simpletable_next">
                            <a  aria-controls="example2" data-dt-idx="7" tabindex="0"
                                class="page-link"><i class="fadeIn animated bx bx-chevrons-right"></i>
                                                    </a>
                         </li>
                        <li @click="loadData(users.path +'?page=' +users.current_page)"  class="paginate_button page-item" id="simpletable_next">
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
