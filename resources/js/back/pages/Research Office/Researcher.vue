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
					loadData('api/researchers');
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


onMounted(() => {
	loadData('api/researchers');
})




</script>
<template>
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
			<div class="breadcrumb-title pe-3">Researcher</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							List of Researchers
						</li>

					</ol>
				</nav>
			</div>

		</div>
		

		<div class="card border-top border-0 border-4 border-primary">
			<div class="card-body">
			

			<div class="table-responsive">
				
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
										<th>Status</th>
										<th>CreatedAt</th>
                                        <th>UpdatedAt</th>
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
											<td><span :class="user.status == 0 ? 'badge bg-danger' : 'badge bg-success'">{{
												user.status == 0 ? 'Deactive' : 'Active' }}</span></td>

											<td>{{ format(new Date(user.created_at), 'MMMM do, yyyy') }}</td>
											<td>{{ format(new Date(user.updated_at), 'MMMM do, yyyy') }}</td>

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
