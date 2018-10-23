<script>

  const _selectOptions = {
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
        }


  // dp options
  // http://eonasdan.github.io/bootstrap-datetimepicker/Options/
  const _datepickerOptions = {
          format: 'YYYY-MM-DD h:mm:ss',
          useCurrent: true,
          showClear: true,
          showClose: true,
          debug: false
        }

  export default {


    data() {
      return {
        selectOptions: _selectOptions,
        datepickerOptions : _datepickerOptions
      }
    },

    methods: {

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


    }
  }
</script>
