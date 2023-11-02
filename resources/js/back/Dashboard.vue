<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'

const token = useLocalStorage('token')
const users = ref({})
const activeUsers = ref({})
const deactiveUsers = ref({})
const documents = ref({})
const departments = ref({})
   onMounted(()=>{
    axios.get("api/all-user")
    .then(res=>{
        users.value = res.data
    }).catch(err=>{
        console.log(err)
    })
    

    axios.get("api/all-document")
    .then(res=>{
        documents.value = res.data
    }).catch(err=>{
        console.log(err)
    })

    axios.get("api/all-active-user")
    .then(res=>{
        activeUsers.value = res.data
    }).catch(err=>{
        console.log(err)
    }) 

    axios.get("api/all-deactive-user")
    .then(res=>{
        deactiveUsers.value = res.data
    }).catch(err=>{
        console.log(err)
    }) 
    
    axios.defaults.headers.common['Authorization'] = token.value;
	axios.get('api/departments')
		.then(res => {
			departments.value = res.data
		}).catch(err => {
			console.log(err)
		})

   })
</script>
<template>
    <div class="page-content">

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-deepblue">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white">{{users}}</h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-group fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="progress my-2 bg-white-transparent" style="height:4px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Total Users</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ohhappiness">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white">{{activeUsers}}</h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-group fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="progress my-2 bg-white-transparent" style="height:4px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Total Active Users</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ibiza">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white">{{deactiveUsers}}</h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-group fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="progress my-2 bg-white-transparent" style="height:4px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Total Deactive Users</p>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-moonlit">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white">{{documents}}</h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-envelope fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="progress my-2 bg-white-transparent" style="height:4px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Total Documents</p>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->

            

            

                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0">Available Programs</h5>
                            </div>
                           
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Department name</th>
                                        <th>College</th>
                                        <th>Admission Type</th>
                                        <th>Study Level</th>
                                        <th>Duration</th>
                                        <th>CreatedAt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" v-for="(dept, index) in departments" :key="dept.index">
											<td>{{ index + 1 }}</td>
											<td>{{ dept.name }}</td>
											<td>{{ dept.college.name }}</td>
											<td>{{ dept.admission.name }}</td>
											<td>{{ dept.study.name }}</td>
											<td>{{ dept.duration }}</td>
											<td>{{ format(new Date(dept.created_at), 'MMMM do, yyyy') }}</td>
                                                            
                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

</template>
