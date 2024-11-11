import { createRouter, createWebHistory } from 'vue-router';
import AttendanceView from '@/views/AttendanceView.vue';
import ClearanceView from '@/views/ClearanceView.vue';
import HomeView from '@/views/HomeView.vue';
import StdntHomeView from '@/views/StdntHomeView.vue';
import StdntClearanceView from '@/views/StdntClearanceView.vue';
import StdntAttendanceView from '@/views/StdntAttendanceView.vue';
import Login from '@/views/Login.vue';
import SignUp from '@/views/SignUp.vue';

const routes = [
  {
    path: '/',
    redirect: '/login',
    meta: { layout: 'empty' },
  },
  {
    path: '/Home',
    name: 'Home',
    component: HomeView,
    meta: { requiresAuth: true },
  },
  {
    path: '/attendance',
    name: 'Attendance',
    component: AttendanceView,
    meta: { requiresAuth: true },
  },
  {
    path: '/clearance',
    name: 'Clearance',
    component: ClearanceView,
    meta: { requiresAuth: true },
  },
  {
    path: '/StudentHome',
    name: 'StudentHome',
    component: StdntHomeView,
    meta: { requiresAuth: true },
  },
  {
    path: '/StudentClearance',
    name: 'StudentClearance',
    component: StdntClearanceView,
    meta: { requiresAuth: true },
  },
  {
    path: '/StudentAttendance',
    name: 'StudentAttendance',
    component: StdntAttendanceView,
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true },
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUp,
    meta: { guestOnly: true, layout: 'empty' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard to handle authentication checks
router.beforeEach((to, from) => {
  const isAuthenticated = !!localStorage.getItem('jwtToken'); // Check if JWT token exists

  // If the route requires authentication and user is not authenticated, redirect to login
  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'Login' }; // Redirect to login
  }

  // If the route is for guests only and the user is authenticated, redirect to the home page
  if (to.meta.guestOnly && isAuthenticated) {
    return { name: 'Home' }; // Redirect to home
  }

  // Explicitly returning true to continue navigation
  return true;
});

// Prevent going back to login after authentication
window.addEventListener('popstate', () => {
  const isAuthenticated = !!localStorage.getItem('jwtToken');
  if (isAuthenticated && (router.currentRoute.value.name === 'Login' || router.currentRoute.value.name === 'SignUp')) {
    router.push({ name: 'Home' });
  }
});

export default router;
