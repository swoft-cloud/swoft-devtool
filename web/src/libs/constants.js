import { cache } from './util'

// lang order: localStorage -> browser language -> default
export const LANG = cache.get('site.lang') || navigator.language || 'zh-CN'

export const ACCESS_TOKEN = 'test'
