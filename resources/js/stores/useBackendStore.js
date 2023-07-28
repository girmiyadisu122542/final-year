import axios from "axios";
import { defineStore } from "pinia";
import { onMounted, ref } from "vue";
import { useLocalStorage } from "@vueuse/core";
export const useBackendStore = defineStore('useBackendStore',()=>{
   const departments = ref({});
   const roles = ref({});
   const token = useLocalStorage('token');
   const colleges = ref({})
   const admissionTypes = ref({})
    function getDepartment(){
        axios.get('api/all-departments')
        .then(res=>{
            departments.value = res.data;
        }).catch(err=>{
            console.log(err);
        })
    }
    function getRole(){
        axios.defaults.headers.common['Authorization'] = token.value
        axios.get('api/roles')
        .then(res=>{
            roles.value = res.data;
        }).catch(err=>{
            console.log(err);
        })
    }
    function getCollege(){
        axios.get('api/colleges')
        .then(res=>{
            colleges.value = res.data;
        }).catch(err=>{
            console.log(err);
        })
    }
    function getAdmissionTypes(){
        axios.get('api/admissionTypes')
        .then(res=>{
            admissionTypes.value = res.data;
        }).catch(err=>{
            console.log(err);
        })
    }
 
 onMounted(()=>{
    getDepartment(),
    getRole(),
    getCollege(),
    getAdmissionTypes()
 })
    return {
        departments,
        roles,
        colleges,
        admissionTypes
    } 
    
});