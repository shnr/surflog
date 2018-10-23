<template>  
  <div>
    <!-- <p class="link"><router-link to='/create' class="btn btn-light">New Task</router-link></p> -->

    <div class="log-list">

      <div v-for="item in logList" class="box-log">
        <router-link v-bind:to="'/view/' + item.id" class="btn">
          <div class="log-fbox">          
            <div class="log-fbox_cont">
              <h3 class="log_date">{{dateTimeFormatter(item.surf_datetime, 'Y/MM/DD hh:mm', false)}}</h3>
              <div class="log_summary">
                <dl>
                  <dt>Location:</dt>
                  <dd>{{item.location}}</dd>
                </dl>
                <dl>
                  <dt>condition:</dt>
                  <dd>{{item.condition}}</dd>
                </dl>
                <dl>
                  <dt>Swell:</dt>
                  <dd>{{item.swell_height}}, {{item.swell_direction}}</dd>
                </dl>
                <dl>
                  <dt>Wind:</dt>
                  <dd>{{item.wind_size}}, {{item.wind_direction}}</dd>
                </dl>
              </div>
            </div>
            <div class="log-fbox_cont log-fbox_cont__img">
              <div v-if="item.images.length > 0" class="imgwrapper" v-bind:style="{'background-image': 'url('+item.images[0].path + '/' + item.images[0].name +')'}" ></div>
            </div>          
          </div>
        </router-link>
      </div>

    </div>

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
          self.logList = response
        }        
      })
    },

    data() {
      return {
        loggedIn: false,
        title:'',
        content:'',
        logList: []
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
                url:'/api/conditions',
                headers: {
                  'Accept': 'application/json',
                  'Authorization': self.$store.state.token_type + ' ' + self.$store.state.access_token
                }
              })
              .then(function (response) {
                console.log(response.data)
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
        Date Formatter
      */
      dateTimeFormatter : function (oridate, format, is12hours) {

        var weekday = ["日", "月", "火", "水", "木", "金", "土"];
        if (!format) {
            format = 'YYYY/MM/DD(WW) hh:mm:dd'
        }
  
        // conveert to date object
        let date = new Date( oridate )      

        var year = date.getFullYear();
        var month = (date.getMonth() + 1);
        var day = date.getDate();
        var weekday = weekday[date.getDay()];
        var hours = date.getHours(); // if utc, use getUTCHours()
        var minutes = date.getMinutes();
        var secounds = date.getSeconds();
        var ampm = hours < 12 ? 'AM' : 'PM';
        if (is12hours) {
            hours = hours % 12;
            hours = (hours != 0) ? hours : 12; // 0時は12時と表示する
        }
        var replaceStrArray =
            {
              'YYYY': year,
              'Y': year,
              'MM': ('0' + (month)).slice(-2),
              'M': month,
              'DD': ('0' + (day)).slice(-2),
              'D': day,
              'WW': weekday,
              'hh': ('0' + hours).slice(-2),
              'h': hours,
              'mm': ('0' + minutes).slice(-2),
              'm': minutes,
              'ss': ('0' + secounds).slice(-2),
              's': secounds,
              'AP': ampm,
            };

        var replaceStr = '(' + Object.keys(replaceStrArray).join('|') + ')';
        var regex = new RegExp(replaceStr, 'g');

        let ret = format.replace(regex, function (str) {
            return replaceStrArray[str];
        });

        return ret;

      }


    },

    mixins: [
      Vue.extend(require('./CommonFunc.vue')),
    ]

  }
</script>
