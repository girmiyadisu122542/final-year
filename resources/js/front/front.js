import { createApp } from "vue";
import { createPinia } from "pinia";
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
import Front from './Front.vue';
import router from "./router";
const front = createApp(Front);
const pinia = createPinia();
        front.use(router);
        front.use(pinia);
        front.use(Toast,options);
front.mount('#app');