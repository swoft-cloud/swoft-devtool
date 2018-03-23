<template>
  <v-slide-y-transition mode="out-in">
    <div>
      <v-card>
        <v-card-title>
          <h2>Static Routes</h2>
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="search"
            label="Search"
            single-line
            hide-details
            v-model="stSearch"
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="stHeaders"
          :items="staticList"
          :search="stSearch"
          :rows-per-page-items="pageOpts"
          class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.path }}</td>
            <td>{{ props.item.method }}</td>
            <td><code>{{ props.item.handler }}</code></td>
          </template>
          <template slot="no-data">
            <v-alert :value="true" color="info" icon="info">
              Sorry, nothing to display here :(
            </v-alert>
          </template>
          <template slot="footer">
            <td colspan="100%">
              <strong>This is an extra footer</strong>
            </td>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="warning">
            Your search for "{{ stSearch }}" found no results.
          </v-alert>
        </v-data-table>
      </v-card>
      <v-divider></v-divider>
      <v-card>
        <v-card-title>
          <h2>Regular Routes</h2>
          <v-spacer></v-spacer>
          <v-text-field
            append-icon="search"
            label="Search"
            single-line
            hide-details
            v-model="rgSearch"
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="rgHeaders"
          :items="regularList"
          :search="rgSearch"
          :rows-per-page-items="pageOpts"
          class="elevation-1"
        >
          <template slot="items" slot-scope="props">
            <td>{{ props.item.original }}</td>
            <td><code>{{ props.item.regex }}</code></td>
            <td>{{ props.item.methods }}</td>
            <td><code>{{ props.item.handler }}</code></td>
          </template>
          <template slot="no-data">
            <v-alert :value="true" color="info" icon="info">
              Sorry, nothing to display here :(
            </v-alert>
          </template>
          <template slot="footer">
            <td colspan="100%">
              <strong>This is an extra footer</strong>
            </td>
          </template>
          <v-alert slot="no-results" :value="true" color="error" icon="warning">
            Your search for "{{ stSearch }}" found no results.
          </v-alert>
        </v-data-table>
      </v-card>
    </div>
  </v-slide-y-transition>
</template>

<script>
  import {VAlert, VDataTable} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {getHttpRoutes} from '@/libs/api-services'

  function formatStaticRoutes(routes, app) {
    for (let path in routes) {
      let val = routes[path]

      for (let method in val) {
        let item = {
          path: path
        }

        item.method = method
        item.handler = val[method].handler
        app.staticList.push(item)
      }
    }
  }

  function formatRegularRoutes(routes, app) {
    for (let fstNode in routes) {
      let items = routes[fstNode]

      for (let k in items) {
        let methods = items[k].methods

        app.regularList.push({
          first: fstNode,
          methods: methods.substr(0, methods.length - 1),
          regex: items[k].regex,
          original: items[k].original,
          handler: items[k].handler,
          option: items[k].option
        })
      }
    }
  }

  export default {
    name: 'httpRoutes',
    components: {VAlert, ...VCard, VDataTable},
    data() {
      return {
        stSearch: '',
        rgSearch: '',
        rawData: [],

        // table settings
        pageOpts: [10, 25, {'text': 'All', 'value': -1}],

        // table headers
        stHeaders: [{
          text: 'Uri Path',
          align: 'left',
          sortable: false,
          value: 'path'
        }, {
          text: 'Method',
          align: 'left',
          value: 'method'
        }, {
          text: 'Route Handler',
          align: 'left',
          value: 'handler'
        }],
        rgHeaders: [{
          text: 'Uri Pattern',
          sortable: false,
          value: 'original'
        }, {
          text: 'Uri Regex',
          sortable: false,
          value: 'regex'
        }, {
          text: 'Allowed Methods',
          value: 'methods'
        }, {
          text: 'Route Handler',
          value: 'handler'
        }],

        // data list
        staticList: [],
        cacheList: [],
        regularList: [],
        vagueList: []
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
        getHttpRoutes().then(({data}) => {
          console.log(data)
          this.rawData = data

          formatStaticRoutes(data.static, this)
          formatRegularRoutes(data.regular, this)

          this.cacheList = data.cached
          this.vagueList = data.vague
        })
      }
    }
  }
</script>

<style scoped>

</style>
