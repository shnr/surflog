<template>
  <div>
    <div class="fullwidth-container">
      <div class="diagnosis-title">
        <div class="fbox">
          <div class="col">
            <div class="inner tc">
              <h3 class="card-ttl">404</h3>
            </div>
          </div>
          <div class="bar"></div>
          <div class="col colx2">
            <p class="txt">ページが見つかりませんでした</p>
          </div>
        </div>
      </div>
    </div>
    <div class="main-container cf">
      <div class="diagnosis-nav">
      </div>
      <div class="diagn-container ">
        <div class="diagn-inner">
          <h2>ページが見つかりませんでした</h2>
          <p class="tc"><router-link to="/" class="btn">TOPへ戻る</router-link></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: block;
  margin: 0 10px 40px;
  padding-bottom: 20px;
  border-bottom: 1px solid #ddd;
}
a {
  color: #42b983;
}
.tc{
  text-align: center;
}
</style>
