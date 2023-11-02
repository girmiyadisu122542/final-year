<script setup>
import { onMounted, ref,computed } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { format } from 'date-fns'
 
 
	const isLoading = ref(false)
	const documents = ref([])
	const toast = useToast();
	const lateDocuments = ref({})
	const searchText = ref('')
function loadDocument() {
	isLoading.value = true
	axios.get("api/front-get-documents")
	.then(res=>{
		// console.log(res)
		documents.value = res.data;
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

const filterDocument = computed(() => {
    if(searchText.value == "") return documents.value
    
    //  return documents.value.filter(documnet =>{
	// 	 return documnet.title.toLowerCase().includes(searchText.value.toLowerCase() ||
	// 	 documnet.user.full_name.toLowerCase().includes(searchText.value.toLowerCase())
		 
	//  } 
	//  )
	return documents.value.filter((documnet) =>
		 
	(documnet.title.toLowerCase().includes(searchText.value.toLowerCase())) ||
	(documnet.user.full_name.toLowerCase().includes(searchText.value.toLowerCase())) 
	
	 )
	 
})
onMounted(()=>{
	loadDocument();
	axios.get("api/latest-documents")
	.then(res=>{
		lateDocuments.value = res.data.data
	}).catch(err=>{
		console.log(err);
	})
})
</script>

<template>
	<section class="page-header">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-8">
					<div class="title-block">
						<h1>List of Documents</h1>
						<ul class="header-bradcrumb justify-content-center">
							<li>
								<RouterLink :to="{ name: 'Index' }">Home</RouterLink>
							</li>
							<li class="active" aria-current="page">
								Documents
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
				<div class="col-lg-8 col-xl-8">
					<div class="row">
						 <template v-if="isLoading">
							<div class="spinner-border text-primary mt-4" role="status"> <span
												class="visually-hidden">Loading...</span>
							</div>

						 </template>
						 <template v-else>
							<template v-if="documents.length!=0">
								<div class="col-xl-6" v-for="(document,index) in filterDocument" :key="index.id" >
							<div class="blog-item mb-30">
								<div class="post-thumb" v-if="document.cover_page">
									<RouterLink :to="{name:'DocumentDetail',params:{id:document.id}}"><img style="width: 300px;height: 300px;"  :src="document.cover_page" alt=""
											 /></RouterLink>
								</div>
								<div class="post-thumb" v-else>
									<RouterLink :to="{name:'DocumentDetail',params:{id:document.id}}"><img style="width: 300px;height: 300px;" src="document/no_image.jpg" alt=""
											 /></RouterLink>
								</div>
								<div class="blog-content">
									<div class="post-meta">
										<span class="post-author">by {{document.user.full_name}}</span>
										<span class="post-date"><i class="fa fa-calendar-alt mr-2"></i>{{ format(new Date(document.created_at),'MMMM do, yyyy') }}</span>
										
									</div>
									<h3 class="post-title">
										<RouterLink  :to="{name:'DocumentDetail',params:{id:document.id}}">{{document.title}}</RouterLink>
									</h3>

									<p>
										{{document.abstract.substring(0,50)}}...
									</p>
								</div>
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
				<div class="col-lg-4 col-xl-4">
					<div class="blog-sidebar mt-5 mt-lg-0">
						<div class="widget widget-search">
							<form role="search" class="search-form">
								<input type="text" v-model="searchText" class="form-control" placeholder="Search" />
								<button type="submit" class="search-submit">
									<i class="fa fa-search"></i>
								</button>
							</form>
						</div>

						<div class="widget widget_latest_post">
							<h4 class="widget-title">Latest Documents</h4>
							<div class="recent-posts" v-if="lateDocuments.length != 0">
								<div class="single-latest-post" v-for="doc in lateDocuments" >
									<div class="widget-thumb">
										<RouterLink v-if="doc.cover_page" :to="{name:'DocumentDetail',params:{id:doc.id}}"><img :src="doc.cover_page" alt=""
												class="img-fluid" /></RouterLink>
										<RouterLink v-else :to="{name:'DocumentDetail',params:{id:doc.id}}"><img src="document/no_image.jpg" alt=""
												class="img-fluid" /></RouterLink>
									</div>
									<div class="widget-content">
										<h5>
											<RouterLink :to="{name:'DocumentDetail',params:{id:doc.id}}">{{doc.title}}</RouterLink>
										</h5>
										<span><i class="fa fa-calendar-times"></i>{{format(new Date(doc.created_at),'MMMM do, yyyy')}}</span>
									</div>
								</div>
								
							</div>
							<div class="recent-posts" v-else  >
								   <h5>
										No Latest Documents
									</h5>
							</div>
						</div>

						
					
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

