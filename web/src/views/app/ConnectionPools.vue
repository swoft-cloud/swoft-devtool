<template>
  <v-layout row wrap>
    <v-flex xs12>
      <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    </v-flex>
    <v-flex xs12>
      <v-card>
        <simple-table class="table-bordered">
          <template slot="header">
            <th> Pool Name </th>
            <th> Pool Class</th>
            <th> Operation</th>
          </template>
          <tr v-for="(val, name) in pools" :key="name">
            <td>{{ name }}</td>
            <td><code>{{ val }}</code></td>
            <td class="text-xs-center">
              <v-btn small outline color="blue" @click="fetchPoolConfig(name)">
                view &nbsp;<v-icon>remove_red_eye</v-icon>
              </v-btn>
            </td>
          </tr>
        </simple-table>
      </v-card>
    </v-flex>
    <v-flex xs12>
      <v-card color="yellow lighten-5" class="pa-3">
        <tree-view :data="dataMap" :options="{maxDepth: 3, rootObjectKey: 'Config'}"></tree-view>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getAppPools} from '../../libs/api-services'
  import SimpleTable from '../parts/SimpleTable'

  export default {
    name: 'connection-pools',
    components: {SimpleTable, ...VCard},
    data() {
      return {
        dataMap: {
          tips: 'Please select a pool to see config!'
        },
        pools: {}
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
        getAppPools().then(({data}) => {
          this.pools = data

          console.log(data)
        })
      },
      fetchPoolConfig (name) {
        getAppPools(name).then(({data}) => {
          this.dataMap = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
