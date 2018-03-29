<template>
  <v-layout column align-center>
    <img src="@/assets/swoft-logo-text.png" alt="swoft" class="mb-5">
    <blockquote>
      Modern High performance AOP and Coroutine PHP Framework, base on Swoole 2
    </blockquote>

    <h2>Back-end technology</h2>
    <div>
      swoft
    </div>

    <h2>Front-end technology</h2>
    <div>
      Vue
      Vuetify
      webpack
    </div>
    <v-data-table
      :headers="headers"
      :items="fItems"
      hide-actions
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.name }}</td>
        <td class="text-xs-center">{{ props.item.fat }}</td>
      </template>
    </v-data-table>
  </v-layout>
</template>

<script>
  import {VDataTable} from 'vuetify'

  export default {
    name: 'about',
    data () {
      return {
        headers: [
          {
            text: 'Name',
            align: 'center',
            sortable: false,
            value: 'name'
          },
          {text: 'Information', value: 'info'}
        ],
        fItems: [{
          name: 'Vue',
          github: 'https://github.com/vue/vue',
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          iron: '1%'
        }]
      }
    }
  }
</script>

<style scoped>

</style>
