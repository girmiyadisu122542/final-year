import   { createRouter , createWebHashHistory } from 'vue-router'
import Dashboard from './Dashboard.vue'
import User from './pages/users/Index.vue'
import Role from './pages/roles/Index.vue'
import College from './pages/Accadamics/College.vue'
import AdmissionType from './pages/Accadamics/AdmissionType.vue'
import Department from './pages/Accadamics/Department.vue'
import Year from './pages/Accadamics/Year.vue'
import StudyLevel from './pages/Accadamics/StudyLevel.vue'

const routes = [
    { path : '/' ,name : 'Dashboard' ,component: Dashboard },
    { path : '/users' , name : 'User' , component : User },
    { path : '/roles' , name : 'Role' , component : Role },
    { path : '/colleges' , name : 'College' , component : College },
    { path : '/admission-types' , name : 'AdmissionType' , component : AdmissionType },
    { path : '/departments' , name : 'Department' , component : Department },
    { path : '/accadamic-years' , name : 'Year' , component : Year },
    { path : '/study-levels' , name : 'StudyLevel' , component : StudyLevel },
] 

const router = createRouter({
    history : createWebHashHistory(),
    routes
})

export default router;