<template>  
  <div>
    <div class="form-group">
      <label for="exampleInputEmail1">Task Name</label>
      <input type="text" class="form-control" v-model="task_name" name="task_name" placeholder="task name">
    </div>
    <p class="addbtn-wrapper"><button class="btn btn btn-primary" type="" v-on:click="btnPost">Post</button></p>
  </div>
</template>


<script>
</script>

<script>
  export default {
    created() {
      const self = this
      console.log(this.$store.getters.getLoginStatus)
      if (!this.$store.getters.getLoginStatus) {
        // self.$router.push('/login')
      }
      
    },

    mounted() {
      const self = this

    },

    data() {
      return {
        loggedIn: false,
        title:'',
        content:'',
        task_name: ''
      }
    },

    methods: {

      btnPost() {
        const self = this
        self.AddTask()
        .then( function(response) {
          console.log(response)
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
      AddTask() {
        const self = this

        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {

            console.log(self.task_name)

            let params = {
              name : self.task_name,
            }

            axios({
                method:'post',
                url: '/api/tasks',
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


      }


    }
  }
</script>
