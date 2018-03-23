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
          <v-btn :disabled="wsUrlIsEmpty" @click="connect" color="info" class="px-2">
            Connect
          </v-btn>
          <v-btn :disabled="!isConnected" @click="disconnect" color="warning">
            Disconnect
          </v-btn>
        </v-flex>
        <v-alert :value="true" color="error" icon="warning" v-show="errorMsg">
          {{errorMsg}}
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
              <v-btn color="green" @click="send">
                <v-icon>send</v-icon>
                Send
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>

    <v-flex d-flex xs12 md8>
      <v-card color="grey lighten-4" flat>
        <v-card-text>
          <v-subheader>
            <v-icon>textsms</v-icon>
            Message Box
          </v-subheader>
          <v-container fluid>
            <v-layout row wrap v-for="(item, idx) in messages" :key="idx">
              <v-flex xs12>
                <v-avatar size="30px" class="teal" v-if="item.type === 1">
                  <span class="white--text headline">C</span>
                </v-avatar>
                <v-avatar size="30px" class="blue" v-else>
                  <span class="white--text headline">S</span>
                </v-avatar>
                <span> {{item.date}}</span>
                <p>{{item.msg}}</p>
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
  import {VAlert, VAvatar, VSubheader} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import Util from '../../libs/util'

  export default {
    name: 'web-socket',
    components: {VAlert, VAvatar, VSubheader, ...VCard},
    data() {
      return {
        ws: null,
        wsUrl: '',
        locWsUrl: '',
        errorMsg: '',
        message: '',
        messages: [{
          type: 1,
          msg: 'send',
          date: '2018-03-23 12:45:34'
        }, {
          type: 2,
          msg: 'receive',
          date: '2018-03-23 12:45:44'
        }],
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
      wsUrlIsEmpty() {
        return this.wsUrl === ''
      },
      isConnected() {
        return this.ws !== null
      }
    },
    methods: {
      connect() {
        if (this.ws) {
          this.errorMsg = 'websocket server has been connected!'
          return
        }

        let app = this
        let timer

        app.errorMsg = ''

        this.ws = new WebSocket(this.wsUrl)
        this.ws.onerror = function error(e) {
          console.log('connect failed!')
          app.errorMsg = 'connect failed! server url:' + app.wsUrl
        }

        this.ws.onopen = function open() {
          console.log('connected')

          // send Heartbeat
          timer = setTimeout(function () {
            app.ws.send('@heartbeat')
          }, 20000)
        }

        this.ws.onmessage = function incoming(me) {
          console.log('received', me)
          app.saveMessage(me.data, 2)
        }

        this.ws.onclose = function close() {
          console.log('disconnected')

          clearTimeout(timer)
          app.ws = null
        }
      },
      disconnect() {
        if (this.ws) {
          this.ws.close()
        }
      },
      send() {
        let msg = Util.trim(this.message)

        if (!msg) {
          this.errorMsg = 'the message cannot be empty'
          return
        }

        if (!this.ws) {
          this.errorMsg = 'please connect to websocket server before send message!'
          return
        }

        this.ws.send(msg)
        this.saveMessage(msg)
      },
      saveMessage(msg, type = 1) {
        this.messages.push({
          type: type,
          msg: msg,
          date: Util.formatDate.format(new Date(), 'yyyy-MM-dd hh:mm:ss')
        })
      },
      clearMessages() {
        this.messages = []
      }
    }
  }
</script>

<style scoped>

</style>
