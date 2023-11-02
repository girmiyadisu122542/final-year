<script setup>
import { onMounted, ref,computed } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { format } from 'date-fns'
import { useLocalStorage } from '@vueuse/core';


	const token = useLocalStorage('token')
	const isLoading = ref(false)
	const announcements = ref([])
	const toast = useToast();
	
function loadDocument() {
	isLoading.value = true
	axios.get("api/get-front-announcements")
	.then(res=>{
		// console.log(res)
		announcements.value = res.data;
		setTimeout(()=>{
			isLoading.value = false
		},700)
	}).catch(err=>{
		toast.error("Somethig Went Wrong!!!", {
					timeout: 2000
				})
	}).finally(()=>{
		isLoading.value = false
	})
}


onMounted(()=>{
	loadDocument();
	
})
</script>

<template>
	<section class="page-header">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-8">
					<div class="title-block">
						<h1>List of Active Announcements</h1>
						<ul class="header-bradcrumb justify-content-center">
							<li>
								<RouterLink :to="{ name: 'Index' }">Home</RouterLink>
							</li>
							<li class="active" aria-current="page">
								Announcements
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="page-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-xl-12">
					<div class="row">
						 <template v-if="isLoading">
							<div class="spinner-border text-primary mt-4" role="status"> <span
												class="visually-hidden">Loading...</span>
							</div>

						 </template>
						 <template v-else>
							<template v-if="announcements.length!=0">
								<div class="col-xl-6" v-for="(announce,index) in announcements" :key="index.id" >
							<div class="blog-item mb-10">
								<h3 class="post-title">
										<a href="#">{{announce.title}}</a>
									</h3>
								<div class="blog-content">
									<div class="post-meta">
										<span class="post-author">by {{announce.user.full_name}}</span>
										<span class="post-date"><i class="fa fa-calendar-alt mr-2"></i>{{ format(new Date(announce.created_at),'MMMM do, yyyy') }}</span>
										
									</div>
									<p>Acadamic Year : {{ announce.year.name }}</p>
									<p class="text-danger"> Start Date: {{  format(new Date(announce.announcement_start_date),'MMMM do, yyyy')  }}</p>
									<p class="text-danger"> End Date: {{  format(new Date(announce.announcement_end_date),'MMMM do, yyyy')  }}</p>
									<p>
										{{announce.content}}
									</p>
								</div>
								<RouterLink :to="{name:'Proposal',params:{id:announce.id}}" v-if="token!= ''" class="btn btn-primary">Upload Proposal</RouterLink>
								<a href="/login"  v-else class="btn btn-primary">Sign in to upload  Proposal</a>
							</div>
						</div>
					</template>
					<template v-else>
						<img src="static_image/not_found.jpg" alt="">

					</template>
							
						 </template>
						

						
					</div>

					<!-- <nav class="blog-page-navigation">
						<ul class="pagination">
							<li class="pagination-arrow">
								<a href="#"><i class="fa fa-angle-double-left"></i></a>
							</li>
							<li><a href="#">1</a></li>
							<li><a class="active" href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li class="pagination-arrow">
								<a href="#"><i class="fa fa-angle-double-right"></i></a>
							</li>
						</ul>
					</nav> -->
				</div>
				
			</div>
		</div>
	</div>
</template>

