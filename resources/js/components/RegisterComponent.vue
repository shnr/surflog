<!-- 
Modal example
https://vuejs.org/v2/examples/modal.html
 -->
<template>
  <div class=" box-login">
    <h1 class="ttl">Please sign up</h1>

    <p><input type="text" name="name" v-model="your_name" class="form-control" placeholder="your name"></p>
    <p><input type="text" name="email" v-model="your_email" class="form-control" placeholder="sample@mail.com"></p>
    <p><input type="password" name="password" class="form-control" v-model="your_pw"></p>
    <p><button v-on:click="letsRegister" class="btn btn__register">Submit</button></p>
    <router-link to='/register' class="btn btn__login">Login Page</router-link>
  </div>
</template>


<script>
  export default {
    created() {
      const self = this
      // if (this.$store.getters.getLoginStatus) {
      //   self.$router.push('/')
      // }
    },

    mounted() {

    },

    data() {
      return {
        showModal: false,
        loggedIn: false,
        title:'',
        content:'',
        your_name: '',
        your_email: '',
        your_pw: ''
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
      letsRegister() {
        const self = this
        let formParams = {
          your_name : self.your_name,
          your_email : self.your_email,
          your_pw : self.your_pw
        }
        this.$store.dispatch('signup', formParams)
        .then( function(response) {
          console.log(response)
          if (!response) {
            // redirect
            self.$router.push('/login')
            self.showModal = false
          } else {
            // login
            self.$store.dispatch('login', formParams)
            .then( function(response) {
              if (response) {
                self.$router.push('/')
              }
            })
          }
        })


      }

    }
  }
</script>


<style scoped>
</style>
