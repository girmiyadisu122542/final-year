<script setup>
import {  useLocalStorage } from "@vueuse/core";
import { useToast } from "vue-toastification"; 
import {ref,onMounted } from 'vue';
import Swal from "sweetalert2";
import axios from "axios";
 const token = useLocalStorage('token');
 const user = useLocalStorage('user');
 const toast = useToast();
 import Auth from '../../Auth'
const userData = ref('')
userData.value = Auth.user
const user_role =ref('')
   
function logout(){
    Swal.fire({
  title: 'Are you sure?',
  text: "You Want to logged out!!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes'
}).then((result) => {
  if (result.isConfirmed) {
  
      axios.defaults.headers.common['Authorization']= token.value;
      axios.post('api/logout')
      .then(()=>{
          token.value= "";
          user.value = ""

          toast.success("successfully Logged out!!",{
                timeout:2000
            })
         window.location.href="/";   
      }).catch(err=>{
        toast.error("Something Went wrong",{
                timeout:2000
            })
      })
  }
})
}
onMounted(()=>{
 let role_id = userData.value.role_id
axios.defaults.headers.common['Authorization'] = token.value
axios.get('api/get-role/'+role_id)
   .then(res=>{
    user_role.value = res.data[0].name
   }).catch(err=>{
    console.log(err)
   })

})
</script>

<template>
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand">
                <div class="mobile-toggle-menu"><i class="bx bx-menu"></i></div>
                
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item mobile-search-icon">
                            <a class="nav-link" href="#">
                                <i class="bx bx-search"></i>
                            </a>
                        </li>
                      
                    </ul>
                </div>
                <div class="user-box dropdown">
                    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img :src="'backend/assets/images/avatars/avatar-1.png'" class="user-img" alt="user avatar" />
                        <div class="user-info ps-3">
                            <p class="user-name mb-0">{{userData.full_name}}</p>
                            <p class="designattion mb-0">{{userData.email}}</p>
                            <p class="designattion mb-0 ">{{user_role}}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a>
                        </li>
                        <li>
                            <RouterLink to="/" class="dropdown-item" href="javascript:;"><i
                                    class="bx bx-home-circle"></i><span>Dashboard</span></RouterLink>
                        </li>
                       
                        <li>
                            <div class="dropdown-divider mb-0"></div>
                        </li>
                        <li>
                            <a  @click.prevent="logout()" class="dropdown-item" ><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>
