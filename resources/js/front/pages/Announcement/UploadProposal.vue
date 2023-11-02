<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { useRoute, useRouter } from 'vue-router';
import { useLocalStorage } from '@vueuse/core';



const isSubmit = ref(false)
const token = useLocalStorage('token')
const isLoading = ref(false)
const toast = useToast();
const file_attached = ref(null)
const route = useRoute();
const router = useRouter()
const errors = ref({})
function addData() {
	let announcement_id = route.params.id
	if (isSubmit.value == true) return;
	isSubmit.value = true
	axios.defaults.headers.common['Authorization'] = token.value
	let formdata = new FormData();
	formdata.append("file_attached", file_attached.value);
	formdata.append("announcement_id", announcement_id);
	axios.post('api/upload-proposal', formdata, {
		headers: {
			'Content-Type': 'multipart/form-data',
		},
	}
	).then(() => {
		toast.success("Proposal Uploaded Successfully!!!", {
				timeout: 2000
			})
		router.back();
	}).catch(err => {
		if (err.response.status === 422) {
			
			errors.value = err.response.data.errors;
		} else {
			toast.error("Something Went Wrong!!!", {
				timeout: 2000
			})
		}

	}).finally(() => {
		setTimeout(() => {
			isSubmit.value = false
		}, 700);

	})
}
function onDocumentSelected(e) {
	file_attached.value = e.target.files[0];
}
</script>

<template>
	<section class="page-header">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-8">
					<div class="title-block">
						<h1>Upload Proposal</h1>
						<ul class="header-bradcrumb justify-content-center">
							<li>
								<RouterLink :to="{ name: 'Index' }">Home</RouterLink>
							</li>
							<li class="active" aria-current="page">
								Proposal Upload
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
							<div class="row">

								<div class="col-lg-8">
									<div class="form-group">
										<input type="file" @change="onDocumentSelected" name="subject" id="subject"
											class="form-control" placeholder="Subject">
									</div>
									<div class="text-danger" v-if="errors.file_attached">*{{ errors.file_attached[0] }}
									</div>
								</div>

								<div class="col-lg-4">
									<div class="text-center">
										<!-- <button class="btn btn-main w-100 rounded" type="submit">Upload</button> -->
										<button :disabled="isSubmit" @click.prevent="addData()" class="btn btn-main w-100 rounded"
							type="submit"><span v-if="isSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ isSubmit ? 'Loading...'   :'Upload' }}</button>
									</div>
								</div>
							</div>


						</template>



					</div>


				</div>

			</div>
		</div>
	</div>
</template>

