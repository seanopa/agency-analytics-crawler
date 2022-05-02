<template>
  <div class="grid-container">
    <h3>Web Crawler</h3>
    <div class="grid-y grid-padding-y">
      <div class="small-12 medium-6 cell">
        <label>Enter url: e.g. https://www.example.com
          <input type="text" v-model="url" placeholder="Enter url">
          <small style="color:red;">{{errors.url}}</small>
        </label>
      </div>
      <div class="small-12 medium-4 cell">
        <label>Max number of pages to crawl
          <input v-model="max_pages" type="number" placeholder="Max pages">
          <small style="color:red;">{{errors.max_pages}}</small>
        </label>
      </div>
      <div class="small-12 medium-2 cell">

        <button :disabled="disabled" class="button" @click="crawl()"> <div v-if="isCrawling" class="spinning-loader">Loading...</div> {{crawl_btn_text}}</button>
      </div>

      <div class="grid-y grid-padding-y">
        OR
        <div class="small-12 medium-6 cell">
          <label>Enter job id to get results
            <input type="number" v-model="job_id" placeholder="Job ID">
            <small style="color:red;">{{stats_err.detail}}</small>
          </label>
        </div>
        <div class="small-12 medium-2 cell">
          <button class="button" @click="fetchStats()">Get Result</button>
        </div>
      </div>

    </div>

    <div v-if="stats.pages">
      <h3>Results</h3>
      <div class="grid-x grid-padding-x">
        <div class="small-12 medium-12 cell">
          <table>
            <thead>
            <tr>
              <th>Stat</th>
              <th>Result</th>
            </tr>
            </thead>
            <tbody>
              <tr><td>Number of pages crawled</td><td>{{stats.total_pages_crawled}}</td></tr>
              <tr><td>Number of a unique images</td><td>{{stats.unique_image_total}}</td></tr>
              <tr><td>Number of unique internal links</td><td>{{stats.unique_internal_link_total}}</td></tr>
              <tr><td>Number of unique external links</td><td>{{stats.unique_external_link_total}}</td></tr>
              <tr><td>Average page load in seconds</td><td>{{stats.average_page_load_time}}</td></tr>
              <tr><td>Average word count</td><td>{{stats.average_word_count}}</td></tr>
              <tr><td>Average title length</td><td>{{stats.average_title_length}}</td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <h3>Pages Summary</h3>
      <div class="grid-x grid-padding-x">
        <div class="small-12 medium-12 cell">
          <table>
            <thead>
            <tr>
              <th>Url</th>
              <th>Http Status</th>
              <th>Title</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(page, key) in stats.pages" :key="key">
              <td><a :href="page.url" target="_blank">{{page.url}}</a></td>
              <td>{{page.http_status}}</td>
              <td>{{page.title}}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data: () => ({
    url: 'https://www.agencyanalytics.com',
    max_pages: 4,
    job_id: null,
    errors: {
      url: '',
      max_pages: ''
    },
    isCrawling: false,
    stats: {},
    stats_err: {},
    polling: null
  }),

  computed: {
    disabled () {
      return this.errors.url.length > 0 || this.errors.max_pages.length > 0 || this.isCrawling;
    },
    crawl_btn_text () {
      return this.isCrawling ? ' Please Wait While Crawling' : 'Start Crawling'
    }
  },

  beforeDestroy () {
    clearInterval(this.polling)
  },

  methods: {

    crawl () {
      this.resetStats()
      const params = new URLSearchParams()
      params.append('url', this.url)
      params.append('max_pages', this.max_pages)

      const config = {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }
      this.isCrawling = true;
      axios
          .post('/api/v1/crawlLink', params, config)
          .then( response => {
            this.job_id = response.data.job_id;
            this.pollResult();
          })
          .catch( (error) => {
            console.log(error);
          })
          .then( () => {
            // always executed
          });
    },

    pollResult () {
      this.fetchStats();
      this.polling = setInterval( () => {this.fetchStats();}, 3000);
    },

    fetchStats () {
      this.resetStats()
      axios
          .get('/api/v1/getCrawlerStatJob/' + this.job_id)
          .then(response => {
            if (response.data.updated_at) {
              this.stats = response.data;
              this.isCrawling = false;
              if (this.polling) clearInterval(this.polling)
            }
          }).catch(error => {
            this.stats_err = error.response.data;
      })
    },

    resetStats () {
      this.stats = {}
      this.stats_err = {}
    },

    validateUrl (value) {
      let valid = /^(http|https):\/\/[^ "]+$/.test(value);
      if (valid) {
        this.errors['url'] = '';
      } else {
        this.errors['url'] = 'Invalid url. Please enter https or http links only';
      }
    },

    validateMaxPages (value) {

      if (value > 0 && value < 101) {
        this.errors['max_pages'] = '';
      } else {
        this.errors['max_pages'] = 'Max pages must be in range of [1-100]';
      }
    }
  },

  watch: {
    url (value) {
      this.url = value;
      this.validateUrl(value);
    },

    max_pages (value) {
      this.max_pages = value;
      this.validateMaxPages(value);
    },
  }
}
</script>
<style ref="text/css">
.spinning-loader {
  font-size: 10px;
  margin-top: -2px;
  text-indent: -9999em;
  width: 2em;
  height: 2em;
  border-radius: 50%;
  background: #963182;
  background: -moz-linear-gradient(left, #963182 10%, rgba(150,49,130, 0) 42%);
  background: -webkit-linear-gradient(left, #963182 10%, rgba(150,49,130, 0) 42%);
  background: -o-linear-gradient(left, #963182 10%, rgba(150,49,130, 0) 42%);
  background: -ms-linear-gradient(left, #963182 10%, rgba(150,49,130, 0) 42%);
  background: linear-gradient(to right, #963182 10%, rgba(150,49,130, 0) 42%);
  position: relative;
  -webkit-animation: load3 1.4s infinite linear;
  animation: load3 1.4s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  display: inline-block;
}
.spinning-loader:before {
  width: 50%;
  height: 50%;
  background: #963182;
  border-radius: 100% 0 0 0;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
}
.spinning-loader:after {
  background: transparent;
  width: 75%;
  height: 75%;
  border-radius: 50%;
  content: '';
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
@-webkit-keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

</style>