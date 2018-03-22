
export default [{
  path: '/__devtool',
  name: 'Dashboard',
  component: () => import('@/views/Dashboard.vue')
}, {
  path: '/__devtool/http/routes',
  name: 'HttpRoutes',
  component: () => import('@/views/HttpRoutes.vue')
}, {
  path: '/__devtool/app/logs',
  name: 'AppLogs',
  component: () => import('@/views/AppLogs.vue')
}, {
  path: '*',
  redirect: '/__devtool'
}]
