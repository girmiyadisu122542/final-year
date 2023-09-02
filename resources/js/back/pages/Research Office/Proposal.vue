<script setup>
import axios from 'axios';
import { ref, onMounted, watch, reactive } from 'vue';
import { format } from 'date-fns'
import Swal from "sweetalert2";
import { useToast } from 'vue-toastification';
import { useLocalStorage } from '@vueuse/core';

const token = useLocalStorage('token')
const toast = useToast();
const proposals = ref([]);
const isLoading = ref(false)
function loadData() {
    isLoading.value = true
    axios.defaults.headers.common['Authorization'] = token.value
    axios.get('api/get-researcher-proposal')
        .then(res => {
            proposals.value = res.data
            setTimeout(() => {
                isLoading.value = false
            }, 500);

            //console.log(res.data)
        }).catch(err => {
            console.log(err.data.response)
        }).finally(() => {
            isLoading.value = false
        })
}
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
                    loadData();
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
    loadData();
})






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

                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-12 col-lg-4 col-xl-4 d-flex">
                            <select id="inputState " class="form-select  border-primary text-primary">
                                <i class="lni lni-funnel"></i>
                                <option selected>Filter By</option>
                                <option>Name</option>
                                <option>Age</option>
                                <option>Email</option>

                            </select>
                        </div>
                        <div class="col-12 col-lg-3 col-xl-3 d-flex">
                            <select id="inputState" class="form-select  border-primary text-primary">
                                <option value="" selected disabled>Per Page</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>

                            </select>
                        </div>
                        <div class="col-12 col-lg-5 col-xl-5 d-flex position-relative">
                            <input type="search" class="form-control ps-5  border-primary text-primary"
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

                                        <div class="spinner-border text-primary mt-4" role="status"> <span
                                                class="visually-hidden">Loading...</span>
                                        </div>


                                    </tbody>

                                </template>
                                <template v-else>
                                    <tbody v-if="proposals.length != 0">
                                        <tr role="row" v-for="(proposal, index) in proposals" :key="proposal.index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ proposal.announcement.title.toUpperCase() }}</td>
                                            <td>{{ proposal.user.full_name }}</td>
                                            <td>{{ proposal.file_attached }}</td>
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
                                                                            proposal.status == 2 ? 'Approve' : 'Reject' }}</button>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <div valign="top" colspan="6" class=" text-center dataTables_empty mt-4 "
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
</div></template>
