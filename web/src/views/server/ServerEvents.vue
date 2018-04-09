<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-layout row>
      <v-flex
        xs12
        md6
      >
        <v-card>
          <v-card-title class="title">Swoft Server</v-card-title>
          <v-divider></v-divider>
          <v-card-text class="pa-2">
            <div v-for="(items, name) in server" :key="name" class="px-1">
              <strong>{{ name }}</strong>
              <ul class="list px-1">
                <li v-for="item in items">
                  <code>{{ item }}</code>
                </li>
              </ul>
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex
        xs12
        md6
      >
        <v-card>
          <v-card-title class="title">Swoole Server</v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-subheader>Main Server</v-subheader>
            <v-divider></v-divider>
            <table class="table">
              <thead>
              <tr>
                <th>Name</th><th>Value</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(val, name) in swoole.server" :key="name">
                <td>{{ name }}</td>
                <td><code>{{ val }}</code></td>
              </tr>
              </tbody>
            </table>

            <v-subheader>Attached Port Server</v-subheader>
            <div v-for="(items, name) in swoole.port" :key="name" v-if="swoole.port" class="px-1">
              <h4 class="pa-1"> - Port {{ name }}</h4>
              <v-divider></v-divider>
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th><th>Value</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(val, name) in items" :key="name">
                    <td>{{ name }}</td>
                    <td><code>{{ val }}</code></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import {VDataTable} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getServerEvents} from '../../libs/api-services'

  export default {
    name: 'ServerEvents',
    components: {VDataTable, ...VCard},
    data() {
      return {
        swoole: {},
        server: {}
      }
    },
    created() {
      this.fetchList()
    },
    mounted() {
    },
    computed: {},
    methods: {
      fetchList() {
        getServerEvents().then(({data}) => {
          console.log(data)
          this.swoole = data.swoole
          this.server = data.server
        })
      }
    }
  }
</script>

<style scoped>

</style>
