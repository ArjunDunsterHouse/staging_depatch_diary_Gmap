import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import BootstrapVue3 from 'bootstrap-vue-3'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css'
import '@formkit/themes/genesis'

import App from './app.vue';
import { plugin, defaultConfig } from '@formkit/vue';
import routes from './router/index.js';

import VueLoading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

import { VueCookieNext } from 'vue-cookie-next';

import * as controller from './global.js';

VueCookieNext.config({ expire: '7d' })

// set global cookie
//VueCookieNext.setCookie('last_refresh_value', '')
VueCookieNext.setCookie('last_refresh_value', '', { path: '/' });

VueCookieNext.setCookie('controller','');
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

//let controller='';
const app=createApp(App).use(controller).use(BootstrapVue3).use(plugin, defaultConfig).use(routes).use(pinia).use(VueLoading).use(VueCookieNext);

//app.config.globalProperties.$controller='';
app.mount('#app');