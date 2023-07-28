import { createApp } from "vue";
import { createPinia, Pinia } from "pinia";
import Back from './Back.vue'
import router from './router'
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
  

const back =createApp(Back);
const pinia = createPinia();
back.use(router);
back.use(pinia)
back.use(Toast,options);
back.mount('#app');
