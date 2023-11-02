<script setup>
import {onMounted,ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useLocalStorage } from '@vueuse/core';
import Auth from '../../Auth'
import axios from 'axios';


const token = useLocalStorage('token')
const userData = ref('')
const user_role =ref('')
const user_dept = ref('')
userData.value = Auth.user


onMounted(()=>{
   let role_id = userData.value.role_id
   let dept_id = userData.value.department_id
axios.defaults.headers.common['Authorization'] = token.value
   axios.get('api/get-role/'+role_id)
   .then(res=>{
    user_role.value = res.data[0].name

   }).catch(err=>{
    console.log(err)
   })
   axios.get('api/get-department/'+dept_id)
   .then(res=>{
    user_dept.value = res.data[0].name
   }).catch(err=>{
    console.log(err)
   })
})

</script>


<template>
     <div class="sidebar-wrapper" data-simplebar="true">
             <div class="sidebar-header">
                <div>
                    <img :src="'static_image/wollo.jfif'" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h5 class="text-primary"> <span>Wollo University</span> </h5>
                    <h6 class="text-primary"><small><b><i>{{ user_dept }}</i></b></small></h6>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <router-link to="/">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </router-link>
                </li>
                <!-- <li  class="menu-title">User Management</li>
                <li>
                    <ul>
                        <li> <RouterLink :to="{name : 'User'}" ><i class="bx bx-right-arrow-alt"></i>User</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Role'}" ><i class="bx bx-right-arrow-alt"></i>Role</RouterLink>
                        </li>
                        
                                           
                    </ul>
                </li> -->
                <template v-if="userData.role_id == 1">
                    <li class="menu-label">Accadamics</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="lni lni-home"></i>
                        </div>
                        <div class="menu-title">Accadamics</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'College'}" ><i class="bx bx-right-arrow-alt"></i>College</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'AdmissionType'}" ><i class="bx bx-right-arrow-alt"></i>Admission Type</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'StudyLevel'}" ><i class="bx bx-right-arrow-alt"></i>Study Level</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Department'}" ><i class="bx bx-right-arrow-alt"></i>Department</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Year'}" ><i class="bx bx-right-arrow-alt"></i>Accadamic Year</RouterLink>
                        </li>

                        
                                           
                    </ul>
                    <li   class="menu-label">User Management</li>
                 <li >
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">User Management</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'Role'}" ><i class="bx bx-right-arrow-alt"></i>Role</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'User'}" ><i class="bx bx-right-arrow-alt"></i>User</RouterLink>
                        </li>
                        
                                           
                    </ul>
                </li>
                
                </li>
                </template>
               
            
               <template v-if="userData.role_id == 2">
                <li class="menu-label">Department Head</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Department Head</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'ProjectCoordinator'}" ><i class="bx bx-right-arrow-alt"></i>Project Coordinator</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Student'}" ><i class="bx bx-right-arrow-alt"></i>Students</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'ViewDocumentDeptHead'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                                           
                    </ul>
                </li>
               </template>
                <template v-if="userData.role_id == 17">
                    <li class="menu-label">Project Coordinator</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Project Coordinator</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'Advisor'}" ><i class="bx bx-right-arrow-alt"></i>Advisors</RouterLink>
                        </li>
                       
                        <li> <RouterLink :to="{name : 'ViewDocumentProjectCoordinator'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                        
                                           
                    </ul>
                </li>
                </template >
               <template v-if="userData.role_id == 15">
                <li class="menu-label">PostGraduate Coordinator</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">PostGraduate Coordinator</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'Advisor'}" ><i class="bx bx-right-arrow-alt"></i>Advisors</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Student'}" ><i class="bx bx-right-arrow-alt"></i>Students</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'ViewDocumentpostGraduateCoordinator'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                        
                                           
                    </ul>
                </li>
               </template>
                <template v-if="userData.role_id == 13">
                    <li class="menu-label">Advisor</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Advisor</div>
                    </a>
                    <ul> 
                     <li> <RouterLink :to="{name : 'ViewDocument'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                                                                 
                    </ul>
                </li>
                </template>
              
                <template v-if="userData.role_id == 10">
                    <li class="menu-label">Student</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Student</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'UploadDocument'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                        
                        
                                           
                    </ul>
                </li>
                </template>
            <template v-if="userData.role_id == 14">
                <li class="menu-label">Research Office</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Research Office</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'Researcher'}" ><i class="bx bx-right-arrow-alt"></i>Researchers</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'ViewDocumentResearchOffice'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Announcement'}" ><i class="bx bx-right-arrow-alt"></i>Announcements</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Proposal'}" ><i class="bx bx-right-arrow-alt"></i>Proposals</RouterLink>
                        </li>
        
                                           
                    </ul>
                </li>
            </template>
                
            <template v-if="userData.role_id == 12">
                <li class="menu-label">Researcher</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">Researcher</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'UploadDocumentResearcher'}" ><i class="bx bx-right-arrow-alt"></i>Documents</RouterLink>
                        </li>
                        
                        
                                           
                    </ul>
                </li>
            </template>
            </ul>
            <!--end navigation-->
        </div>
</template>

