import { ajax } from './http'

// 获取图片验证码
export const getCaptcha = () => ajax('/util/captcha')

// 账号密码登录
export const accountLogin = (username, password, captcha) => ajax.post('/v1/auth/login', {username, password, captcha})

// 刷新用户token
export const refreshToken = () => ajax.post('/v1/auth/refreshToken')

// 退出登录
export const logout = () => ajax('/v1/auth/logout')

// 获取当前登录用户信息
export const getLoggedUser = () => ajax.get('/v1/users/me')

// 获取用户信息
export const getUserInfo = (id) => ajax.get('/v1/users/' + id)

/*
 * Application
 */

// get basic info
export const getBasicInfo = () => ajax.get('/app/info')

// get app config
export const getAppConfig = () => ajax.get('/app/config')

// get App Events
export const getAppEvents = (name = '') => ajax.get('/app/events?name=' + name)

/*
 * Server
 */

// get server config
export const getServerConfig = (type = 'all') => ajax.get('/server/config', {type})

// get server stats
export const getServerStats = (type = 'all') => ajax.get('/server/stats', {type})

// get swoole info
export const getSwooleInfo = (type = 'all') => ajax.get('/server/swoole-info', {type})

// get server php ext list
export const getPhpExtList = () => ajax.get('/server/php-ext-list')

// get http routes
export const getHttpRoutes = (type = 'all') => ajax.get('/http/routes', {type})

// get ws routes
export const getWsRoutes = () => ajax.get('/ws/routes')

// get rpc routes
export const getRpcRoutes = () => ajax.get('/rpc/routes')
