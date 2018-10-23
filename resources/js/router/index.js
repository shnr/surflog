import Vue from 'vue'
import Router from 'vue-router'

import NotFoundComponent from '../components/NotFoundComponent'
import LoginComponent from '../components/LoginComponent'
import RegisterComponent from '../components/RegisterComponent'

import ConditionIndexComponent from '../components/ConditionIndexComponent'
import ConditionViewComponent from '../components/ConditionViewComponent'
import ConditionCreateComponent from '../components/ConditionCreateComponent'
import ConditionEditComponent from '../components/ConditionEditComponent'
// import TaskIndexComponent from '../components/TaskIndexComponent'
// import TaskViewComponent from '../components/TaskViewComponent'
// import TaskCreateComponent from '../components/TaskCreateComponent'
// import TaskEditComponent from '../components/TaskEditComponent'

Vue.use(Router)

const router = new Router({
  // mode: 'history',
  base: '/',
  routes: [
    { path: '*', component: NotFoundComponent },
    {
      path: '/',
      name: 'ConditionIndex',
      component: ConditionIndexComponent
    },
    {
      path: '/login',
      name: 'Login',
      component: LoginComponent
    },
    {
      path: '/register',
      name: 'Register',
      component: RegisterComponent
    },
    {
      path: '/create',
      name: 'ConditionCreate',
      component: ConditionCreateComponent
    },
    {
      path: '/view/:id(\\d+)',
      name: 'ConditionView',
      component: ConditionViewComponent,
    },
    {
      path: '/edit/:id(\\d+)',
      name: 'ConditionEdit',
      component: ConditionEditComponent,
    }
  ]
})

// router.beforeEach((to, from, next) => {
//   next()
// })
// router.afterEach((to, from) => {
// })

export default router
