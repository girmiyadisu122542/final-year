<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { format } from 'date-fns'
import { useRoute } from 'vue-router';

const isLoading = ref(false)
const documents = ref([])
const toast = useToast();
const route = useRoute();
const lateDocuments = ref({})
function loadDocument() {
    let id = route.params.id
    axios.get("api/get-single-document/"+ id)
        .then(res => {
            // console.log(res.data)
            documents.value = res.data;
           
        }).catch(err => {
            toast.error("Somethig Went Wrong!!!", {
                timeout: 2000
            })
        })
}

onMounted(() => {
    loadDocument();
    axios.get("api/latest-documents")
        .then(res => {
            lateDocuments.value = res.data.data
        }).catch(err => {
            console.log(err);
        })

})

function downloadDocument(id,file){
	this.axios({
                url: '/api/download-documnet/' + id,
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
                <div class="col-lg-8 col-xl-8" v-for="document in documents">
                    <div class="title-block">
                        <h1>{{document.title }}</h1>
                        <ul class="header-bradcrumb justify-content-center">
                            <li>
                                <RouterLink :to="{ name: 'Index' }">Home</RouterLink>
                            </li>
                            <li class="active" aria-current="page">
                                Document Detail
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
                <div class="col-lg-12 col-xl-12" v-for="document in documents" :key="document.id">
                    <div class="post-single">
                        <div class="post-thumb"  v-if="document.cover_page">
                            <img  style="width: 600px;height: 500px;" :src="document.cover_page" alt="" class="img-fluid">
                        </div>
                        <div class="post-thumb" v-else>
                            <img  style="width: 600px;height: 500px;" src="document/no_image.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="single-post-content">
                            <div class="post-meta">
                                <span class="post-author">by {{document.user.full_name}}</span>
                                <span class="post-date"><i class="fa fa-calendar-alt mr-2"></i>{{ format(new Date(document.created_at),'MMMM do, yyyy') }}</span>
                                
                            </div>
                            <h3 class="post-title"> {{document.title}}
                            </h3>
                            <p>{{document.abstract}}</p>
                        
                            <h4 class="inner-title" v-if="document.department!=null">Department: {{ document.department.name }}</h4>
                            <p>Acadamic Year : {{ document.year.name }}</p>
                            <ol>
                                <li>Page Number: {{ document.page_number }}</li>
                                <li>Format: {{ document.format }}</li>
                                <li>Author:{{ document.author }}</li>
                            </ol>

                           
                        </div>

                        <!--  Share -->

                        <div class="blog-footer-meta d-md-flex justify-content-between align-items-center">
                            <div class="post-tags mb-4 mb-md-0"> 
                                <!-- downloadDocument(document.id, document.path) -->
                                <button @click.prevent="downloadDocument(document.id,document.path)"
                                    class="btn btn-outline-primary dropdown-item">
                                    <i class="lni lni-download">download</i></button>


                            </div>


                        </div>








                </div>
            </div>

        </div>
    </div>
</div></template>

