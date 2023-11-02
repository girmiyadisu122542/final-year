<script setup>
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useRoute } from 'vue-router';
const toast = useToast();
const token = useLocalStorage('token')
const documents = ref([]);
const isLoading = ref(false)
const route = useRoute();

function loadData() {
	let id = route.params.id;
    axios.defaults.headers.common['Authorization'] = token.value
	axios.get('api/show-document/'+id)
		.then(res => {
			documents.value = res.data
			setTimeout(() => {
				isLoading.value = false
			}, 500);

			//console.log(res.data)
		}).catch(err => {
			toast.error("Something Went Wrong!!", {
						timeout: 2000
					})
		}).finally(() => {
			isLoading.value = false
		})
}


onMounted(() => {
	
	loadData();

})



</script>
<template>
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
			<div class="breadcrumb-title pe-3">Document Detail</div>
			<div class="ps-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<li class="breadcrumb-item">
							<a href="javascript:;"><i class="bx bx-home-alt"></i></a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							document detail
						</li>

					</ol>
				</nav>
			</div>

		</div>
		<!--end breadcrumb-->
	   <div>
        <iframe src="document/attached_file/1693647087.pdf" width="400px" height="300" frameborder="0"></iframe>
       </div>

		
</div></template>
