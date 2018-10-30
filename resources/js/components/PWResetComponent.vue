<!-- 
Modal example
https://vuejs.org/v2/examples/modal.html
 -->
<template>
  <div class=" box-login">
    <h1 class="ttl">Reset Your Password</h1>

    <p><input type="text" name="email" v-model="your_email" class="form-control" placeholder="sample@mail.com"></p>
    <p><button v-on:click="resetPw" class="btn btn__register">Submit</button></p>
    <router-link to='/' class="btn btn__login">Home</router-link>
  </div>
</template>


<script>
  export default {
    created() {
      const self = this
      self.getResetToken()
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
      getResetToken() {
        const self = this
        this.$store.dispatch('getResetToken')
        .then( function(response) {
          console.log(response)
          // if (!response) {
          //   // redirect
          //   self.$router.push('/reset')
          //   self.showModal = false
          // } else {
          //   // login
          //   self.$store.dispatch('login', formParams)
          //   .then( function(response) {
          //     if (response) {
          //       self.$router.push('/')
          //     }
          //   })
          // }
        })
      },



      /*
      */
      resetPw() {
        const self = this
        let formParams = {
          your_name : self.your_name,
          your_email : self.your_email,
          your_pw : self.your_pw
        }
        this.$store.dispatch('reset', formParams)
        .then( function(response) {
          console.log(response)
          // if (!response) {
          //   // redirect
          //   self.$router.push('/reset')
          //   self.showModal = false
          // } else {
          //   // login
          //   self.$store.dispatch('login', formParams)
          //   .then( function(response) {
          //     if (response) {
          //       self.$router.push('/')
          //     }
          //   })
          // }
        })


      }

    }
  }
</script>


<style scoped>
</style>
