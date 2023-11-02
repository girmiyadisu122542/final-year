import { createRouter,createWebHashHistory } from "vue-router";
import Index from './pages/Index.vue';
import About from './pages/About.vue'
import Document from './pages/Documents/DocumentList.vue'
import Contact from './pages/Contact.vue'
import Login from './Auth/Login.vue';
import Register from './Auth/Register.vue';
import DocumentDetail from './pages/Documents/Document_detail.vue'
import Announcement from './pages/Announcement/Announcement.vue'
import Proposal from './pages/Announcement/UploadProposal.vue'
import ProposalStatus from './pages/Announcement/ProposalStatus.vue'
const routes = [
    { path : '/', name : 'Index', component : Index },
    { path : '/login', name : 'Login' , component : Login },
    { path : '/register', name : 'Register' , component : Register },
    { path : '/about', name : 'About' , component : About },
    { path : '/documents', name : 'Document' , component : Document },
    { path : '/contact', name : 'Contact' , component : Contact },
    { path : '/document-detail/:id', name : 'DocumentDetail' , component : DocumentDetail },
    { path : '/announcements', name : 'Announcement' , component : Announcement },
    { path : '/proposal-status', name : 'ProposalStatus' , component : ProposalStatus },
    { path : '/upload-proposal/:id', name : 'Proposal' , component : Proposal },


]

const router = createRouter({
    history:createWebHashHistory(),
    routes
})

export default router;