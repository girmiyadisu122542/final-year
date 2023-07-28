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
const users = ref([]);
const isLoading = ref(false)
const isHide = ref(false)
const back = useBackendStore();
const isSubmit = ref(false)
const errors = ref({});
const errTime = ref(false);
const full_name = ref('');
const email = ref('');
const role = ref('');
const isEdit = ref(false)
const department = ref('');
const userId= ref('');

function loadData() {
	isLoading.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/users')
		.then(res => {
			users.value = res.data
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
			axios.get('api/delete-user/' + id)
				.then(() => {
					users.value = users.value.filter(user => {
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
	axios.post('api/add-user', {
		'full_name': full_name.value,
		'email': email.value,
		'role': role.value,
		'department': department.value,
	}).then(() => {
			isHide.value = false
			loadData();
			toast.success("User Successfully Added!!", {
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
					loadData();
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
	axios.post('api/update-user/'+userId.value, {
		'full_name': full_name.value,
		'email': email.value,
		'role': role.value,
		'department': department.value,
	}).then(() => {
			isHide.value = false
			isEdit.value = false
			resetState();
			loadData();
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
	loadData();
})

function resetState() {
	
     	   full_name.value ='',
			department.value = '',
			role.value = '',
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
		department.value = res.data[0].department_id,
		role.value = res.data[0].role_id,
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
			<div class="breadcrumb-title pe-3">User</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Users
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
					<h5 class="mb-0 text-primary">User Registration</h5>
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
					<div class="col-md-6">
						<label for="validationCustom03" class="form-label">Role</label>
						<select v-model="role" 
							class="form-select  border-primary text-primary">
							<i class="lni lni-funnel"></i>
							<option selected="" disabled="" value="">select role</option>
							<option v-for="role in back.roles" :value="role.id">{{ role.name }}</option>


						</select>
						<div class="text-danger" v-if="errors.role && errTime == true"> *{{ errors.role[0] }}</div>

					</div>
					<div class="col-md-6">
						<label for="validationCustom03" class="form-label">Department</label>
						<select v-model="department" id="inputState "
							class="form-select  border-primary text-primary">
							<i class="lni lni-funnel"></i>
							<option selected="" disabled="" value="">select department</option>
							<option v-for="dept in back.departments" :value="dept.id">{{ dept.name }}</option>

						</select>
						<div class="text-danger" v-if="errors.department && errTime == true">* {{ errors.department[0] }}</div>

					</div>

					<div class="col-12">
						<button :disabled="isSubmit" @click.prevent="isEdit?editUser():addUser()" class="btn btn-primary"
							type="submit"><i :class="isEdit?'fadeIn animated bx bx-edit-alt':'fadeIn animated bx bx-plus-circle'"></i><span v-if="isSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ isSubmit ? 'Loading...' : isEdit? 'Update'  :'Add' }}</button>
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
										<th>Full Name</th>
										<th>Email</th>
										<th>Role</th>
										<th>Department</th>
										<th>Code</th>
										<th>Status</th>
										<th>CreatedAt</th>
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
									<tbody v-if="users.length!=0">
										<tr role="row" v-for="(user, index) in users" :key="user.index">
											<td>{{ index + 1 }}</td>
											<td>{{ user.full_name.toUpperCase() }}</td>
											<td>{{ user.email }}</td>
											<td>{{ user.role.name }}</td>
											<td>{{ user.department.name }}</td>
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
