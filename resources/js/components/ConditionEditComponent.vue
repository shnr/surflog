<template>  
  <div>
    <div class="logcreate-box">
      <form enctype="multipart/form-data" novalidate @submit.prevent="btnPost">

      <div class="form-group surf_datetime_wrapper">
        <label for="surf_datetime">Surf Datetime</label>
        <date-picker v-model="surf_datetime" :config="datepickerOptions" class=""></date-picker>
      </div>
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" v-model="location" name="location"
         v-bind:class="{ is_error : errors.location }">
        <p class="errortxt" v-if="errors.location">Location must be filled.</p>
      </div>
      <div class="form-group">
        <label for="condition">Condition</label>
        <select class="form-control" v-model="condition"
         v-bind:class="{ is_error : errors.condition }">
          <option v-for="item in selectOptions.conditions" v-bind:value="item">{{item}}</option>
        </select>
        <p class="errortxt" v-if="errors.condition">Condition must be filled.</p>
      </div>
      <div class="form-group">
        <label for="swell_height">Swell height</label>
        <select class="form-control" v-model="swell_height">
          <option v-for="(key, val) in selectOptions.swellHeight" v-bind:value="val">{{key}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="swell_direction">Swell direction</label>
        <select class="form-control" v-model="swell_direction">
          <option v-for="item in selectOptions.swellDirection" v-bind:value="item">{{item}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="wind_strength">Wind strength</label>
        <select class="form-control" v-model="wind_strength">
          <option v-for="item in selectOptions.windStrengthMax" v-bind:value="item">{{item}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="wind_direction">Wind direction</label>
        <select class="form-control" v-model="wind_direction">
          <option v-for="item in selectOptions.windDirection" v-bind:value="item">{{item}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="wetsuits">Wetsuits</label>
        <select class="form-control" v-model="wetsuits">
          <option v-for="item in selectOptions.wetsuits" v-bind:value="item">{{item}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="quiver">quiver</label>
        <select class="form-control" v-model="quiver">
          <option v-for="item in selectOptions.quiver" v-bind:value="item">{{item}}</option>
        </select>
      </div>
      <div class="form-group">
        <label for="comment">Comment</label>
        <textarea class="form-control" v-model="comment" name="comment"></textarea>
      </div>

      <div class="form-group">
        <label>Today's Photos</label>
        <label for="myphotofiles" class="lbl-myphotofiles">Click to add photos
        <input type="file" multiple @change="filesChange" accept="image/*" class="input-file" id="myphotofiles">
        </label>
        <div class="img-upload-preview">
          <div class="upload-image" v-for="item in uploadedImages">
            <span class="imageElem" v-bind:style="{'background-image': 'url('+item+')'}" ></span>
            <!-- <span class="delbtn" v-on:click="delImage(item)">Delete</span>   -->
          </div>
        </div>
      </div>
      
      <p class="addbtn-wrapper"><button class="btn btn-addlog" type="submit">Submit</button></p>
      </form>
    </div>
  </div>
</template>


<script>

  // Import this component
  import datePicker from 'vue-bootstrap-datetimepicker';
  
  // Import date picker css
  import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

  export default {

    mixins: [
      Vue.extend(require('./CommonFunc.vue')),
    ],

    components: {
      datePicker
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
      console.log('mounted')
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
        condition: '',
        surf_datetime: '',
        location: '',
        swell_height: '',
        swell_direction: '',
        wind_strength: '',
        wind_direction: '',
        wetsuits: '',
        quiver: '',
        comment: '',
        uploadFile: null,
        uploadedImages: [],
        uploadedImageDatas: null,
        errors: {
          location: false,
          condition: false,
        },
      }
    },

    methods: {

      setup(response) {
        const self = this
        self.surfDate = self.dateTimeFormatter(response.surf_datetime, 'Y/MM/DD hh:mm', false)

        self.condition = response.condition
        self.surf_datetime = response.surf_datetime
        self.location = response.location
        self.swell_height = (response.swell_height !== null) ? response.swell_height : ''
        self.swell_direction = response.swell_direction
        self.wind_strength =  (response.wind_strength !== null && typeof response.wind_strength !== 'undefined') ? response.wind_strength : ''
        self.wind_direction = response.wind_direction
        self.wetsuits = response.wetsuits
        self.quiver = response.quiver
        self.comment = response.comment

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


      filesChange: function(e) {
        const self = this
          // 選択された File の情報を保存しておく
          e.preventDefault();
          let files = e.target.files;

          // 一旦サムネを空に
          // ほんとは追加・削除したいのだが
          self.uploadedImages = []

          if(files.length > 0) {
            // サムネ表示用
            // files はFilelist型となるのでforeachできない
            for(let i=0;i<files.length;i++){
              let reader = new FileReader();
              let elem = files[i]
              reader.onload = (elem) => {
                self.uploadedImages.push(elem.target.result);
              };
              reader.readAsDataURL(elem);
            }
          }

          self.uploadedImageDatas = files
          self.uploadFile = files[0]
      },


      /*
       Filelistはreadonly.
       なので画像データの編集が出来ない
       どの様に渡すか、課題。

       １．アップロードの度にサーバへアップする
        無駄なデータが多くなる　が
        実装はできそう

      ２．input filesを複数もたせる？
      実装面倒だなあ

      とりあえず削除ボタンはなしにしとく

      */
      delImage: function(item) {
        const self = this
        console.log(item)

        // fileListはforeach不可
        for(let i=0;i<self.uploadedImageDatas.length;i++){
          console.log(self.uploadedImageDatas[i] === item)
        }


      },


      btnPost() {
        const self = this
        let isValid = self.formValidation()
        if(isValid){
          self.editLog()
          .then( function(response) {
            console.log(response)
            if (response !== '') {
              self.$router.push('/')
            }        
          })
        }
      },


      /*
        Error check
        must be filled: location, condition, 
        */
      formValidation() {
        const self = this
        let isValid = true
        if(self.location === '') {
          self.errors.location = true
          isValid = false
        }
        if(self.condition === '') {
          self.errors.condition = true
          isValid = false
        }
        return isValid;
      },

      /*
        POST http://myapi.test/api/tasks with params.
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      editLog() {
        const self = this

        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {

            // アップロード画像があるのでFormData型で値を渡す
            let params = new FormData()
            params.append('surf_datetime', self.surf_datetime);
            params.append('location', self.location);
            params.append('condition', self.condition);
            params.append('swell_height', self.swell_height);
            params.append('swell_direction', self.swell_direction);
            params.append('wind_strength', self.wind_strength);
            params.append('wind_direction', self.wind_direction);
            params.append('wetsuits', self.wetsuits);
            params.append('quiver', self.quiver);
            params.append('comment', self.comment);
            // params.append('file', self.uploadFile);

            // images
            // fileList型のままPostする必要がある。
            if (self.uploadedImageDatas != null && self.uploadedImageDatas.length > 0) {
              for(let i=0;i<self.uploadedImageDatas.length;i++){                
                params.append('file[]', self.uploadedImageDatas[i])
              }
            }            

            axios({
                method:'post',
                url: '/api/conditions/' + self.$route.params.id,
                headers: {
                  'Content-Type': 'multipart/form-data',
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
