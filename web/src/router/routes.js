import { URI_PREFIX } from '../libs/constants'

export default [{
  path: URI_PREFIX,
  name: 'Dashboard',
  component: () => import('@/views/Dashboard.vue')
}, {
  path: URI_PREFIX + '/http/routes',
  name: 'HttpRoutes',
  component: () => import('@/views/http/HttpRoutes.vue')
}, {
  path: URI_PREFIX + '/application',
  name: 'Application',
  component: () => import('@/views/Application.vue')
}, {
  path: URI_PREFIX + '/ws/test',
  name: 'WebSocket',
  component: () => import('@/views/pages/WebSocket.vue')
}, {
  path: URI_PREFIX + '/code/gen',
  name: 'Generator',
  component: () => import('@/views/gen/GenView.vue')
}, {
  path: URI_PREFIX + '/app/logs',
  name: 'AppLogs',
  component: () => import('@/views/AppLogs.vue')
}, {
  path: URI_PREFIX + '/about',
  name: 'About',
  component: () => import('@/views/pages/About.vue')
}, {
  path: '*',
  redirect: URI_PREFIX
}]
