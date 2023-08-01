import { createRouter,createWebHashHistory } from "vue-router";
import Index from './pages/Index.vue';
import Login from './Auth/Login.vue';
import Register from './Auth/Register.vue';
const routes = [
    { path : '/', name : 'Index', component : Index },
    { path : '/login', name : 'Login' , component : Login },
    { path : '/register', name : 'Register' , component : Register },


]

const router = createRouter({
    history:createWebHashHistory(),
    routes
})

export default router;