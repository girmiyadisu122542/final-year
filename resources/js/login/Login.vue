<script setup>
import { computed, onMounted, ref } from 'vue';
import { useLocalStorage } from '@vueuse/core';
import axios from 'axios';
import { useToast } from "vue-toastification";
import { useBackendStore } from '../stores/useBackendStore';
const username = ref('');
const password = ref('');
const isLogin = ref(false);
const token = useLocalStorage('token', '');
const user = useLocalStorage('user','');
const errors = ref({});
const authError = ref('');
const errTime = ref(false);
const toast = useToast();
const showPage = ref(true)
const isRegister = ref(true)
// register

const fullname = ref('');
const email = ref('');
const rpassword = ref('');
const password_confirmation = ref('');
const department = ref('');
const role = ref('');
const college = ref('');
const study = ref('')
const departments = ref({});
const back = useBackendStore();
const rerrors = ref({});
const isSubmit = ref(false)
const rerrTime = ref(false)
function login() {
    if (isLogin.value == true) return;
    isLogin.value = true;
    axios.post('api/login', {
        email: username.value,
        password: password.value
    }).then(res => {
         
        token.value = 'Bearer ' + res.data.access_token;
        user.value = JSON.stringify(res.data.user) ;
        window.location.href = "/admin"
        toast.success("logged in successfully", {
            timeout: 2000
        })

    }).catch(err => {
        errTime.value = true
        if (err.response.status === 422) {
            errors.value = err.response.data.errors;
            authError = '';
        }
        else if (err.response.status === 401) {
            authError.value = err.response.data.message;
            errors.value = {};
        } else {
            toast.error("Something went Wrong!!", {
                timeout: 2000
            })
        }

    }).finally(() => {
        setTimeout(() => {
            isLogin.value = false
        }, 700),
            setTimeout(() => {
                errTime.value = false

            }, 3000)
    })

}

function addUser() {
    if (isSubmit.value == true) return;
    isSubmit.value = true
    axios.post('api/register', {
        'full_name': fullname.value,
        'email': email.value,
        'role': role.value,
        'department': department.value,
        'college': college.value,
        'study_level': study.value,
        'password': rpassword.value,
        'password_confirmation': password_confirmation.value,

    }).then(() => {
       
        window.location.href = '/login'
        toast.success("Successfully Registsered!!", {
            timeout: 2000
        })
    }).catch(err => {
        errTime.value = true
        if (err.response.status === 422) {
            rerrors.value = err.response.data.errors;
        } else {
            toast.error("Somethig Went Wrong!!!", {
                timeout: 2000
            })
        }

    }).finally(() => {
        setTimeout(() => {
            isSubmit.value = false
            resetState();
        }, 700);
        setTimeout(() => {
            errTime.value = false
            rerrors.value = {}
        }, 4000)
    })
}


const filterDept = computed(() => {
    // if(!college.value) return departments.value
    if (college.value && study.value) {

        return departments.value.filter((department) => (department.college_id == college.value) && department.study_level_id == study.value);
    }
})
function resetState() {

    fullname.value = '',
        email.value = '',
        role.value = '',
        department.value = '',
        college.value = ''
    study.value = ''
    rpassword.value = ''
    password_confirmation.value = ''
}

onMounted(() => {
    axios.get('api/all-departments')
        .then(res => {
            departments.value = res.data;
        }).catch(err => {
            console.log(err);
        })
})
</script>
<template>
    <div class="wrapper">
        <header class="login-header shadow">
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm">
                <div class="container-fluid">
                    <RouterLink to="/" class="navbar-brand">
                        <img :src="'static_image/title.jfif'" width="50" height="50" alt="" />
                    </RouterLink>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1"
                        aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item"> <a class="nav-link active" aria-current="page" href="/"><i
                                        class='bx bx-home-alt me-1'></i>Home</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    </div>
    <div class="wrapper" v-if="showPage">

        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-4">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card shadow-none mt-5">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center mb-4">
                                        <h3 class="">Sign in</h3>
                                        <p class="mb-0">Login to your account</p>
                                        <p class="text-danger" v-if="authError != null && errTime == true">{{ authError }}</p>

                                    </div>

                                    <div class="form-body">
                                        <form class="row g-4">
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input type="email" v-model="username" class="form-control"
                                                    id="inputEmailAddress" placeholder="Email Address">
                                                <p class="text-danger" v-if="errors.email && errTime == true"> *{{
                                                    errors.email[0] }}</p>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        id="inputChoosePassword" v-model="password"
                                                        placeholder="Enter Password">

                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>

                                                </div>
                                                <p class="text-danger" v-if="errors.password && errTime == true"> *{{
                                                    errors.password[0] }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Remember
                                                        Me</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end"> <a href="javascript:;"
                                                    @click="showPage = false, isRegister = false">Forgot
                                                    Password ?</a>
                                            </div>
                                            <div class="col-12" v-if="isLogin">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="button" disabled=""> <span
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...</button>
                                                </div>
                                            </div>
                                            <div class="col-12" v-else>
                                                <div class="d-grid">
                                                    <button @click.prevent="login()" type="button"
                                                        class="btn btn-primary "><i class="bx bxs-lock-open"></i>Sign
                                                        in</button>
                                                </div>

                                            </div>
                                            <div class="col-12 text-center">
                                                <p class="mb-0">Don't have an account yet? <a href="javascript:;"
                                                        @click="showPage = false">Sign up here</a>
                                                </p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>

    </div>
    <div class="wrapper" v-if="!showPage && !isRegister">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card forgot-box shadow-none">
                <div class="card-body">
                    <div class="p-4 rounded  border">
                        <div class="text-center">
                            <img :src="'backend/assets/images/icons/forgot-2.png'" width="120" alt="" />
                        </div>
                        <h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
                        <p class="text-muted">Enter your registered email ID to reset the password</p>
                        <div class="my-4">
                            <label class="form-label">Email id</label>
                            <input type="text" class="form-control form-control-lg" placeholder="example@user.com" />
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg">Send</button> <a
                                @click="showPage = true, isRegister = true" class="btn btn-light btn-lg"><i
                                    class='bx bx-arrow-back me-1'></i>Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <div class="wrapper" v-if="!showPage && isRegister">
        <div class="d-flex align-items-center justify-content-center py-5 my-lg-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center mb-4">
                                        <h3 class="">Sign Up</h3>
                                        <p class="mb-0">Create your account</p>
                                    </div>
                                    <hr>
                                    <div class="form-body">
                                        <form class="row g-4">
                                            <div class="col-sm-6">
                                                <label for="inputFirstName" class="form-label">Full Name</label>
                                                <input v-model="fullname" type="text" class="form-control"
                                                    id="inputFirstName" placeholder="enter full name">
                                               <div class="text-danger" v-if="rerrors.full_name && errTime == true">*{{ rerrors.full_name[0] }} </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input v-model="email" type="email" class="form-control"
                                                    id="inputEmailAddress" placeholder="example@user.com">
                                                <div class="text-danger" v-if="rerrors.email && errTime == true">*{{ rerrors.email[0] }} </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="inputChoosePassword" class="form-label">Password</label>
                                                <input v-model="rpassword" type="password"
                                                        class="form-control border-end-0" placeholder="Enter Password">
                                                   <div class="text-danger" v-if="rerrors.password && errTime == true">*{{ rerrors.password[0] }} </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="inputChoosePassword" class="form-label">Confirm Password</label>
                                                <input v-model="password_confirmation" type="password"
                                                        class="form-control border-end-0" placeholder="Enter Password">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="inputSelectCountry" class="form-label">Sign Up As</label>
                                                <select v-model="role" class="form-select"
                                                    aria-label="Default select example">
                                                    <option disabled selected="" value="">sign up as</option>
                                                    <option value="Student">Student</option>
                                                    <option value="User">User</option>
                                                    <option value="Researcher">Researcher</option>
                                                </select>
                                                <div class="text-danger" v-if="rerrors.role && errTime == true">*{{ rerrors.role[0] }} </div>
                                            </div>
                                            <template v-if="role == 'Student'">
                                                <div class="col-sm-6">
                                                    <label for="inputSelectCountry" class="form-label">College</label>
                                                    <select @change="filterDept" v-model="college" class="form-select"
                                                        aria-label="Default select example">
                                                        <option disabled selected="" value="">select college</option>
                                                        <option v-for="coll in back.colleges" :value="coll.id">{{ coll.name }}
                                                        </option>

                                                    </select>
                                                    <div class="text-danger" v-if="rerrors.college && errTime == true">*{{ rerrors.college[0] }} </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="inputSelectCountry" class="form-label">Study Level</label>
                                                    <select @change="filterDept" v-model="study" class="form-select"
                                                        aria-label="Default select example">
                                                        <option disabled selected="" value="">select study level</option>
                                                        <option v-for="study in back.studyLevels" :value="study.id">
                                                            {{ study.name }}</option>

                                                    </select>
                                                    <div class="text-danger" v-if="rerrors.study_level && errTime == true">*{{ rerrors.study_level[0] }} </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="inputSelectCountry" class="form-label">Department</label>
                                                    <select v-model="department" class="form-select"
                                                        aria-label="Default select example">
                                                        <option disabled selected="" value="">select department</option>
                                                        <option v-for="dept in filterDept" :value="dept.id">{{ dept.name }}
                                                        </option>

                                                    </select>
                                                    <div class="text-danger" v-if="rerrors.department && errTime == true">*{{ rerrors.department[0] }} </div>
                                                </div>
                                            </template>
                                            <div class="col-12">

                                                <button :disabled="isSubmit" @click.prevent="addUser()" type="submit"
                                                    class="btn btn-primary"><i v-if="!isSubmit" class="bx bx-user"></i><span v-if="isSubmit" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ isSubmit ? 'Loading...'   :'Sign Up' }}</button>
                                                <button class="btn  btn-danger" @click.prevent="resetState()"><i
                                                        class="fadeIn animated bx bx-trash-alt"></i>Clear</button>

                                            </div>
                                            <div class="col-12 text-center">
                                                <p class="mb-0">Already have an account? <a href="javascript:;"
                                                        @click="showPage = true, isRegister = true">Sign in here</a>
                                                </p>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</div>
<footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
    <p class="mb-0">Copyright © 2021. All right reserved.</p>
</footer></template>