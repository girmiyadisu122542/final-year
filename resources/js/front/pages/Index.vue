<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import format from 'date-fns/format';

const users = ref({})
const documents = ref({})
const departments = ref({})
const lateDocuments = ref({})
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
axios.get("api/all-programs")
    .then(res=>{
        departments.value = res.data
    }).catch(err=>{
        console.log(err)
    }) 
  })

  axios.get("api/latest-documents")
	.then(res=>{
		lateDocuments.value = res.data.data
	}).catch(err=>{
		console.log(err);
	})
</script>

<template>
   <!-- Banner Section Start -->
<section class="banner-style-4 banner-padding">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-12 col-xl-6 col-lg-6">
                <div class="banner-content ">
                    <h1>Download and Enhance Your Research and Projects</h1>
                    <p class="mb-40"> This system is a web based research and project repository for wollo university that stores different douments that are prepared by wollo univeristy community.</p>

                    <div class="btn-container">
                        <RouterLink :to="{name:'Document'}" href="#" class="btn btn-main rounded">Find Documents</RouterLink>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-6 col-lg-6">
                <div class="banner-img-round mt-5 mt-lg-0 ps-5">
                    <!-- <img src="landingpage/assets/images/banner/banner_img.png" alt="" class="img-fluid"> -->
                    <img src="static_image/lan.jpeg" alt="" class="img-fluid">
                </div>
            </div>
        </div> <!-- / .row -->
    </div> <!-- / .container -->
</section>
<!-- Banner Section End -->

<!-- Counter Section start -->
<section class="counter-section4">
    <div class="container">
        <div class="row justify-content-center" >
            <div class="col-xl-12 counter-inner">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="counter-item mb-5 mb-lg-0">
                            <div class="count">
                                <span class="counter h2">{{users}}</span><span>+</span>
                            </div>
                            <p>Users</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="counter-item mb-5 mb-lg-0">
                            <div class="count">
                                <span class="counter h2">{{ documents }}</span><span>+</span>
                            </div>
                            <p>Documents</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="counter-item mb-5 mb-lg-0">
                            <div class="count">
                                <span class="counter h2">{{departments}}</span><span>+</span>
                            </div>
                            <p>Programs</p>     
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="counter-item">
                            <div class="count">
                                <span class="counter h2">100</span><span>%</span>
                            </div>
                            <p>Satisfaction</p>     
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
</section>
<!-- COunter Section End -->




<!--  Course style 1 -->

<section class="course-wrapper section-padding ">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="section-heading mb-70 text-center">
                    <h2 class="font-lg">Latest Documents</h2>
                </div>
            </div>
        </div>

        <div class="row justify-content-lg-center">
            <div class="col-xl-4 col-lg-4 col-md-6" v-for="document in lateDocuments">
                <div class="course-grid bg-shadow tooltip-style">
                    <div class="course-header">
                        <div class="course-thumb" v-if="document.cover_page">
                            <img style="width: 300px;height: 300px;" :src="document.cover_page" alt="" class="img-fluid">
                            
                        </div>
                        <div class="course-thumb" v-else>
                            <img style="width: 300px;height: 300px;" src="document/no_image.jpg" alt="" class="img-fluid">
                            
                        </div>
                    </div>
    
                    <div class="course-content">
                        <div class="rating mb-10">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>

                            <span>3.9 </span>
                        </div>

                        <h3 class="course-title mb-20"> <a href="#">{{document.title}} </a> </h3>

                        <div class="course-footer mt-20 d-flex align-items-center justify-content-between ">
                            <span class="students"><i class="far fa-user-alt me-2"></i>{{document.user.full_name}}</span>
                            <span class="lessons"><i class="far fa-play-circle me-2"></i>{{ format(new Date(document.created_at),'MMMM do, yyyy') }}</span>
                        </div>
                    </div>
    
                    <div class="course-hover-content">
                        <h3 class="course-title mb-20 mt-30"> <a href="#">{{ document.title }} </a> </h3>
                        <div class="course-meta d-flex align-items-center mb-20">
                            <div class="author me-4" v-if="document.cover_page">
                                <img  :src="document.cover_page" alt="" class="img-fluid">
                                <a href="#">{{document.user.full_name}}</a>
                            </div>
                            <div class="author me-4" v-else>
                                <img  src="document/no_image.jpg" alt="" class="img-fluid">
                                <a href="#">{{document.user.full_name}}</a>
                            </div>
                        </div>
                        <p class="mb-20">{{ document.abstract.substring(0,80) }}...</p>
                        <RouterLink :to="{name:'DocumentDetail',params:{id:document.id}}" class="btn btn-grey btn-sm rounded">View Document <i class="fal fa-angle-right"></i></RouterLink>
                    </div>
                </div>
             </div>
            <!-- COURSE END -->

            
            <!-- COURSE END -->
        </div>
    </div>
</section>

<!--  Course style 1 End -->





</template>
