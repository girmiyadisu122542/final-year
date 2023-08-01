<script setup>
import { ref } from 'vue';
import axios from 'axios'
import { useLocalStorage } from '@vueuse/core';
import { useToast } from "vue-toastification"; 
    const username = ref('');
    const password = ref('');
    const isLogin = ref(false);
    const token = useLocalStorage('token','');
    const errors = ref({});
    const authError = ref('');
    const errTime = ref(false);
    const toast = useToast();
    function login(){
        if(isLogin.value == true) return;
        isLogin.value = true;
        axios.post('api/login',{
            email : username.value,
            password : password.value
        }).then(res=>{
            token.value = 'Bearer '+res.data.access_token;
            window.location.href = "/admin"
            toast.success("loggin successfully",{
                timeout:2000
            })
        
        }).catch(err=>{
            errTime.value = true
            if(err.response.status === 422){
                errors.value = err.response.data.errors;
                authError = '';
            }
            else if(err.response.status === 401){
                authError.value = err.response.data.message;
                errors.value = {};
            }else{
                console.log("something went wrong")
            }
            
        }).finally(()=>{
            setTimeout(()=>{
                isLogin.value = false
            },700),
            setTimeout(()=>{
                errTime.value = false

            },3000)
        })

    }


</script>

<template>
<section class="page-wrapper woocommerce single">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-xl-5">
                <div class="woocommerce-notices-wrapper"></div>
                <div class="login-form">
                    <div class="form-header">
                        <h2 class="font-weight-bold mb-3">Login</h2>
    
                        <p class="woocommerce-register">
                            Don't have an account yet? <RouterLink :to="{name:'Register'}" class="text-decoration-underline">Sign Up for Free</RouterLink>
                        </p>
                        <p class="text-danger" v-if="authError!=null  && errTime==true ">{{ authError }}</p>
                    </div>
                    <form class="woocommerce-form woocommerce-form-login login" >
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="username">Username or email address&nbsp;<span class="required">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text form-control"  placeholder="Username or Email" v-model="username">
                            <p class="text-danger" v-if="errors.email && errTime==true"> *{{ errors.email[0] }}</p>
                        </p>
                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="password">Password&nbsp;<span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text form-control" type="password"   placeholder="Password" v-model="password">
                            <p class="text-danger" v-if="errors.password && errTime==true"> *{{ errors.password[0] }}</p>

                        </p>
                       
                       <div class="d-flex align-items-center justify-content-between py-2">
                            <p class="form-row">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"> <span>Remember me</span>
                                </label>
                            </p>
    
                            <p class="woocommerce-LostPassword lost_password">
                                <a href="#">Forgot password?</a>
                            </p>
                       </div>
    
                       <p class="form-row">
                        <button type="button" :disabled="isLogin" class="woocommerce-button button woocommerce-form-login__submit" @click.prevent="login()"> {{ isLogin?'Logging...':'Login' }}</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</template>