<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

import { useLocalStorage } from '@vueuse/core';
import { format } from 'date-fns';
 


const token = useLocalStorage('token')
const isLoading = ref(false)
const proposals = ref({})

onMounted(()=>{
	axios.defaults.headers.common['Authorization'] = token.value
    axios.get("api/proposal-status")
	.then(res=>{
		proposals.value = res.data
	}).catch(err=>{
		console.log(err);
	})
})

function downloadComment(id,file){
	this.axios({
                url: '/api/download-proposal/' + id,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: 'application/pdf'
                })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = file
                link.click()
            });
        }
</script>

<template>
	<section class="page-header">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-8">
					<div class="title-block">
						<h1>Proposal Status</h1>
						<ul class="header-bradcrumb justify-content-center">
							<li>
								<RouterLink :to="{ name: 'Index' }">Home</RouterLink>
							</li>
							<li class="active" aria-current="page">
								Proposal Status
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
                <div class="col-lg-12 col-xl-12" >
                    <div class="woocommerce-cart">
                        <div class="woocommerce-notices-wrapper"></div>
                        <form class="woocommerce-cart-form" action="#" method="">
                            <table  class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-remove">#</th>
                                        <th class="product-thumbnail">Announcment Title</th>
                                        <th class="product-name">Uploader</th>
                                        <th class="product-price">File Attached</th>
                                        <th class="product-quantity">Created At</th>
                                        <th class="product-subtotal">Status</th>
                                    </tr>
                                </thead>

                                <tbody v-if="proposals.length !=0">
                                <tr class="woocommerce-cart-form__cart-item cart_item" v-for="(proposal,index) in proposals" :key="proposal.index">
                                    <td class="product-remove">
                                      {{ index + 1 }}
                                    </td>
                                    <td> {{ proposal.announcement.title }}</td>
 
                                    <td class="product-name" data-title="Product">
                                        <a href="#"> {{proposal.user.full_name}}</a>
                                    </td>

                                    <td v-if="proposal.file_attached != null"> 
												<i @click.prevent="downloadComment(proposal.id,proposal.file_attached)" class="lni lni-download">download</i>
										 </td>
											<td v-else><span><i><small class="text-secondary">No file</small></i></span></td>

                                            <td>{{ format(new Date(proposal.updated_at), 'MMMM do, yyyy') }}</td>
                                    <td class="product-subtotal" data-title="Total">
                                        <span v-if="proposal.status == 0" class="woocommerce-Price-amount amount text-danger">pending...</span>
                                        <span v-else-if="proposal.status == 1" class="woocommerce-Price-amount amount text-success">Approved</span>
                                        <span v-else class="woocommerce-Price-amount amount text-danger">Rejected</span>
                                    </td>
                                </tr>
                            
                              
                                </tbody>
                                <tbody v-else>
                                        <div valign="top" colspan="6" class=" text-center dataTables_empty mt-4 "
                                            style="width: 250%;font-size: large;">

                                            <span class="badge bg-light text-primary">No Records Found!</span>
                                        </div>
                                    </tbody>
                            </table>
                        </form>
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

