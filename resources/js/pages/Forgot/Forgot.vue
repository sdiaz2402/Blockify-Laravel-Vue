<template>
  <div class="auth-page">
    <b-container>
      <h5 class="auth-logo">
        <!-- <i class="fa fa-circle text-warning"></i>
        Entri
        <i class="fa fa-circle text-warning"></i> -->
      </h5>
      <Widget class="widget-auth mx-auto" title="" customHeader>
        <p class="widget-auth-info">
            Use your email to recover your password
        </p>
        <form class="mt" @submit.prevent="forgotAuth">
          <b-alert class="alert-sm" variant="danger" :show="!!errorMessage">
            {{errorMessage}}
          </b-alert>
          <div class="form-group">
            <input class="form-control no-border" v-model="email" required type="email" name="email" placeholder="Email" />
          </div>
          <b-button type="submit" size="sm" ref="submit" class="auth-btn mb-3" variant="inverse">{{button_status}}</b-button>
        </form>
        <p class="widget-auth-info">
        </p>
      </Widget>
    </b-container>
    <footer class="auth-footer">
      2021 &copy; StockNewsApp
    </footer>
  </div>
</template>

<script>
import Widget from 'resources/js/components/Widget/Widget';
import {mapState} from 'vuex';

import config from 'resources/js/config';

export default {
  name: 'Forgot',
  components: { Widget },
   data() {
      return {
        button_status:"Recover",
        email:""
      }
   },
  computed: {
    ...mapState('auth', {
      isFetching: state => state.isFetching,
      errorMessage: state => state.errorMessage,
    }),
  },
  methods: {

    forgotAuth() {
      this.button_status = "Loading..."
      this.$axios.post(config.baseURLApi+"/auth/create",{email: this.email,crossDomain: true}).then(({data}) => {
        // console.log(data);
        this.$success_notification(data.data.message);
        this.button_status = "Recover"
      }).catch(({data}) => {
        // console.log(data)
        this.button_status = "Recover"
        this.$error_notification(data.data.message);

      })
    },
  },
  mounted() {
    const creds = config.auth;
    this.email = creds.email;
  }
};
</script>
