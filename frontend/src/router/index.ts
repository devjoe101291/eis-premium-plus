import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppLayout from '@/components/layout/AppLayout.vue'
// import EmployeeDashboardView from '@/views/EmployeeDashboardView.vue'
import AdminEmployeesView from '@/views/AdminEmployeesView.vue'
import LoginView from '@/views/LoginView.vue'
import NotFoundView from '@/views/NotFoundView.vue'
// import DashboardIcon from '@/components/icons/DashboardIcon.vue'
import EmployeesIcon from '@/components/icons/EmployeesIcon.vue'
// import RegisterView from '@/views/RegistrationView.vue'
import ForgotPasswordView from '@/views/ForgotPasswordView.vue'
import TopicView from '@/views/TopicView.vue'
// import MaterialDetail from '@/views/MaterialDetail.vue'
import { Folders } from 'lucide-vue-next'
import TopicDetail from '@/views/TopicDetail.vue'
import ExamCreator  from '@/views/ExamCreatorView.vue'
import EmployeeTopicView from '@/views/EmployeeMaterials.vue'
import EmployeeExamView from '@/views/EmployeeExamView.vue'
import PaperIcon from '@/components/icons/PaperIcon.vue'
import TakeExam from '@/views/TakeExam.vue'

// Types
interface NavigationItem {
  name: string
  to: string
  icon: any
}

interface RouteMeta {
  requiresAuth?: boolean
  title?: string
  icon?: any
  hidden?: boolean
  roles?: string[] 
}


// Route Configuration
const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      // {
      //   path: '',
      //   name: 'dashboard',
      //   component: EmployeeDashboardView,
      //   meta: {
      //     requiresAuth: true,
      //     title: 'Dashboard',
      //     icon: DashboardIcon
      //   }
      // },
      {
        path: 'employees',
        name: 'employees',
        component: AdminEmployeesView,
        meta: {
          requiresAuth: true,
          title: 'Employees',
          icon: EmployeesIcon,
          roles: ['admin']
        }
      },
      {
        path: 'topic',
        name: 'topic',
        component: TopicView,
        meta: {
          requiresAuth: true,
          roles: ['admin'],
          title: 'Topics',
          icon: Folders
        }
      },
      {
        path: 'employee-materials',
        name: 'employee-materials',
        component: EmployeeTopicView,
        meta: {
          requiresAuth: true,
          roles: ['employee'],
          title: 'Materials',
          icon: Folders
        }
      },
      {
        path: 'employee-exam',
        name: 'employee-exam',
        component: EmployeeExamView,
        meta: {
          requiresAuth: true,
          roles: ['employee'],
          title: 'Exams',
          icon: PaperIcon
        }
      },
      {
        path: 'take-exam/:id',
        name: 'take-exam',
        component: TakeExam,
        meta: {
          requiresAuth: true,
          roles: ['employee'],
          hidden: true,
        }
      },
       {
        path: 'topic:id',
        name: 'topic-details',
        component: TopicDetail,
        meta: {
          requiresAuth: true,
          title: 'Topics',
          icon: Folders,
          hidden: true
        }
      },
      {
  path: '/exams/create',
  name: 'exam-create',
  component: ExamCreator,
  meta: { 
    requiresAuth: true, 
    title: 'Create Exam',
    hidden: true

  },
},
{
  path: '/exams/:id/edit',
  name: 'exam-edit',
  component: ExamCreator,
  meta: { 
    requiresAuth: true ,
    title: 'Edit Exam',
    hidden: true
  },
},

//       {
//   path: '/materials/:id',
//   name: 'material-detail',
//   component: MaterialDetail,
//   meta: { 
//     requiresAuth: true, 
//     title: 'Material Detail',
//   // icon: File
//   hidden: true
//   }
// },
    ]
  },



  
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {
      requiresAuth: false,
      title: 'Login'
    }
  },
  //   {
  //   path: '/register',
  //   name: 'register',
  //   component: RegisterView,
  //   meta: { requiresAuth: false, title: 'Register' }
  // },
    {
    path: '/forgot-password',
    name: 'ForgotPassword',
    component: ForgotPasswordView,
    meta: { requiresAuth: false, title: 'Forgot Password' }
  },
  {
    path: '/500',
    name: 'server-error',
    component: () => import('@/views/ServerErrorView.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFoundView,
    meta: {
      requiresAuth: false,
      title: 'Not Found'
    }
  }
]

// Router Instance
const router = createRouter({
  history: createWebHistory(process.env.VUE_APP_BASE_URL),
  routes
})

// Navigation Helper
export const getNavigationItems = (routes: RouteRecordRaw[]): NavigationItem[] => {
  const authStore = useAuthStore()
  const userRole = authStore.user?.role?.toLowerCase()
  const layoutRoute = routes.find(route => route.path === '/')
  if (!layoutRoute?.children) return []

  return layoutRoute.children
    .filter(route => {
      const meta = route.meta as RouteMeta | undefined
      const routeRoles = (meta?.roles as string[] | undefined) ?? []
      
      // Show if: requires auth, not hidden, and either has no role restrictions OR user role matches
      return (
        meta?.requiresAuth &&
        !meta?.hidden &&                // ✅ hide from sidebar
        route.name !== 'not-found' &&
        (routeRoles.length === 0 || routeRoles.includes(userRole ?? ''))  // ✅ role filtering
      )
    })
    .map(route => ({
      name: (route.meta as RouteMeta)?.title || String(route.name),
      to: `/${String(route.path).replace(/^\/+/, '')}`, // ✅ avoids "//" and keeps child path correct
      icon: (route.meta as RouteMeta)?.icon
    }))
}


// Navigation Guards
// router.beforeEach(async (to, from, next) => {
//   const authStore = useAuthStore()
//   const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

//   // Check if route requires authentication
//   if (requiresAuth) {
//     // Check if user is authenticated
//     const isAuthenticated = await authStore.checkAuth()
    
//     if (!isAuthenticated) {
//       // Redirect to login if not authenticated
//       next({ name: 'login', query: { redirect: to.fullPath } })
//       return
//     }
//   }

//   // If user is authenticated and trying to access login page
//   if (to.name === 'login' && authStore.isAuthenticated) {
//     next({ name: 'employees' })
//     return
//   }

//   next()
// })

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  const requiresAuth = to.matched.some(r => r.meta.requiresAuth)

  // ✅ Ensure auth state is up-to-date (token → user)
  if (!authStore.isInitialized) {
    await authStore.checkAuth()
  }

  const isAuthed = authStore.isAuthenticated
  const userRole = authStore.user?.role?.toLowerCase() ?? ''
  const allowedRoles = ((to.meta.roles as string[] | undefined) ?? []).map(r => r.toLowerCase())

  // 🚫 Not authenticated but route requires auth
  if (requiresAuth && !isAuthed) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  // ✅ If authenticated but role not loaded yet, DON'T redirect on protected pages
  // This prevents "refresh bounce" to wrong route while user is hydrating.
  if (requiresAuth && isAuthed && !userRole) {
    return next()
  }

  // 🚫 Authenticated but role not allowed
  if (requiresAuth && allowedRoles.length && !allowedRoles.includes(userRole)) {
    if (userRole === 'admin') return next({ name: 'topic' })
    if (userRole === 'employee') return next({ name: 'employee-materials' })
    return next({ name: 'login' })
  }

  // 🚫 Already logged in → block login page (role-safe)
  if (to.name === 'login' && isAuthed) {
    if (!userRole) return next() // wait until role exists

    if (userRole === 'admin') return next({ name: 'topic' })
    if (userRole === 'employee') return next({ name: 'employee-materials' })

    // ✅ unknown role: don’t force admin
    return next()
  }

  next()
})


export default router 