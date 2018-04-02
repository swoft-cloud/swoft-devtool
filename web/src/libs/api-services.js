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

// 获取 basic 信息
export const getBasicInfo = () => ajax.get('/app/info')

// 获取 app config 信息
export const getAppConfig = () => ajax.get('/app/config')

// 获取 server config 信息
export const getServerConfig = (type = 'all') => ajax.get('/server/config', {type})

// 获取http routes信息
export const getHttpRoutes = (type = 'all') => ajax.get('/http/routes', {type})
