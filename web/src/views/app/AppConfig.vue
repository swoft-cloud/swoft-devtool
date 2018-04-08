<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-layout row wrap>
      <v-flex xs12
              md4>
        hello
      </v-flex>
      <v-flex xs12
              md8>
        <v-card color="lighten-2">
          <v-card-text>
            <tree-view :data="dataMap" :options="{maxDepth: 2}"></tree-view>
          </v-card-text>
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

<style scoped>

</style>
