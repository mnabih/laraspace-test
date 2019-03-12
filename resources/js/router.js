import Vue from 'vue'
import VueRouter from 'vue-router'
import AuthService from './services/auth'

/*
 |--------------------------------------------------------------------------
 | Admin Views
 |--------------------------------------------------------------------------|
 */

// Dashboard
import Basic from './views/admin/dashboard/Basic.vue'
import permissions from './views/admin/permissions/permissions.vue'
import editPermissions from './views/admin/permissions/EditPermissions.vue'

// Layouts
 import LayoutBasic from './views/layouts/LayoutBasic.vue'
 import LayoutLogin from './views/layouts/LayoutLogin.vue'
 import LayoutFront from './views/layouts/LayoutFront.vue'

// Basic UI

// Users
import Users from './views/admin/users/Users.vue'



/*
 |--------------------------------------------------------------------------
 | Other
 |--------------------------------------------------------------------------|
 */

// Auth
import Login from './views/auth/Login.vue'
import Register from './views/auth/Register.vue'

import NotFoundPage from './views/errors/404.vue'

/*
 |--------------------------------------------------------------------------
 | Frontend Views
 |--------------------------------------------------------------------------|
 */

import Home from './views/front/Home.vue'

Vue.use(VueRouter)

const routes = [

  /*
   |--------------------------------------------------------------------------
   | Layout Routes for DEMO
   |--------------------------------------------------------------------------|
   */

  {
    path: '/admin/layouts',
    component: LayoutBasic,
    children: [
      {
        path: 'sidebar',
        component: Basic
      }
    ]
  },
  

  /*
   |--------------------------------------------------------------------------
   | Frontend Routes
   |--------------------------------------------------------------------------|
   */

  {
    path: '/',
    component: LayoutFront,
    children: [
      {
        path: '/',
        component: Home,
        name: 'home'
      }
    ]
  },

  /*
   |--------------------------------------------------------------------------
   | Admin Backend Routes
   |--------------------------------------------------------------------------|
   */
  {
    path: '/admin',
    component: LayoutBasic, // Change the desired Layout here
    meta: { requiresAuth: true },
    children: [
      // Dashboard
      {
        path: 'dashboard/basic',
        component: Basic,
        name: 'basic',
        title: 'الرئيسية',
        icon:'icon-fa icon-fa-dashboard',
      },
      {
        path: 'dashboard/permissions',
        component: permissions,
        name: 'permissions',
        title: ' الصلاحيات ',
        icon:'icon-fa icon-fa-book',        
      },
      
      
    ]
  },

  {
    path: '/admin',
    component: LayoutBasic, // Change the desired Layout here
    meta: { requiresAuth: true },
    children: [
      // Dashboard for permissions
      
      {
        path: 'dashboard/users',
        component: Users,
        name:'users',
        title: 'الاعضاء',
        icon:'icon-fa icon-fa-user',
      },

      
      
    ]
  },

  /*
   |--------------------------------------------------------------------------
   | Auth & Registration Routes
   |--------------------------------------------------------------------------|
   */

  {
    path: '/',
    component: LayoutLogin,
    children: [
      {
        path: 'login',
        component: Login,
        name: 'login'
      },
      {
        path: 'register',
        component: Register,
        name: 'register'
      }
    ]
  },

  // Demo Pages
  {
    path: '/admin/pages',
    component: LayoutLogin,
    children: [
      {
        path: 'login',
        component: Login
      },
      {
        path: 'register',
        component: Register
      }
    ]
  },

  // routes not to display
  {
    path: '/admin',
    component: LayoutBasic, // Change the desired Layout here
    meta: { requiresAuth: true },
    children: [
      // Dashboard for permissions
      
      {
        path: 'dashboard/editPermissions/:id',
        component: editPermissions,
        name:'editPermissions',
      },

      
      
    ]
  },
  
  

  //  DEFAULT ROUTE
  { path: '*', component: NotFoundPage }
]

const router = new VueRouter({
  routes,
  mode: 'history',
  linkActiveClass: 'active'
})

router.beforeEach((to, from, next) => {
  //  If the next route is requires user to be Logged IN
  if (to.matched.some(m => m.meta.requiresAuth)) {
    return AuthService.check().then(authenticated => {
      if (!authenticated) {
        return next({ path: '/login' })
      }

      return next()
    })
  }

  return next()
})

export default router
