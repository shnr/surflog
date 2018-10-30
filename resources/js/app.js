
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vuex from 'vuex'
import router from './router'
import App from './App.vue'

import '@fortawesome/fontawesome-free/css/fontawesome.css'
import '@fortawesome/fontawesome-free/css/solid.css'
import '@fortawesome/fontawesome-free/css/regular.css'



$.extend(true, $.fn.datetimepicker.defaults, {
  icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'fas fa-calendar-check',
    clear: 'far fa-trash-alt',
    close: 'far fa-times-circle',
    add: 'far fa-plus-square'
  }
})


const store = new Vuex.Store({
  state: {
    isLoggedIn: false,
    strageKey: '83ubgjahghfbadknll;a:', // WebStrageのkey. 
    clientSecret: _clientSecret, 
    clientId: _clientId,
    your_email: '',
    your_pw: '',
    access_token: '',
    expires_in: '',
    refresh_token: '',
    token_type: '',
  },
  mutations: {
    // Stateを変更するメソッド。非同期を含めることは負荷
    // commit を使って呼び出す
    // componentから使う場合、
    // this.$store.commit('setLogInStatus', true) みたいなかんじ？
    setLogInStatus(state, status) {
      console.log(status)
      state.isLoggedIn = status
    }
  },
  actions: {
    // actionは非同期処理を含めることができる
    // ここでapi問い合わせをやるとよいかも
    // 呼び出すときはdispatch
    // this.$store.dispatch('actionName')
    // ここでapiを呼び出し、通信を行いlocalstrageまたはcookieに格納、までやるとよいかも

    /*
        TODO: axiosでpwを送信することにリスクはないのか？
        通常のパスワードログインと基本的には同じはず
        だがブラウザの送信履歴に残ってしまうが、問題ないだろうか

        Method: POST
        URL: youredomein.com/oauth/token
        Header: [Content-Type:  application/json;]
        {
            "grant_type": "password",
            "client_id": 2,
            "client_secret": "r5V5xoKmBrU2kOmG4ZOrF0iCcJ4lfVncTvWnRk2P",
            "username": "my.name@mail.com",
            "password": "test",
            "scope": "*"
        }

    */
    login({commit, dispatch, state, rootState, getters, rootGetters}, formParams) {
      const self = this
      
      // formParams need to be checked.
      return new Promise((resolve, reject) => {
        axios.post('/oauth/token', {
            grant_type: 'password',
            client_id: self.state.clientId,
            client_secret: self.state.clientSecret,
            username: formParams.your_email,
            password: formParams.your_pw,
            scope: "*"
          })
          .then(function (response) {
            console.log(response)

            if (response.data) {

              self.state.access_token = response.data.access_token
              self.state.token_type = response.data.token_type
              self.state.refresh_token = response.data.refresh_token
              self.state.expires_in = response.data.expires_in

              // token情報をlocal strageに格納
              let tokenInfo = {
                access_token: self.state.access_token,
                token_type: self.state.token_type,
                refresh_token: self.state.refresh_token,
                expires_in: self.state.expires_in
              }
              window.localStorage.setItem(self.state.strageKey, JSON.stringify(tokenInfo))

              // statusを変更
              commit('setLogInStatus', true)


              resolve(self.getters.getLoginStatus)
              // this.$router.push('/')
            }

          })
          .catch(function (error) {
            commit('setLogInStatus', false)
            resolve(self.getters.getLoginStatus)
          })
      })

    },

    // ブラウザ展開時になど、ログイン状態をチェックする
    // 具体的にはrefresh tokenを行う

    /*
      Method: POST
      URL: youredomein.com/oauth/token
      params.
      {
          "grant_type": "refresh_token",
          "refresh_token": '',
          "client_id": ,
          "client_secret": ',
          "scope": "*"
      }
    */

    checkLoginStatus ({commit, dispatch, state, rootState, getters, rootGetters}) {
      const self = this
      let tokenJson = window.localStorage.getItem(self.state.strageKey)
      let tokenObj = JSON.parse(tokenJson)

      if (tokenJson !== null) {

        return new Promise((resolve, reject) => {
          axios.post('/oauth/token', {
              grant_type: 'refresh_token',
              client_id: self.state.clientId,
              client_secret: self.state.clientSecret,
              refresh_token: tokenObj.refresh_token,          
              scope: "*"
            })
            .then(function (response) {
              console.log(response)

              if (response.data) {

                self.state.access_token = response.data.access_token
                self.state.token_type = response.data.token_type
                self.state.refresh_token = response.data.refresh_token
                self.state.expires_in = response.data.expires_in

                // token情報をlocal strageに格納
                let tokenInfo = {
                  access_token: self.state.access_token,
                  token_type: self.state.token_type,
                  refresh_token: self.state.refresh_token,
                  expires_in: self.state.expires_in
                }
                window.localStorage.setItem(self.state.strageKey, JSON.stringify(tokenInfo))

                // statusを変更
                commit('setLogInStatus', true)

                // return resolve
                resolve(self.getters.getLoginStatus)
                // this.$router.push('/')
              }

            })
            .catch(function (error) {
              // console.log(error.response)
              commit('setLogInStatus', false)
              window.localStorage.removeItem(self.state.strageKey)
              resolve(self.getters.getLoginStatus)
              // reject
            })

        })


      }

    },


    logout({commit, dispatch, state, rootState, getters, rootGetters}) {
      const self = this
      self.state.access_token = ''
      self.state.token_type = ''
      self.state.refresh_token = ''
      self.state.expires_in = ''
      window.localStorage.removeItem(self.state.strageKey)
      commit('setLogInStatus', false)
    },


    /*
      signup func
    */
    signup({commit, dispatch, state, rootState, getters, rootGetters}, formParams) {
      const self = this
      
      let _data = {
        name : formParams.your_name,
        email : formParams.your_email,
        password : formParams.your_pw,
        password_confirmation : formParams.your_pw,
      }
      // formParams need to be checked.
      return new Promise((resolve, reject) => {
        axios.post('/api/register', {
            name : formParams.your_name,
            email : formParams.your_email,
            password : formParams.your_pw,
            password_confirmation : formParams.your_pw
          })
          .then(function (response) {
            if (response.data) {
              // sighn up 成功
              resolve(true)
            }
          })
          .catch(function (error) {
            commit('setLogInStatus', false)
            resolve(self.getters.getLoginStatus)
          })
      })

    },

    /*
      get reset token
    */
    getResetToken({commit, dispatch, state, rootState, getters, rootGetters}, formParams) {
      const self = this
      
      // formParams need to be checked.
      return new Promise((resolve, reject) => {
        axios.get('/api/getResetToken')
          .then(function (response) {
            if (response.data) {
              // sighn up 成功
              resolve(true)
            }
          })
          .catch(function (error) {
            // commit('setLogInStatus', false)
            resolve(self.getters.getLoginStatus)
          })
      })

    },


    /*
      reset password func
      この機能はSPAせずデフォルト画面を使う
    */
    // reset({commit, dispatch, state, rootState, getters, rootGetters}, formParams) {
    //   const self = this
      
    //   // formParams need to be checked.
    //   return new Promise((resolve, reject) => {
    //     axios.post('/api/reset', {
    //         email : formParams.your_email,
    //       })
    //       .then(function (response) {
    //         if (response.data) {
    //           // sighn up 成功
    //           resolve(true)
    //         }
    //       })
    //       .catch(function (error) {
    //         // commit('setLogInStatus', false)
    //         resolve(self.getters.getLoginStatus)
    //       })
    //   })

    // },

  },
  getters: {
    // Loginの状態を得る
    // componentからは、
    // this.$store.getters.getLoginStatus のように使う
    getLoginStatus(state) { return state.isLoggedIn }
  }
})



const app = new Vue({
    el: '#app',
    router,
    store: store,
    components: { App },
    template: '<App/>'
});


