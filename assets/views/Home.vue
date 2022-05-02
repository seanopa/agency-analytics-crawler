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
        <button :disabled="disabled" class="button" @click="crawl()">Start Crawling</button>
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
          <button :disabled="disabled" class="button" @click="fetchStats()">Get Result</button>
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
    stats: {},
    stats_err: {},
    polling: null
  }),

  computed: {
    disabled () {
      return this.errors.url.length > 0 || this.errors.max_pages.length > 0;
    }
  },

  beforeDestroy () {
    clearInterval(this.polling)
  },

  methods: {

    crawl () {

      const params = new URLSearchParams()
      params.append('url', this.url)
      params.append('max_pages', this.max_pages)

      const config = {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }

      axios
          .post('/api/v1/crawlLink', params, config)
          .then( response => {
            this.job_id = response.data.job_id;
            this.stats_err = {};
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
      axios
          .get('/api/v1/getCrawlerStatJob/' + this.job_id)
          .then(response => {
            this.stats = response.data;
            this.stats_err = {};
            if (this.stats.updated_at) {
              if (this.polling) clearInterval(this.polling)
            }
          }).catch(error => {
            this.stats_err = error.response.data;
      })
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

      if (value < 1 || value < 11) {
        this.errors['max_pages'] = '';
      } else {
        this.errors['max_pages'] = 'Max pages must be between 1 and 10';
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