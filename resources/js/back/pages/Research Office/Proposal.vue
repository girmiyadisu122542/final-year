<script setup>
import axios from 'axios';
import { ref, onMounted, watch, computed } from 'vue';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useLocalStorage } from '@vueuse/core';

const token = useLocalStorage('token')
const toast = useToast();
const proposals = ref([]);
const isLoading = ref(false)
const perPage = ref("");
const search_key = ref("");
function loadData(url, per_page = null) {
	      let cxr = "?";
            url.includes("?") ? (cxr = "&") : "";
            per_page == null ? (per_page = perPage.value) : "";
            url = url + cxr + "per_page=" + per_page;
           isLoading.value  = true;
            axios.get(url)
                .then((res) => {
                   proposals.value = res.data;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    setTimeout(() => {
                       isLoading.value = false;
                    }, 500);
                });
}

watch(perPage,(newVal)=>{
	    loadData(
              proposals.value.path + "?page=" + proposals.value.current_page,
                newVal
            );
})

const filterByStatus = computed(()=>{
	if (search_key.value != "") {
                 isLoading.value = true
                setTimeout(()=>{
                    isLoading.value =false
                },500)
                return proposals.value.data.filter(
                    (item) =>(item.user.full_name.toLowerCase().includes(search_key.value.toLowerCase()))||(item.announcement.title.toLowerCase().includes(search_key.value.toLowerCase()))
                  
                );
            } else {
                isLoading.value = true
                setTimeout(()=>{
                 isLoading.value =false
                },500)
                return proposals.value.data;
            }
})

function approveDocument(id) {

    Swal.fire({
        title: 'Are you sure?',
        text: "You Want to Change Document Status",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {

            axios.defaults.headers.common['Authorization'] = token.value;
            axios.get('api/approve-proposal/' + id)
                .then(() => {
                  loadData('api/get-researcher-proposal');
                    toast.success("Document Status Changed Successfully!!", {
                        timeout: 2000
                    })
                }).catch(err => {
                    toast.error("You cann't Approve this document!", {
                        timeout: 2000
                    })
                })
        }
    })


}




onMounted(() => {
  loadData('api/get-researcher-proposal');
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
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-2">
            <div class="breadcrumb-title pe-3">Proposals</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            List of Uploaded Proposals
                        </li>

                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
      
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
            
            <div class="table-responsive">
					
							<hr>
                                <div class="row mb-3">
                                    <div class="col-12 col-lg-6 col-xl-6 d-flex">
                                        <select id="inputState" v-model="perPage"
                                            class="form-select border-primary text-primary">
                                            <option value="" selected disabled>Per Page</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                   

                                    <div class="col-12 col-lg-6 col-xl-6 d-flex position-relative">
                                   <input v-model="search_key" type="search" class="form-control ps-5 border-primary text-primary" placeholder="Search by  Name" >
                                        <span class="position-absolute top-50 product-show translate-middle-y"><i
                                                class="bx bx-search"></i></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example2" class="table table-striped dataTable text-primary">
                                            <thead>
                                                <tr role="row">
                                                 <th>#</th>
                                        <th>Announcment</th>
                                        <th>Uploader</th>
                                        <th>File Attached</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Status</th>
                                        <th width="5%">Actions</th>
                                                   
                                                </tr>
                                            </thead>
                                            <template v-if="isLoading">
                                                <tbody>
                                                    <div class="spinner-border text-primary mt-4" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </tbody>
                                            </template>

                                            <template v-else>
                                           <tbody v-if="proposals.data !=  null">
                                            <tr role="row" v-for="(proposal,index) in filterByStatus" :key="proposal.id">
                                            <th scope="row">{{ proposals.from +index }} </th>
                                         <td>{{ proposal.announcement.title.toUpperCase() }}</td>
                                            <td>{{ proposal.user.full_name }}</td>
                                            <td v-if="proposal.file_attached != null"> 
												<i @click.prevent="downloadComment(proposal.id,proposal.file_attached)" class="lni lni-download">download</i>
										 </td>
											<td v-else><span><i><small class="text-secondary">No file</small></i></span></td>
                                            <td>{{ format(new Date(proposal.created_at), 'MMMM do, yyyy') }}</td>
                                            <td>{{ format(new Date(proposal.updated_at), 'MMMM do, yyyy') }}</td>
                                            <td><span
                                                    :class="proposal.status == 0 ? 'badge bg-danger' : proposal.status == 1 ? 'badge bg-success' : 'badge bg-danger'">{{
                                                        proposal.status == 0 ? 'Pending...' : proposal.status == 1 ?
                                                        'Approved' : 'Rejected' }}</span></td>

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

                                                            <li><button                                                                     class="btn btn-outline-primary dropdown-item"><i
                                                                        class="lni lni-eye"></i>View</button>
                                                            </li>
                                                            <li><button
                                                                    @click.prevent="approveDocument(proposal.id)"
                                                                    class="btn btn-outline-danger dropdown-item"><i
                                                                        :class="proposal.status == 2 ? 'fadeIn animated bx bx-plus' : 'fadeIn animated bx bx-minus'"></i>{{
                                                                            proposal.status == 2 ? 'Approve':proposal.status == 1?'Reject' : 'Approve' }}</button>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                                    </tr>
                                                
                                                </tbody>
                                                <tbody v-else>
                                                    <div valign="top" colspan="6" class="text-center dataTables_empty mt-4"
                                                      style="width: 250%;font-size: large;">
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
                Showing {{ proposals.from }} to {{ proposals.to }} of {{ proposals.total }} entries
            </div>
        </div>
                <div class="col-sm-12 col-md-4">
                  <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        <ul class="pagination">
                        <li @click="proposals.current_page ==1? '': loadData(proposals.first_page_url)" :class="{disabled:proposals.current_page == 1,'paginate_button page-item next': true,}" id="example2_previous">
                                <a  aria-controls="example2" data-dt-idx="0" tabindex="0"
                                     class="page-link"><i class="fadeIn animated bx bx-chevrons-left"></i></a>
                        </li>
                        <li @click="proposals.prev_page_url !=null? loadData       (proposals.prev_page_url): ''" :class="{disabled:proposals.prev_page_url ==null,'paginate_button page-item previous': true,}" id="simpletable_next">
                               <a  aria-controls="example2" data-dt-idx="1" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-left"></i></a>
                         </li>
                        <li @click="loadData(proposals.path +'?page=' +proposals.current_page)" class="paginate_button page-item active">
                               <a aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{ proposals.current_page }}</a>
                         </li>
                        <li v-if="proposals.last_page >proposals.current_page +1"
                        @click="loadData(proposals.path +'?page=' +(proposals.current_page +1))"  class="paginate_button page-item">                    
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"> {{proposals.current_page + 1 }}
                                                    </a>
                         </li>
                        <li v-if="proposals.last_page >proposals.current_page +2" 
                        @click="loadData(proposals.path +'?page=' +(proposals.current_page +2))" class="paginate_button page-item"><a  aria-controls="example2" data-dt-idx="6" tabindex="0"  class="page-link">{{ proposals.current_page + 2 }} </a>
                        </li>
                          <li @click="proposals.next_page_url !=null ? loadData(proposals.next_page_url): ''" :class="{disabled:proposals.next_page_url ==null,
                            'paginate_button page-item next': true,}" id="simpletable_next">
                                <a  aria-controls="example2" data-dt-idx="6" tabindex="0"
                                    class="page-link"><i class="fadeIn animated bx bx-chevron-right"></i>
                                </a>
                        </li>
                        <li @click="proposals.current_page == proposals.last_page? '': loadData(proposals.last_page_url)" :class="{disabled:proposals.current_page == proposals.last_page,'paginate_button page-item next': true,}"  id="simpletable_next">
                            <a  aria-controls="example2" data-dt-idx="7" tabindex="0"
                                class="page-link"><i class="fadeIn animated bx bx-chevrons-right"></i>
                                                    </a>
                         </li>
                        <li @click="loadData(proposals.path +'?page=' +proposals.current_page)"  class="paginate_button page-item" id="simpletable_next">
                            <a  aria-controls="example2" data-dt-idx="7" tabindex="0"
                                class="page-link"><i class="fadeIn animated bx bx-refresh"></i>
                                                    </a>
                        </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
        </div>
    </div>
</div></template>
