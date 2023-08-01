<script setup>
import { RouterLink } from 'vue-router';


</script>


<template>
     <div class="sidebar-wrapper" data-simplebar="true">
             <div class="sidebar-header">
                <div>
                    <img :src="'backend/assets/images/logo-icon.png'" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h5 class="text-primary"> <span>Wollo University</span> </h5>
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
                <li class="menu-label">User Management</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fadeIn animated bx bx-user"></i>
                        </div>
                        <div class="menu-title">User Management</div>
                    </a>
                    <ul>
                        <li> <RouterLink :to="{name : 'User'}" ><i class="bx bx-right-arrow-alt"></i>User</RouterLink>
                        </li>
                        <li> <RouterLink :to="{name : 'Role'}" ><i class="bx bx-right-arrow-alt"></i>Role</RouterLink>
                        </li>
                        
                                           
                    </ul>
                </li>
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
                </li>
            
                
             
            </ul>
            <!--end navigation-->
        </div>
</template>

