import Vue from 'vue';
import Router from 'vue-router';

import Layout from '@/components/Layout/Layout';
import Admin from '@/components/Admin/Admin';

import Sources from 'resources/js/pages/Admin/Sources/Sources';
import SourcesCategory from 'resources/js/pages/Admin/SourcesCategory/SourcesCategory';


import Dashboard from 'resources/js/pages/Dashboard/Dashboard';
import Login from 'resources/js/pages/Login/Login';
import ErrorPage from 'resources/js/pages/Error/Error';
import Reset from 'resources/js/pages/Reset/Reset';
import Forgot from 'resources/js/pages/Forgot/Forgot';
import Register from '@/pages/Join/Join';
import { isAuthenticated } from 'resources/js/mixins/auth';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    { path: '/', redirect: '/app/dashboard' },

    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
    {
      path: '/forgot',
      name: 'Forgot',
      component: Forgot,
    },
    {
      path: '/reset',
      name: 'Reset',
      component: Reset,
    },
    {
      path: '/error',
      name: 'Error',
      component: ErrorPage,
    },
    {
      path: '/admin',
      name: 'Layout',
      component: Admin,
      beforeEnter: (to, from, next) => {
        // let authenticated = localStorage.getItem('authenticated');
        let authenticated = true;
        isAuthenticated(authenticated) ? next() : next({ path: '/login' });
      },
      children: [
        // main page
        {
          path: 'sources',
          name: 'Sources',
          component: Sources,
        },
        {
            path: 'sources_category',
            name: 'SourcesCategory',
            component: SourcesCategory,
          },
      ],
    },
    {
      path: '/app',
      name: 'Layout',
      component: Layout,
      beforeEnter: (to, from, next) => {
        // let authenticated = localStorage.getItem('authenticated');
        let authenticated = true;
        isAuthenticated(authenticated) ? next() : next({ path: '/login' });
      },
      children: [
        // main page
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
        },
      ],
    },
  ],
});
