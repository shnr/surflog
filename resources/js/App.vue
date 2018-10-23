<template>
      <div id="app">

        <header class="header">
          <div class="header-inner">
            <h3 class="header-title"><router-link :to="rootpath" >SURF LOG</router-link></h3>
          </div>
        </header>

        <div class="app-container">
          <div class="main-content">
            <transition name="fade" mode="">
              <router-view class="view"></router-view>
            </transition>            
          </div>
        </div>

        <footer class="footer">
          <nav class="foot-nav">
            <ul v-show="isLoggedIn">
              <li class="add-link"><router-link to='/create' class=""><i class="far fa-plus-square"></i></router-link></li>
            </ul>
            <p class="foot-logout" v-show="isLoggedIn"><button class="btn-foot-logout" type="" v-on:click="logout">Logout</button></p>
          </nav>
        </footer>
      </div>
</template>

<script>

export default {
  name: 'app',

  data() {
    return {
      rootpath: '/',
      appName: 'My surf Log',
      loginStates: false
    }
  },

  created() {
    const self = this
    // ログインステータスの確認
    self.$store.dispatch('checkLoginStatus')
    .then( function(response) {
      console.log(response)
      if (!response) {
        // redirect
        self.$router.push('/login')
      } else {
        // if Login Page
        if (self.$route.name == 'Login') {
          self.$router.push('/')
        }
      }
    })

  },

  computed: {

    isLoggedIn () {
      return this.$store.getters.getLoginStatus
    },

    isLogin () {
      console.log('computed')
      console.log(this.$store.getters.getLoginStatus)
      return
    }

  },

  methods: {

    logout () {
      const self = this
      this.$store.dispatch('logout')
      self.$router.push('/login')
    }

  }
}
</script>