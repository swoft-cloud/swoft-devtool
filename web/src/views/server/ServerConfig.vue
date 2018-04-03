<template>

</template>

<script>
  import {getServerConfig} from '../../libs/api-services'

  export default {
    name: 'ServerInfo',
    data() {
      return {
        items: []
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
        getServerConfig().then(({data}) => {
          console.log(data)
          this.items = data
        })
      }
    }
  }
</script>

<style scoped>

</style>
