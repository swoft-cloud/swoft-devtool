<template>
      <v-layout column align-center>
        <img src="@/assets/logo.png" alt="Vuetify.js" class="mb-5">
        <blockquote>
          &#8220; logs .&#8221;
          <footer>
            <small>
              <em>&mdash;John Johnson</em>
            </small>
          </footer>
        </blockquote>
      </v-layout>
</template>

<script>
  import { ajax } from '../libs/http'

  export default {
    name: 'appLogs',
    data () {
      return {
        test: 'value'
      }
    },
    created () {
      ajax.get('/routes').then(res => {
        console.log(res)
      })
    },
    mounted () {
    },
    computed: {
    },
    methods: {

    }
  }
</script>

<style scoped>

</style>
