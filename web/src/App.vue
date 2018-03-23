<template>
  <v-app id="app-wrapper">
    <!-- left sidebar -->
    <v-navigation-drawer
      persistent
      :mini-variant="miniVariant"
      :clipped="clipped"
      v-model="drawer"
      enable-resize-watcher
      fixed
      app
    >
      <v-toolbar flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile avatar>
            <v-list-tile-avatar>
              <img src="@/assets/swoft-logo-sm.png"  alt="logo">
            </v-list-tile-avatar>
            <v-list-tile-content>
              Swoft Dev
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-divider></v-divider>
      <v-list>
        <v-list-tile
          value="true"
          v-for="(item, i) in items"
          :key="i"
          :to="uriPrefix + item.href"
          exact
        >
          <v-list-tile-action>
            <v-icon v-html="item.icon"></v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title v-text="item.title"></v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>

    <!-- top menu -->
    <v-toolbar
      app
      color="blue-grey lighten-5"
      :clipped-left="clipped"
    >
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon v-html="miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
      </v-btn>
      <!--<v-btn icon @click.stop="fixed = !fixed">-->
        <!--<v-icon>remove</v-icon>-->
      <!--</v-btn>-->
      <v-toolbar-title v-text="title"></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon @click.stop="clipped = !clipped">
        <v-icon>web</v-icon>
      </v-btn>
      <v-btn icon @click.stop="rightDrawer = !rightDrawer">
        <v-icon>menu</v-icon>
      </v-btn>
    </v-toolbar>

    <!-- content -->
    <v-content>
      <v-container :fluid="false" grid-list-md>
        <v-breadcrumbs>
          <v-icon slot="divider">chevron_right</v-icon>
          <v-breadcrumbs-item
            v-for="item in bcItems"
            :key="item.text"
            :disabled="item.disabled"
          >
            {{ item.text }}
          </v-breadcrumbs-item>
        </v-breadcrumbs>

        <v-slide-y-transition mode="out-in">
          <router-view></router-view>
        </v-slide-y-transition>

      </v-container>
      <!--footer-->
      <app-footer></app-footer>
    </v-content>

    <!--right-->
    <v-navigation-drawer
      temporary
      right
      v-model="rightDrawer"
      fixed
      app
    >
      <v-list>
        <v-list-tile @click="right = !right">
          <v-list-tile-action>
            <v-icon>compare_arrows</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>Switch drawer (click me)</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>

    <n-progress parent="#app-wrapper"></n-progress>
  </v-app>
</template>

<script>
  import sidebar from './libs/sidebar'
  import {URI_PREFIX} from './libs/constants'
  import NProgress from './views/base/NProgress'
  import AppFooter from './views/parts/AppFooter'
  import * as VBreadcrumbs from 'vuetify/es5/components/VBreadcrumbs'

  export default {
    components: {AppFooter, NProgress, ...VBreadcrumbs},
    data() {
      return {
        clipped: false,
        drawer: true,
        fixed: false,
        uriPrefix: URI_PREFIX,
        items: sidebar,
        miniVariant: false,
        right: true,
        rightDrawer: false,
        title: 'DevTool',
        swoft: {
          officialUrl: 'https://swoft.org',
          githubUrl: 'https://github.com/swoft-cloud/swoft',
          issueUrl: 'https://github.com/swoft-cloud/swoft/issues'
        },
        bcItems: [{
          text: 'Dashboard',
          disabled: false
        }, {
          text: 'Page',
          disabled: true
        }],
        gotoOpts: {
          duration: 300,
          offset: 0,
          easing: 'easeInOutCubic'
        }
      }
    },
    name: 'App'
  }
</script>
