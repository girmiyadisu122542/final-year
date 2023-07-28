<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns'
const token = useLocalStorage('token')
const props = defineProps({
	dataSource: {
		type: String,
		required: true
	},
	tableHead: {
		type: Array
	}
})
const stores = ref([]);
const isLoading = ref(false)
let index = ref(0)
function loadData(url) {
	isLoading.value = true
	axios.get(url)
		.then(res => {
			stores.value = res.data.data
			setTimeout(() => {
				isLoading.value = false
			}, 500);

			//console.log(res.data)
		}).catch(err => {
			console.log(err.data.response)
		}).finally(()=>{
			isLoading.value = false
		})
}

onMounted(() => {
	axios.defaults.headers.common['Authorization'] = token.value
	loadData(props.dataSource)
})

</script>

<template>
	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<div class="row">

					<div class="col-12 col-lg-8 col-xl-8 d-flex">

					</div>
					<div class="col-12 col-lg-4 col-xl-4 d-flex">
						<div class="dt-buttons btn-group">
							<button class="btn btn-outline-success " type="button">
								<span>Grid</span>
							</button>
							<button class="btn btn-outline-info " type="button">
								<span>Excel</span>
							</button>
							<button class="btn btn-outline-danger " type="button">
								<span>Import</span>
							</button>
							<button class="btn btn-outline-warning " type="button">
								<span><i class="fadeIn animated bx bx-plus-circle"></i>Create</span>
							</button>
						</div>
					</div>
				</div>
				<hr>
				<div class="row mb-3">
					<div class="col-12 col-lg-4 col-xl-4 d-flex">
						<select id="inputState " class="form-select border border-primary text-primary">
							<i class="lni lni-funnel"></i>
							<option selected>Filter By</option>
							<option>Name</option>
							<option>Age</option>
							<option>Email</option>

						</select>
					</div>
					<div class="col-12 col-lg-3 col-xl-3 d-flex">
						<select id="inputState" class="form-select border border-primary text-primary">
							<option selected>Per Page</option>
							<option>10</option>
							<option>15</option>
							<option>20</option>

						</select>
					</div>
					<div class="col-12 col-lg-5 col-xl-5 d-flex position-relative">
						<input type="search" class="form-control ps-5 border border-primary text-primary"
							placeholder="Search..."> <span
							class="position-absolute top-50 product-show translate-middle-y "><i
								class="bx bx-search"></i></span>

					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table id="example2" class="table table-striped  dataTable text-primary">
							<thead>
								<tr role="row">
									<th v-for=" row in tableHead" :key="row.value">{{ row.header }} </th>

									<th width="5%">Actions</th>
								</tr>
							</thead>
							<template v-if="isLoading">
								<tbody>
								
								<div class="spinner-border text-primary mt-4" role="status"> <span class="visually-hidden">Loading...</span>
								</div>
								
						
								</tbody>

							</template>
							<template v-else>
								<tbody v-if="stores.length != 0">
									<tr role="row" v-for="store in stores" :key="store.value">
										<td v-for="row in tableHead" :key="row.value">
											<template v-if="row.value == 'created_at' || row.value == 'updated_at'">
												{{ format(new Date(store[row.value]), 'MMMM do, yyyy') }}
											</template>
											<template v-else>
												{{ store[row.value] }}

											</template>
										</td>


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
														<li><button class="btn btn-outline-info dropdown-item "><i
																	class="fadeIn animated bx bx-edit"></i>Edit</button>
														</li>
														<li><button class="btn btn-outline-danger dropdown-item"><i
																	class="fadeIn animated bx bx-trash"></i>Delete</button>
														</li>
														<li><button class="btn btn-outline-success dropdown-item">
																<i class="lni lni-eye"></i>View</button>
														</li>
													</ul>
												</div>
											</div>
										</td>

									</tr>
								</tbody>
								<tbody v-else >
								 <div valign="top" colspan="6" class=" text-center dataTables_empty mt-4 " style="width: 250%;font-size: large;">

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
							Showing 1 to 10 of 57 entries
						</div>
					</div>
					<div class="col-sm-12 col-md-4">
						<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
							<ul class="pagination">
								<li class="paginate_button page-item previous disabled" id="example2_previous">
									<a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
										<i class="fadeIn animated bx bx-chevrons-left"></i> </a>
								</li>
								<li class="paginate_button page-item active">
									<a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">
										<i class="fadeIn animated bx bx-chevron-left"></i>
									</a>
								</li>
								<li class="paginate_button page-item">
									<a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">
										1
									</a>
								</li>
								<li class="paginate_button page-item">
									<a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">
										<i class="fadeIn animated bx bx-chevron-right"></i>
									</a>
								</li>
								<li class="paginate_button page-item next" id="example2_next">
									<a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">
										<i class="fadeIn animated bx bx-chevrons-right"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</template>