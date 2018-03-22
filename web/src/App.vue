<template>
  <v-app>
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
      <v-toolbar flat>
        <v-list>
          <v-list-tile>
            <v-list-tile-title class="title">
              Application
            </v-list-tile-title>
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
      :clipped-left="clipped"
    >
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon v-html="miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
      </v-btn>
      <v-btn icon @click.stop="clipped = !clipped">
        <v-icon>web</v-icon>
      </v-btn>
      <v-btn icon @click.stop="fixed = !fixed">
        <v-icon>remove</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title"></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon @click.stop="rightDrawer = !rightDrawer">
        <v-icon>menu</v-icon>
      </v-btn>
    </v-toolbar>

    <v-content>
      <v-container :fluid="false">
        <router-view></router-view>
      </v-container>
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
    <v-footer :fixed="fixed" insert app>
      <span>&copy; 2018</span>
      <a target="_blank" :href="swoft.githubUrl"> Github</a>
      <a target="_blank" :href="swoft.issueUrl"><v-icon>bug_report</v-icon> Issues</a>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      clipped: false,
      drawer: true,
      fixed: false,
      uriPrefix: '/__devtool',
      items: [{
        icon: 'dashboard',
        title: 'Dashboard',
        href: '/'
      }, {
        icon: 'list',
        title: 'Routes',
        href: '/http/routes'
      }, {
        icon: 'insert_drive_file',
        title: 'Logs',
        href: '/app/logs'
      }],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Swoft DEV',
      swoft: {
        githubUrl: 'https://github.com/swoft-cloud/swoft',
        issueUrl: 'https://github.com/swoft-cloud/swoft/issues'
      }
    }
  },
  name: 'App'
}
</script>
