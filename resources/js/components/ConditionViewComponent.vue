<template>  
  <div class="condition-detail">

    <div class="condition-detail_mainimage">
      <div v-if="condition.images.length > 0" class="imgwrapper" v-bind:style="{'background-image': 'url('+condition.images[0].path + '/' + condition.images[0].name +')'}" ></div>
    </div>

    <div class="condition-detail_content">      
      <div class="detail-box">
        <h2 class="date-txt" v-cloak>{{surfDate}}</h2>
        <div class="log_summary">
          <dl>
            <dt>Location:</dt>
            <dd>{{condition.location}}</dd>
          </dl>
          <dl>
            <dt>condition:</dt>
            <dd>{{condition.condition}}</dd>
          </dl>
          <dl>
            <dt>Swell:</dt>
            <dd>{{condition.swell_height}}, {{condition.swell_direction}}</dd>
          </dl>
          <dl>
            <dt>Wind:</dt>
            <dd>{{condition.wind_size}}, {{condition.wind_direction}}</dd>
          </dl>
        </div>
        <div class="comment-box">
          <p>{{condition.comment}}</p>
        </div>
      </div>
      <div class="detail-gallery-box">
        <h3 class="gallery-title">Gallery</h3>
        <gallery :images="gellryImage" :index="index" @close="index = null"></gallery>
        <div class="image-panel-wrapper">        
          <div
            class="image-panel"
            v-for="(image, imageIndex) in thumbnails"
            :key="imageIndex"
            @click="index = imageIndex"
            v-bind:style="{'background-image': 'url('+image +')'}"           
          ></div>
        </div>
      </div>
    </div>

    <p class="link"><router-link to='/' class="btn btn-light">Index</router-link></p>

    <router-link v-bind:to="'/edit/' + condition.id" class="btn btn-primary">Edit</router-link>
    <p class="delete-btn-wrap"><a v-on:click="clickDelButon(condition.id)">Delete</a></p>
    
  </div>
</template>


<script>
</script>

<script>

  import MyGallery from './MyGallery'
  import 'v-slim-dialog/dist/v-slim-dialog.css'
  import SlimDialog from 'v-slim-dialog'

  Vue.use(SlimDialog)

  export default {

    components: {
      'gallery': MyGallery
    },

    // 毎回呼ばれる
    created() {
      const self = this
      // console.log('created')
      if (!this.$store.getters.getLoginStatus) {
        // self.$router.push('/login')
      }
      let id = self.$route.params.id

      // このページからロードした場合は、mountedのタイミングでは isLoggedIn = falseのはず
      // これは二度目以降に呼ばれた時用。
      if (this.$store.getters.getLoginStatus) {
        self.get(id)
        .then( function(response) {
          self.setup(response)
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
                  self.get(id)
                  .then( function(response) {
                    self.setup(response)
                  })                
                }
              }
            )
      }

       
    },

    // 毎回呼ばれる
    mounted() {
      const self = this
      // console.log('mounted')
    },

    // v-modelが変更されたとき、呼ばれる？
    updated () {
      // console.log('updated')
    }, 

    data() {
      return {
        loggedIn: false,
        title:'',
        surfDate: null,
        content:'',
        condition: {
          images:[]
        },
        gellryImage:[],
        thumbnails:[],
        index: null
      }
    },

    methods: {

      setup(response) {
        const self = this
        self.condition = response
        self.makeGalleryArray(self.condition.images)
        self.makeThumbnailsArray(self.condition.thumbnails)
        self.surfDate = self.dateTimeFormatter(self.condition.surf_datetime, 'Y/MM/DD hh:mm', false)
      },

      // thumbnail list
      makeThumbnailsArray(images) {
        const self = this
        images.forEach(function(element) {
          self.thumbnails.push(element.path + '/' + element.name)
        })
      },

      // fullimage list
      makeGalleryArray(images) {
        const self = this
        images.forEach(function(element) {
          self.gellryImage.push(element.path + '/' + element.name)
        })
      },

      /*
        POST http://myapi.test/api/tasks with params.
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      get(id) {
        const self = this
        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {
            axios({
                method:'GET',
                url: '/api/conditions/' + id,
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
      delete(id) {
        const self = this
        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {
            axios({
                method:'delete',
                url: '/api/conditions/' + id,
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
        const options = {title: 'Delete Log', size: 'sm'}
        this.$dialogs.alert('Are you sure to delete this log?', options)
        .then(res => {
          if(res.ok) {
            // console.log('ok!') // {ok: true|false|undefined}  
            self.delete(id)
            .then( function(response) {
              self.$router.push('/')
            })
          }          
        })
      }




    },


    mixins: [
      Vue.extend(require('./CommonFunc.vue')),
    ]

  }
</script>
