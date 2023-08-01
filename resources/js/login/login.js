import { createApp } from "vue";
import { createPinia } from "pinia";
import Login from './Login.vue'
const pinia = createPinia();
const login = createApp(Login);
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const options ={
    transition: "Vue-Toastification__bounce",
    maxToasts: 5,
    newestOnTop: true,
    closeOnClick: true,
    showCloseButtonOnHover: true,
    position: "top-right",
    icon: true,
    rtl: false
  }
login.use(pinia)
login.use(Toast,options);
login.mount('#app');