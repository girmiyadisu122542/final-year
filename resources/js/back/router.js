import   { createRouter , createWebHashHistory } from 'vue-router'
import Dashboard from './Dashboard.vue'
import User from './pages/users/Index.vue'
import Role from './pages/roles/Index.vue'
import College from './pages/Accadamics/College.vue'
import AdmissionType from './pages/Accadamics/AdmissionType.vue'
import Department from './pages/Accadamics/Department.vue'
import Year from './pages/Accadamics/Year.vue'
import StudyLevel from './pages/Accadamics/StudyLevel.vue'
import Advisor from './pages/ProjectCoordinator/Advisor.vue'
import ProjectCoordinator from './pages/Department Head/ProjectCoordinator.vue'
import Student from './pages/Department Head/Student.vue'
import UploadDocument from './pages/Student/UploadDocument.vue'
import ViewDocument from './pages/Advisor/Document.vue'
import ViewComment from './pages/Advisor/Comment.vue'
import ViewDocumentProjectCoordinator from './pages/ProjectCoordinator/Document.vue'
import ViewCommentProjectCoordinator from './pages/ProjectCoordinator/Comment.vue'
import ViewDocumentDeptHead from  './pages/Department Head/Document.vue'
import ViewCommentdeptHead from './pages/Department Head/Comment.vue'
import ViewDocumentpostGraduateCoordinator from  './pages/PostGraduateCoordinator/Document.vue'
import ViewCommentStudent from './pages/Student/Comment.vue'
import Researcher from './pages/Research Office/Researcher.vue'
import ViewDocumentResearchOffice from './pages/Research Office/Document.vue'
import Announcement from './pages/Research Office/Announcement.vue'
import Proposal from './pages/Research Office/Proposal.vue'
import UploadDocumentResearcher from './pages/Researcher/UploadDocument.vue'
import DocumentDetail from  './pages/ViewDocument.vue'
const routes = [
    { path : '/' ,name : 'Dashboard' ,component: Dashboard },
    { path : '/users' , name : 'User' , component : User },
    { path : '/roles' , name : 'Role' , component : Role },
    { path : '/colleges' , name : 'College' , component : College },
    { path : '/admission-types' , name : 'AdmissionType' , component : AdmissionType },
    { path : '/departments' , name : 'Department' , component : Department },
    { path : '/accadamic-years' , name : 'Year' , component : Year },
    { path : '/study-levels' , name : 'StudyLevel' , component : StudyLevel },
    { path : '/advisors' , name : 'Advisor' , component : Advisor },
    { path : '/project-coordinators' , name : 'ProjectCoordinator' , component : ProjectCoordinator },
    { path : '/students' , name : 'Student' , component : Student },
    // student
    { path : '/student/upload-document' , name : 'UploadDocument' , component : UploadDocument },
    { path : '/student/comments/:id' , name : 'ViewCommentStudent' , component : ViewCommentStudent },
    // advisor
    { path : '/documents' , name : 'ViewDocument' , component : ViewDocument },
    { path : '/comments/:id' , name : 'ViewComment' , component : ViewComment },
    //project Coordinator
    { path : '/projectCoordinator/documents' , name : 'ViewDocumentProjectCoordinator' , component : ViewDocumentProjectCoordinator },
    { path : '/projectCoordinator/comments/:id' , name : 'ViewCommentProjectCoordinator' , component : ViewCommentProjectCoordinator },
    // dept head
    { path : '/deptHead/documents' , name : 'ViewDocumentDeptHead' , component : ViewDocumentDeptHead },
    { path : '/deptHead/comments/:id' , name : 'ViewCommentdeptHead' , component : ViewCommentdeptHead },
    // post graduate coordinator
    { path : '/postGraduateCoordinator/documents' , name : 'ViewDocumentpostGraduateCoordinator' , component : ViewDocumentpostGraduateCoordinator },
    // research office
    { path : '/research-office/researchers' , name : 'Researcher' , component : Researcher },
    { path : '/research-office/documents' , name : 'ViewDocumentResearchOffice' , component : ViewDocumentResearchOffice },
    { path : '/research-office/announcements' , name : 'Announcement' , component : Announcement },
    { path : '/research-office/proposals' , name : 'Proposal' , component : Proposal },

    // researcher
    { path : '/researcher/upload-document' , name : 'UploadDocumentResearcher' , component : UploadDocumentResearcher },
    //other 
    { path: '/document-detail/:id', name : 'DocumentDetail', component: DocumentDetail },

] 

const router = createRouter({
    history : createWebHashHistory(),
    routes
})

export default router;