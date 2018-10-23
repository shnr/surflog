<template>  
  <div>
    <p class="link"><router-link to='/' class="btn btn-light">Index</router-link></p>
    <div class="form-group">
      <label for="exampleInputEmail1">Task Name</label>
      <input type="text" class="form-control" v-model="task_name" v-cloak name="task_name">
    </div>

    <p class="addbtn-wrapper"><button class="btn btn-primary" type="" v-on:click="btnPost">Post</button></p>

  </div>
</template>


<script>
</script>

<script>
  export default {

    // 毎回呼ばれる
    created() {
      const self = this
      console.log('created')
      if (!this.$store.getters.getLoginStatus) {
        // self.$router.push('/login')
      }
      let id = self.$route.params.id

      // このページからロードした場合は、mountedのタイミングでは isLoggedIn = falseのはず
      // これは二度目以降に呼ばれた時用。
      if (this.$store.getters.getLoginStatus) {
        self.getTask(id)
        .then( function(response) {
          self.task = response
          self.task_name = self.task.name
        })
      } else {
        // このページからロードされた場合、ログイン状態の確認をしてから、apiを投げる必要がある。
        // createdの時点では、checkLoginStatusがまだ動かない
        // watchで、state.isLoggedInの状態を監視できる
        // isLoggedIn === trueになった場合に、getTaskできる
        this.$store.watch(
              state => state.isLoggedIn,
              isLoggedIn => {
                if (isLoggedIn) {
                  self.getTask(id)
                  .then( function(response) {
                    self.task = response
                    self.task_name = self.task.name
                  })                
                }
              }
            )
      }

       
    },

    // 毎回呼ばれる
    mounted() {
      const self = this
      console.log('mounted')
    },

    // v-modelが変更されたとき、呼ばれる？
    updated () {
      console.log('updated')
    }, 

    data() {
      return {
        loggedIn: false,
        title:'',
        content:'',
        task: {},
        task_name: ''
      }
    },

    methods: {

      btnPost() {
        const self = this
        let id = self.$route.params.id
        self.editTask(id)
        .then( function(response) {
          if (response !== '') {
            self.$router.push('/')
          }        
        })
      },


      /*
        POST http://myapi.test/api/tasks with params.
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      editTask(id) {
        const self = this
        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {

            let params = {
              name : self.task_name,
            }

            axios({
                method:'put',
                url: '/api/tasks/' + id,
                headers: {
                  'Accept': 'application/json',
                  'Authorization': self.$store.state.token_type + ' ' + self.$store.state.access_token
                },
                data: params
              })
              .then(function (response) {

                if (response.data) {                  
                  resolve(response.data)
                }

              })
              .catch(function (error) {                
                // resolve()
              })
          } else {
            reject
          }
        })
      },


      /*
        POST http://myapi.test/api/tasks with params.
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      getTask(id) {
        const self = this
        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {
            axios({
                method:'GET',
                url: '/api/tasks/' + id,
                headers: {
                  'Accept': 'application/json',
                  'Authorization': self.$store.state.token_type + ' ' + self.$store.state.access_token
                }
              })
              .then(function (response) {
                if (response.data) {                  
                  resolve(response.data)
                }
              })
              .catch(function (error) {                
                // resolve()
              })
          } else {
            reject
          }
        })
      }

    }
  }
</script>
