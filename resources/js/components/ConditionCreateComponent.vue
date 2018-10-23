<template>  
  <div>
    <div class="logcreate-box">
      <form enctype="multipart/form-data" novalidate @submit.prevent="btnPost">

      <div class="form-group surf_datetime_wrapper">
        <label for="surf_datetime">Surf Datetime</label>
        <date-picker v-model="surf_datetime" :config="options" class=""></date-picker>
      </div>
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control" v-model="location" name="location">
      </div>
      <div class="form-group">
        <label for="condition">Condition</label>
        <select class="form-control" v-model="condition">
          <option v-for="item in selectOptions.conditions" v-bind:value="item">{{item}}</option>
        </select>
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
      
      <p class="addbtn-wrapper"><button class="btn btn-addlog" type="submit">Add New Log</button></p>
      </form>
    </div>
  </div>
</template>


<script>

  // import 'bootstrap/dist/css/bootstrap.css';
  // import 'bootstrap/dist/css/bootstrap.css';
  
  // Import this component
  import datePicker from 'vue-bootstrap-datetimepicker';
  
  // Import date picker css
  import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

  export default {
    components: {
      datePicker
    },
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
        date: new Date(),
        // dp options
        // http://eonasdan.github.io/bootstrap-datetimepicker/Options/
        options: {
          format: 'YYYY-MM-DD h:mm:ss',
          useCurrent: true,
          showClear: true,
          showClose: true,
          debug: false
        },

        selectOptions: {
          conditions: [
            "poor",
            "fair",
            "good",
            "excellent",
            "perfect"
          ],
          swellHeight: {
            1: "knee",
            2: "chest",
            3: 'shoulder',
            4: 'head',
            5: 'overhead',
            6: 'double'
          },
          swellDirection: [
            'N',
            'NNE',
            'NE',
            'ENE',
            'E',
            'ESE',
            'SE',
            'SSE',
            'S',
            'SSW',
            'SW',
            'WSW',
            'W',
            'WNW',
            'NW',
            'NNW'
          ],
          windStrengthMax: 20,
          windDirection: [
            'N',
            'NNE',
            'NE',
            'ENE',
            'E',
            'ESE',
            'SE',
            'SSE',
            'S',
            'SSW',
            'SW',
            'WSW',
            'W',
            'WNW',
            'NW',
            'NNW'
          ],
          wetsuits: [
            'semidry',
            '3mmfull',
            'seagul',
            'longspring',
            'tapper',
            'trunks'
          ],
          quiver: [
            'cipodmod',
            'bic sup',
            'ryko',
            'cinewflyer',
            'beater'
          ]
        },

        loggedIn: false,
        condition: null,
        surf_datetime: null,
        location: null,
        swell_height: null,
        swell_direction: null,
        wind_strength: null,
        wind_direction: null,
        wetsuits: null,
        quiver: null,
        comment: null,
        uploadFile: null,
        uploadedImages: [],
        uploadedImageDatas: null
      }
    },

    methods: {

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
        self.addLog()
        .then( function(response) {
          // console.log(response)
          // if (response !== '') {
          //   self.$router.push('/')
          // }        
        })
      },

      /*
        POST http://myapi.test/api/tasks with params.
        Accept: application/json
        Authorization: Bearer xxxxx
      */
      addLog() {
        const self = this

        return new Promise((resolve, reject) => {
          if (self.$store.state.token_type != "") {

            // アップロード画像があるのでFormData型で値を渡す
            let params = new FormData()
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
            if (self.uploadedImageDatas.length > 0) {
              for(let i=0;i<self.uploadedImageDatas.length;i++){                
                params.append('file[]', self.uploadedImageDatas[i])
              }
            }            

            console.log(params)
            axios({
                method:'post',
                url: '/api/conditions',
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
