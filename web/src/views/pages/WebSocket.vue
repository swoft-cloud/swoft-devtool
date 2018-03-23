<template>
  <v-layout row wrap>
    <v-flex xs12 md4>
      <v-layout row wrap>
        <v-flex xs7>
          <v-text-field
            name="wsUrl"
            :label="'eg ' + locWsUrl"
            single-line
            required
            prepend-icon="language"
            v-model="wsUrl"
            hint="websocket url. eg wss://echo.websocket.org/"
            persistent-hint
          ></v-text-field>
        </v-flex>
        <v-flex xs5>
          <v-btn :disabled="wsUrlIsEmpty" @click="connect" color="info">
            <v-icon>link</v-icon> Connect
          </v-btn>
          <v-btn :disabled="!isConnected" @click="disconnect" color="warning">
            <v-icon>do_not_disturb</v-icon> Disconnect
          </v-btn>
        </v-flex>
        <v-alert :value="true" color="error" icon="warning" v-show="errorMsg">
          <v-icon>error</v-icon> {{errorMsg}}
        </v-alert>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card color="grey lighten-4" flat>
            <v-card-text>
              <v-text-field
                name="message"
                label="Message"
                textarea
                v-model="message"
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green" @click="send"><v-icon>send</v-icon> Send</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>

    <v-flex d-flex xs12 md8>
      <v-card color="grey lighten-4" flat>
        <v-card-text>
          <v-subheader><v-icon>textsms</v-icon> Message Box</v-subheader>
          <v-container fluid fill-height>
            <v-layout row fill-height>
              <v-flex xs12 v-for="item in messages" key="item.date">
                {{item.msg}}
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon>delete</v-icon>
          </v-btn>
          <v-btn icon>
            <v-icon>bookmark</v-icon>
          </v-btn>
          <v-btn icon>
            <v-icon>share</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
  import {VAlert, VSubheader} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import Util from '../../libs/util'

  // ws instance
  let ws = null

  export default {
    name: 'web-socket',
    components: {VAlert, VSubheader, ...VCard},
    data() {
      return {
        ws: null,
        wsUrl: '',
        locWsUrl: '',
        errorMsg: '',
        message: '',
        messages: [],
        urlHistories: [],
        defaultUrls: [
          'wss://echo.websocket.org/'
        ],
        btnSts: {
          connect: true,
          send: false
        }
      }
    },
    created() {
      let proto = window.location.protocol
      let wsProto = proto.indexOf('s:') > 1 ? 'wss://' : 'ws://'
      let locWsUrl = wsProto + window.location.host

      this.wsUrl = 'wss://echo.websocket.org/'
      this.locWsUrl = locWsUrl
      this.defaultUrls.push(locWsUrl)
    },
    mounted() {
    },
    computed: {
      wsUrlIsEmpty () {
        return this.wsUrl === ''
      },
      isConnected () {
        return this.ws !== null
      }
    },
    methods: {
      connect() {
        let app = this
        let timer

        app.errorMsg = ''

        ws = new WebSocket(this.wsUrl)
        ws.onerror = function error(e) {
          console.log('connect failed!')
          app.errorMsg = 'connect failed! server url:' + app.wsUrl
        }

        ws.onopen = function open() {
          console.log('connected')

          // send Heartbeat
          timer = setTimeout(function () {
            ws.send('@heartbeat')
          }, 20000)
        }

        ws.onmessage = function incoming(data) {
          app.saveMessage(data, 2)
        }

        ws.onclose = function close() {
          console.log('disconnected')

          clearTimeout(timer)
          app.ws = null
        }
      },
      disconnect() {
        if (ws) {
          ws.close()
        }
      },
      send() {
        let msg = Util.trim(this.message)

        if (!msg) {
          this.errorMsg = 'the message cannot be empty'
        }

        if (!ws) {
          this.errorMsg = 'please connect to websocket server before send message!'
        }

        ws.send(msg)
      },
      saveMessage(msg, type = 1) {
        this.messages.push({
          type: type,
          msg: msg,
          date: Util.formatDate.format(new Date(), 'yyyy-MM-dd hh:mm:ss')
        })
      },
      clearMessages () {
        this.messages = []
      }
    }
  }
</script>

<style scoped>

</style>
