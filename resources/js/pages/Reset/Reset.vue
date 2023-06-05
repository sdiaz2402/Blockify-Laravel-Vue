<template>
  <div class="auth-page">
    <b-container>
      <h5 class="auth-logo">
        <!-- <i class="fa fa-circle text-warning"></i>
        Entri
        <i class="fa fa-circle text-warning"></i> -->
      </h5>
      <Widget class="widget-auth mx-auto" title customHeader>
        <p class="widget-auth-info">Use your email to recover your password</p>
        <form class="mt" @submit.prevent="resetAuth">
          <div class="form-group">
            <input
              class="form-control no-border"
              v-model="email"
              required
              type="email"
              name="email"
              placeholder="Email"
            />
          </div>
          <div class="form-group">
            <input
              class="form-control no-border"
              v-model="password"
              required
              type="password"
              name="password"
              placeholder="Password"
            />
          </div>
          <div class="form-group">
            <input
              class="form-control no-border"
              v-model="c_password"
              required
              type="password"
              name="c_password"
              placeholder="Confirm Password"
            />
          </div>
          <b-button
            type="submit"
            size="sm"
            ref="submit"
            class="auth-btn mb-3"
            variant="inverse"
          >{{button_status}}</b-button>
        </form>
        <p class="widget-auth-info"></p>
      </Widget>
    </b-container>
    <footer class="auth-footer">2021 &copy; FCP.FUND</footer>
  </div>
</template>

<script>
import Widget from "@/components/Widget/Widget";

import config from "../../config";

export default {
  name: "Reset",
  components: { Widget },
  data() {
    return {
      button_status: "Update",
      email: "",
      password: "",
      c_password: "",
      token: "",
      rules: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/
    };
  },
  computed: {},
  methods: {
    password_check() {
      if (this.password != this.c_password) {
        this.$error_notification("Passwords must be the same");
      }
      if (this.rules.test(this.password)) {
        return true;
      } else {
        this.$error_notification(
          "Password should contain at least one number and one special character"
        );
        return false;
      }
    },

    resetAuth() {
      if (this.password_check()) {
        this.button_status = "Loading...";
        this.$axios
          .post(config.baseURLApi + "/auth/reset", {
            email: this.email,
            password: this.password,
            token: this.token,
            crossDomain: true
          })
          .then(({ data }) => {
            // console.log(data.data);
            if (data.data.status == "success") {
              this.$success_notification(data.data.message);
            } else {
              this.$error_notification(data.data.message);
            }
            this.button_status = "Update";
            this.email = "";
            this.token = "";
            this.password = "";
            this.c_password = "";
            setTimeout(function(){
              this.$router.push('login')
            },3000)
          })
          .catch(({ data }) => {
            // console.log(data);
            this.button_status = "Update";
            this.$error_notification(data.data.message);
          });
      }
    }
  },
  mounted() {
    const creds = config.auth;
    this.email = creds.email;
    this.token = this.$route.query.t;
  }
};
</script>
