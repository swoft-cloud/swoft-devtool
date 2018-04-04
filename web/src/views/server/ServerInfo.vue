<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-card>
      <v-card-title>
        <h3>Php Extensions</h3> <small> (total: <code>{{ extList.length }}</code>)</small>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text class="pa-2">
        <v-chip label outline v-for="name in extList" :key="name">{{ name }}</v-chip>
      </v-card-text>
    </v-card>

    <v-subheader><h3>Swoole Info</h3></v-subheader>
    <v-layout>
      <v-flex
        xs12
        sm6
      >
        <v-card>
          <v-card-title><h2>Enable Info</h2></v-card-title>
          <v-divider></v-divider>
          <v-list dense>
            <v-list-tile v-for="(item, index) in swooleInfo.enable" :key="index">
              <v-list-tile-content>{{ item.name }}</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ item.value }}</v-list-tile-content>
            </v-list-tile>
          </v-list>
        </v-card>
      </v-flex>

      <v-flex
        xs12
        sm6
      >
        <v-card>
          <v-card-title><h2>Directive Info</h2></v-card-title>
          <v-divider></v-divider>
          <v-list dense>
            <v-list-tile v-for="(item, index) in swooleInfo.directive" :key="index">
              <v-list-tile-content>{{ item.name }}</v-list-tile-content>
              <v-list-tile-content class="align-end">{{ item.value }}</v-list-tile-content>
            </v-list-tile>
          </v-list>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import {VChip} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getSwooleInfo, getPhpExtList} from '../../libs/api-services'

  export default {
    name: 'ServerInfo',
    components: {VChip, ...VCard},
    data() {
      return {
        extList: [],
        swooleInfo: []
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
        getSwooleInfo().then(({data}) => {
          this.swooleInfo = data
        })
        getPhpExtList().then(({data}) => {
          this.extList = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
