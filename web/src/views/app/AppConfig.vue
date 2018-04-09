<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-layout row wrap>
      <v-flex xs12 md4>
        <v-card>
          <v-card-text>
            get config: <code>\bean('config')->get(key, default = null)</code>
          </v-card-text>
        </v-card>
      </v-flex>
      <v-flex xs12 md8>
        <v-card color="amber lighten-5" class="pa-3">
          <tree-view :data="dataMap" :options="{maxDepth: 2, rootObjectKey: 'config'}"></tree-view>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import {VAlert} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getAppConfig} from '../../libs/api-services'

  export default {
    name: 'app-config',
    components: {VAlert, ...VCard},
    data() {
      return {
        dataMap: {}
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
        getAppConfig().then(({data}) => {
          this.dataMap = data

          console.log(data)
        })
      }
    }
  }
</script>

<style>
  .tree-view-item-key {
  }

  .tree-view-item-value {
    color: #ad1457;
  }

  .tree-view-item-key-with-chevron {

  }
</style>
