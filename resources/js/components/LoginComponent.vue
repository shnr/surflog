<!-- 
Modal example
https://vuejs.org/v2/examples/modal.html
 -->
<template>
  <div class="box-login" v-bind:class='{active:isActive}'>
    <h1 class="ttl">Please Login or Register</h1>
    <div class="box-login-fbox">
      <div class="box-login-fcont box-login-fcont__login">
        <a v-on:click="showLoginModal" class="btn btn__login">Login</a>
      </div>
      <div class="box-login-fcont box-login-fcont__register">
        <router-link to='/register' class="btn btn__register">Register</router-link>
      </div>
    </div>
    <p class="reset_pasword"><a href="/password/reset/" class="">Reset Password</a></p>

    <div class="modal-mask" v-show="showModal">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <h4 class="modal-lead">
              Please Login
            </h4>
          </div>

          <div class="modal-body">
            <slot name="body">
              <p class="errormsg">{{errormsg}}</p>
              <p><input type="text" name="email" v-model="your_email" class="form-control" placeholder="sample@mail.com"></p>
              <p><input type="password" name="password" class="form-control" v-model="your_pw"></p>
              <button v-on:click="letsLogin" class="btn btn-login">Log in!</button>
            </slot>
          </div>

          <div class="modal-footer">
            <slot name="footer">
              <button class="btn btn-close" @click="closeModal">
                close
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>


<script>
  export default {
    created() {
      const self = this
      if (this.$store.getters.getLoginStatus) {
        self.$router.push('/')
      } else {
        self.isActive = true
      }
    },

    mounted() {

    },

    data() {
      return {
        showModal: false,
        loggedIn: false,
        title:'',
        content:'',
        errormsg:'',
        your_email: '',
        your_pw: '',
        isActive: false
      }
    },

    methods: {

      showLoginModal () {
        const self = this
        if (!self.showModal) {
          self.showModal = true
        }
      },

      closeModal () {
        const self = this
        if (self.showModal) {
          self.showModal = false
        }
      },


      /*
      */
      letsLogin() {
        const self = this
        let formParams = {
          your_email : self.your_email,
          your_pw : self.your_pw
        }
        this.$store.dispatch('login', formParams)
        .then( function(response) {
          console.log(response)
          if (!response) {
            // redirect
            self.errormsg = 'Something wrong with your information.'
            // self.showModal = false
          } else {
            // if Login Page
            if (self.$route.name == 'Login') {
              self.$router.push('/')
            }
          }
        })


      }

    }
  }
</script>


<style scoped>
</style>
