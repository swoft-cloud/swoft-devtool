
export default [{
  path: '/__devtool',
  name: 'Dashboard',
  component: () => import('@/views/Dashboard.vue')
}, {
  path: '/__devtool/http/routes',
  name: 'HttpRoutes',
  component: () => import('@/views/http/HttpRoutes.vue')
}, {
  path: '/__devtool/application',
  name: 'Application',
  component: () => import('@/views/Application.vue')
}, {
  path: '/__devtool/code/gen',
  name: 'Generator',
  component: () => import('@/views/gen/GenView.vue')
}, {
  path: '/__devtool/app/logs',
  name: 'AppLogs',
  component: () => import('@/views/AppLogs.vue')
}, {
  path: '/__devtool/about',
  name: 'About',
  component: () => import('@/views/pages/About.vue')
}, {
  path: '*',
  redirect: '/__devtool'
}]
