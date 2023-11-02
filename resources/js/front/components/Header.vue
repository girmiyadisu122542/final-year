<script setup>
import { RouterLink } from 'vue-router';
import { useLocalStorage } from '@vueuse/core';
const token = useLocalStorage('token')


</script>

<template>
  <header class="header-style-1"> 
    <div class="header-topbar topbar-style-2">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-8 col-lg-6 col-md-12">
                   <div class="header-contact text-center text-lg-start d-none d-sm-block">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <span class="text-color me-2"><i class="fa fa-phone-alt"></i></span><a href="tel:+(354) 6800 37849"> +(033) 311 5204</a>
                            </li>

                            <li class="list-inline-item">
                                <span class="text-color me-2"><i class="fa fa-envelope"></i></span><a href="malito:hello@edumel.com"> info@wu.edu.et</a>
                            </li>
                        </ul>
                   </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-12">
                   <div class="d-sm-flex justify-content-center justify-content-lg-end">
                        <div class="header-socials text-center text-lg-end">
                            <ul class="list-inline">
                                <li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li class="list-inline-item"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                <li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                                <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>

                        <div class="header-btn text-center text-lg-end">
                           <a v-if="token== ''" href="/login"> <i class="fa fa-user-alt"></i> Login/Register</a>
                           <a v-else href="/admin"> <i class="fa fa-user-alt"></i> Dashboard</a>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-navbar navbar-sticky">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="site-logo">
                    <RouterLink  to="/">
                        <img src="static_image/title.jfif" alt="" width="50" height="50" id="logo_image" class="img-fluid" />
                    </RouterLink>
                </div>

                <div class="offcanvas-icon d-block d-lg-none">
                    <a href="#" class="nav-toggler"><i class="fal fa-bars"></i></a> 
                </div>

                <nav class="site-navbar ms-auto">

                    <ul class="primary-menu">
                        <li class="current">
                            <RouterLink :to="{name:'Index'}">Home</RouterLink>
                            
                        </li>
                        <li class="current">
                            <RouterLink :to="{name:'Document'}">Documents</RouterLink>
                            
                        </li>
                        <li class="current">
                            <RouterLink :to="{name:'Announcement'}">Call For Research</RouterLink>
                            
                        </li>
                        <li class="current">
                            <RouterLink :to="{name:'ProposalStatus'}">Proposal Status</RouterLink>
                            
                        </li>
                        <li><RouterLink :to="{name:'About'}">About</RouterLink></li>

                        <li>
                            <RouterLink :to="{name : 'Contact'}">Contact</RouterLink>
                        </li>
                    </ul>

                    <a href="#" class="nav-close"><i class="fal fa-times"></i></a>
                </nav>
            </div>
        </div>
    </div>
</header>
</template>
<style>

#logo_image{
   background-color: green;
  
}
</style>