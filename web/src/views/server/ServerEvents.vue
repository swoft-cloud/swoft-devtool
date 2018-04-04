<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-layout row>
      <v-flex
        xs12
        md6
      >
        <v-card>
          <div class="pa-2">
            <h3>Swoft Server</h3>
            <div v-for="(items, name) in server" :key="name" class="px-1">
              <strong>{{ name }}</strong>
              <ul class="list px-1">
                <li v-for="item in items">
                  <code>{{ item }}</code>
                </li>
              </ul>
            </div>
          </div>
        </v-card>
      </v-flex>
      <v-flex
        xs12
        md6
      >
        <v-card class="pa-2">
          <h3>Swoole Server</h3>
          <ul class="list">
            <li v-for="(item, name) in swoole.server" :key="name" v-if="swoole.server">
              {{ name }} - <code>{{ item }}</code>
            </li>
          </ul>

          <h3>Swoole Port</h3>
          <div v-for="(items, name) in swoole.port" :key="name" v-if="swoole.port" class="px-1">
            <strong>port {{ name }}</strong>
            <ul class="list px-1">
              <li v-for="(item, key) in items">
                {{ key }} - <code>{{ item }}</code>
              </li>
            </ul>
          </div>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getServerEvents} from '../../libs/api-services'

  export default {
    name: 'ServerEvents',
    components: {...VCard},
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
