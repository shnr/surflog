<template>  
  <div>
    <p class="link"><router-link to='/create' class="btn btn-light">New Task</router-link></p>
    <ul class="list-group">
      <li v-for="item in taskList" class="list-group-item">
        <div class="btn-toolbar justify-content-between">
          <div class="">
            <h3>{{item.name}}</h3>            
            <span>{{item.updated_at}}</span>
          </div>
          <div class="">
            <div class="btn-group">
              <router-link v-bind:to="'/view/' + item.id" class="btn btn-success">View</router-link>
              <router-link v-bind:to="'/edit/' + item.id" class="btn btn-primary">Edit</router-link>
              <a v-on:click="clickDelButon(item.id)" class="btn btn-secondary">Delete</a>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>


<script>
</script>

<script>
  export default {
    created() {
      const self = this
      if (!this.$store.getters.getLoginStatus) {
        self.$router.push('/login')
      }
      
    },

    mounted() {
      const self = this
      self.getList()
      .then( function(response) {
        if (response !== '') {
          self.taskList = response
        }        
      })
    },

    data() {
      return {
        loggedIn: false,
        title:'',
        content:'',
        taskList: []
      }
    },

    methods: {

      /*
        GET http://myapi.test/api/surf
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      getList() {
        const self = this

        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {
            axios({
                method:'get',
                url:'/api/tasks',
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
      },

      /*
        Delete Task
      */
      deleteTask(id) {
        const self = this
        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {
            axios({
                method:'delete',
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

      },

      clickDelButon(id) {
        const self = this
        self.deleteTask(id)
        .then( function(response) {
            let newTasks = []
            // reload tasks
            self.taskList.forEach(function(element) {
              if ( element.id !== response.id ){
                newTasks.push(element)
              }
            })          
            self.taskList = newTasks
        })
      }

    }
  }
</script>
