<template>
  <div>
    <v-jumbotron color="light-green darken-1" dark>
      <v-container fill-height>
        <v-layout align-center>
          <v-flex>
            <h3 class="display-3">Welcome to the DevTool</h3>
            <span class="subheading">Lorem ipsum dolor sit amet, pri veniam forensibus id. Vis maluisset molestiae id, ad semper lobortis cum. At impetus detraxit incorrupte usu, repudiare assueverit ex eum, ne nam essent vocent admodum.</span>
            <v-divider class="my-3"></v-divider>
            <div class="title mb-3">Check out our newest features!</div>
            <v-btn large color="primary" class="mx-0" :href="github" target="_blank">Github</v-btn>
            <v-btn large color="success" class="mx-0" :href="document" target="_blank">Document</v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </v-jumbotron>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">
        Application
      </v-flex>
      <v-flex d-flex xs12 sm6 md4>
        <v-card>
          <v-card-media src="@/assets/swoft-logo-text.png">
          </v-card-media>
          <v-card-title primary class="title">Swoft</v-card-title>
        </v-card>
      </v-flex>
      <v-flex d-flex xs12 sm6 md3>
        <v-layout row wrap>
          <v-flex d-flex>
            <v-card :color="randomColor()" dark>
              <v-card-title>
                Application information
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn outline :to="buildRoute('/app/info')">View</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
          <v-flex d-flex>
            <v-layout row wrap>
              <v-flex d-flex v-for="n in 2" :key="n" xs12>
                <v-card color="red lighten-2" dark>
                  <v-card-text
                    v-text="lorem.slice(0, 40)">
                  </v-card-text>
                </v-card>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex d-flex xs12 sm6 md2 child-flex>
        <v-card color="green lighten-2" dark>
          <v-card-title primary-title><h4 class="mb-0">Information</h4></v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn outline :to="buildRoute('/app/info')">View</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
      <v-flex d-flex xs12 sm6 md3>
        <v-card color="blue lighten-2" dark>
          <v-card-text
            v-text="lorem.slice(0, 100)">
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">Server</v-flex>
      <v-flex d-flex xs6 sm4 md3 xl2 v-for="item in server" :key="item.href">
        <v-card :color="randomColor()" dark>
          <v-card-title primary-title><h4 class="mb-0">{{ item.title }}</h4></v-card-title>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn outline :to="item.href">View</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>

    <v-layout row wrap>
      <v-flex d-flex xs12 tag="h2" class="headline">Tools</v-flex>
    </v-layout>

  </div>
</template>

<script>
  import {URI_PREFIX} from '../libs/constants'
  import {VJumbotron} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'

  export default {
    name: 'dashboard',
    components: {VJumbotron, ...VCard},
    data: () => ({
      uriPrefix: URI_PREFIX,
      github: 'https://github.com/swoft-cloud/swoft',
      document: 'https://doc.swoft.org',
      server: [{
        title: 'Information',
        href: URI_PREFIX + '/server/info'
      }, {
        title: 'Configuration',
        href: URI_PREFIX + '/server/config'
      }],
      colors: [
        'indigo',
        'orange',
        'purple',
        'green lighten-2',
        'blue lighten-2'
      ],
      lorem: `Lorem ipsum dolor sit amet, mel at clita quando. Te sit oratio vituperatoribus, nam ad ipsum posidonium mediocritatem, explicari dissentiunt cu mea. Repudiare disputationi vim in, mollis iriure nec cu, alienum argumentum ius ad. Pri eu justo aeque torquatos.`
    }),
    computed: {
    },
    methods: {
      randomColor () {
        let index = Math.floor(Math.random() * this.colors.length)

        return this.colors[index]
      },
      buildRoute (route) {
        return this.uriPrefix + route
      }
    }
  }
</script>

<style scoped>

</style>
