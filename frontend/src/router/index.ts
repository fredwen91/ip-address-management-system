import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LoginView from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      alias: '/',
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true, hideNavbar: true },
    },
    {
      path: '/ip-address',
      name: 'ipAddress',
      component: () => import('../views/IpAddress.vue'),
      meta: { requiresAuth: true, title: 'IP Address' },
    },
    {
      path: '/test',
      name: 'test',
      component: () => import('../views/Test.vue'),
      meta: { requiresAuth: true, title: 'Test' },
    },
    {
      path: '/:catchAll(.*)*',
      name: 'pageNotFound',
      component: () => import('../views/PageNotFound.vue'),
    },
  ],
})

router.beforeEach((to) => {
  const { isAuthenticated } = useAuthStore()

  if (to.meta.guest && isAuthenticated) {
    return { name: 'ipAddress' }
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'login' }
  }

  return true
})

export default router
