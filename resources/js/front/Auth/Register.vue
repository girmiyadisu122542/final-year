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
            <div class="col-md-8 col-xl-7">
                
                <div class="signup-form">
                    <div class="form-header">
                        <h2 class="font-weight-bold mb-3">Sign Up</h2>
                        <p class="woocommerce-register">
                            Already have an account? <RouterLink :to="{name:'Login'}" class="text-decoration-underline">Log in</RouterLink>
                        </p>
                    </div>

                    <form  class="woocommerce-form woocommerce-form-register register">

                        <div class="row">
                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>Full Name&nbsp;<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="first-name" placeholder="First Name">
                                </p>
                            </div>
                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>Last Name&nbsp;<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="last-name" placeholder="Last Name">
                                </p>
                            </div>
                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>User Name&nbsp;<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="user-name" value="" placeholder="Username">
                                </p>
                            </div>

                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>Email&nbsp;<span class="required">*</span></label>
                                    <input type="email" class="form-control" name="email" value="" placeholder="Your Email">
                                </p>
                            </div>

                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>Password&nbsp;<span class="required">*</span></label>
                                    <input type="password" class="form-control" name="password" value="" placeholder="Password">
                                </p>
                            </div>
                            <div class="col-xl-6">
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label>Re-Enter Password&nbsp;<span class="required">*</span></label>
                                    <input type="password" class="form-control" name="re-password" value="" placeholder="Re-Enter Password">
                                </p>
                            </div>

                            <div class="col-xl-12">
                                <div class="d-flex align-items-center justify-content-between py-2">
                                    <p class="form-row">
                                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__policy">
                                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="policy" type="checkbox" id="policy" value="forever"> <span>Accept the Terms and Privacy Policy</span>
                                        </label>
                                    </p>
            
                                    <p class="woocommerce-LostPassword lost_password">
                                        <a href="#">Forgot password?</a>
                                    </p>
                               </div>
                            </div>
                        </div>
                      
                        <p class="woocommerce-FormRow form-row">
                            <button type="submit" class="woocommerce-button button" name="register" value="Register">Register</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</template>