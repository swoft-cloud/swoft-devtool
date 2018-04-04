<template>
  <div>
  <v-subheader><h2>{{ this.$route.name }}</h2></v-subheader>

  <v-layout row wrap>
    <v-flex xs12 md4>
      <v-layout row wrap>
        <v-flex xs12>
          <v-alert :value="true" color="warning" icon="warning" v-show="errorMsg">
            {{errorMsg}}
          </v-alert>
          <v-card color="grey lighten-4" flat>
            <v-layout row wrap>
              <v-flex xs7>
                <v-text-field
                  name="wsUrl"
                  :label="'eg ' + locWsUrl"
                  single-line
                  required
                  v-model="wsUrl"
                  hint="websocket url. eg wss://echo.websocket.org/"
                  persistent-hint
                ></v-text-field>
              </v-flex>
              <v-flex xs5>
                <v-btn
                  outline
                  :disabled="wsUrlIsEmpty"
                  @click="connect"
                  color="info"
                >
                  Connect
                </v-btn>
                <v-btn :disabled="!isConnected" @click="disconnect" color="warning" outline>
                  Disconnect
                </v-btn>
              </v-flex>

            </v-layout>

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
      <v-card color="grey lighten-4">
        <v-card-text>
          <v-subheader>
            <v-icon>textsms</v-icon>
            Message Box
          </v-subheader>
          <v-divider></v-divider>
          <v-container class="msg-box" fluid>
            <v-layout row wrap v-for="(item, idx) in messages" :key="idx">
              <v-flex xs12>
                <v-avatar size="25px" class="teal" v-if="item.type === 1">
                  <span class="white--text headline">C</span>
                </v-avatar>
                <v-avatar size="25px" class="blue" v-else>
                  <span class="white--text headline">S</span>
                </v-avatar>
                <span class="blue--text"> {{item.date}}</span>
                <div>
                <pre class="px-2 py-2 my-1 msg-detail">{{item.msg}}</pre>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="clearMessages" icon>
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
  </div>
</template>

<script>
  import {VAlert, VAvatar, VBtnToggle} from 'vuetify'
  import * as VCard from 'vuetify/es5/components/VCard'
  import Util from '../../libs/util'

  export default {
    name: 'web-socket',
    components: {VAlert, VAvatar, VBtnToggle, ...VCard},
    data() {
      return {
        ws: null,
        wsUrl: '',
        loading: false,
        locWsUrl: '',
        errorMsg: '',
        logHeartbeat: false,
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
        // req/res data for handshake request
        reqData: [],
        resData: []
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

        this.ws.onopen = function open(ev) {
          console.log('connected', ev)

          // send Heartbeat
          timer = setTimeout(function () {
            app.sendMessage('@heartbeat', false)
          }, 20000)
        }

        this.ws.onmessage = function incoming(me) {
          console.log('received', me)
          app.saveMessage(me.data, 2, 1)
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
        this.sendMessage(Util.trim(this.message))
        this.message = ''
      },
      sendMessage(msg, log = true) {
        this.errorMsg = ''

        if (!msg) {
          this.errorMsg = 'the message cannot be empty'
          return
        }

        if (!this.ws) {
          this.errorMsg = 'please connect to websocket server before send message!'
          return
        }

        this.ws.send(msg)

        if (log) {
          this.saveMessage(msg)
        }
      },
      saveMessage(msg, type = 1, isHeart = 0) {
        this.messages.push({
          type: type,
          isHeart: isHeart,
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

<style lang="stylus" scoped>
  .msg-box
    min-height 400px
    max-height 550px
    overflow-y auto
  .msg-detail
    border 1px solid #cdcdcd
    border-radius 3px
</style>
