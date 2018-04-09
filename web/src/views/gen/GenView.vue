<template>
  <div>
    <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>
    <v-layout row wrap>
      <v-flex d-flex xs12 md4>
        <v-card>
          <v-container>
            <v-form v-model="valid" ref="form" lazy-validation>
              <v-text-field
                label="Class Name"
                v-model="name"
                :rules="nameRules"
                :counter="10"
                hint="The class name, don't need suffix and ext(eg. demo)"
                persistent-hint
                required
              ></v-text-field>
              <v-text-field
                label="E-mail"
                v-model="email"
                :rules="emailRules"
                required
              ></v-text-field>
              <v-select
                label="Item"
                v-model="select"
                :items="items"
                :rules="[v => !!v || 'Item is required']"
                required
              ></v-select>
              <v-checkbox
                label="Do you agree?"
                v-model="checkbox"
                :rules="[v => !!v || 'You must agree to continue!']"
                required
              ></v-checkbox>

              <v-btn
                @click="submit"
                :disabled="!valid"
              >
                submit
              </v-btn>
              <v-btn @click="clear">clear</v-btn>
            </v-form>
          </v-container>
        </v-card>

      </v-flex>
      <v-flex d-flex xs12 md8>
        <v-card>
          <v-card-title primary-title>
            <div>
              <div class="headline">Top western road trips</div>
              <span class="grey--text">1,000 miles of wonder</span>
            </div>
          </v-card-title>
          <v-card-actions>
            <v-btn flat>Share</v-btn>
            <v-btn flat color="purple">Explore</v-btn>
            <v-spacer></v-spacer>
            <v-btn flat @click.native="show = !show">
              View <v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
            </v-btn>
          </v-card-actions>
          <v-slide-y-transition>
            <v-card-text v-show="show">
              escape.
            </v-card-text>
          </v-slide-y-transition>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import axios from 'axios'
  import * as VCard from 'vuetify/es5/components/VCard'
  import {VForm, VCheckbox, VSelect} from 'vuetify'

  export default {
    name: 'GenView',
    components: {VForm, VCheckbox, VSelect, ...VCard},
    data: () => ({
      show: false,
      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters'
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
      ],
      select: null,
      items: [
        'Item 1',
        'Item 2',
        'Item 3',
        'Item 4'
      ],
      checkbox: false
    }),

    methods: {
      submit () {
        if (this.$refs.form.validate()) {
          // Native form submission is not yet supported
          axios.post('/api/submit', {
            name: this.name,
            email: this.email,
            select: this.select,
            checkbox: this.checkbox
          })
        }
      },
      clear () {
        this.$refs.form.reset()
      }
    }
  }
</script>

<style scoped>

</style>
