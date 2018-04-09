<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <simple-table>
      <template slot="header">
        <th> Number </th>
        <th> Middleware Class</th>
      </template>
      <tr v-for="(val, index) in middles" :key="index">
        <td>{{ index }}</td>
        <td><code>{{ val }}</code></td>
      </tr>
    </simple-table>
  </div>
</template>

<script>
  import SimpleTable from '../parts/SimpleTable'
  import {getRpcMiddles} from '../../libs/api-services'

  export default {
    name: 'http-middleware',
    components: {SimpleTable},
    data: () => ({
      middles: []
    }),
    created() {
      this.fetchList()
    },
    mounted() {
    },
    computed: {},
    methods: {
      fetchList() {
        getRpcMiddles().then(({data}) => {
          console.log(data)

          this.middles = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
