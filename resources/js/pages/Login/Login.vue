<template>
  <div class="auth-page">
    <div class="min-h-screen bg-white flex bg-block-black-accent">
      <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
        <div class="mx-auto w-full max-w-sm lg:w-96">
          <div>
            <div class="w-24 py-10 bg-block-black m-auto text-center text-block-white align-middle rounded-lg">Logo</div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 text-block-white">Sign in to your account</h2>
          </div>

          <div class="mt-6">
            <div>
              <button type="button" @click="login_web3" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" :class="class_connected()"  >
                    {{ this.web3Wallet && this.web3Wallet != "" ? 'Continue with '+this.$options.filters.address_ellipsis(this.web3Wallet) : 'Web3 Login (Wallet)' }}
              </button>
              <!-- <p class="text-xs text-gray-700 mt-1">Address: {{this.web3Wallet }}</p> -->
            </div>
          </div>
          <div class="relative mt-8">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-block-black-accent text-gray-500"> Or </span>
            </div>
          </div>
          <div class="mt-8">
            <div class="mt-6">
              <form action="#" method="POST" class="space-y-6" @submit.prevent="login">
                <div>
                  <label for="email" class="block text-sm font-medium text-block-white"> Email address </label>
                  <div class="mt-1">
                    <input ref="email" id="email" name="email" type="email" autocomplete="email" required class="w-full placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="user@blockify.com" />
                  </div>
                </div>

                <div class="space-y-1">
                  <label for="password" class="block text-sm font-medium text-block-white"> Password </label>
                  <div class="mt-1">
                    <input ref="password" id="password" name="password" type="password" autocomplete="current-password" required class="w-full placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="123" />
                  </div>
                </div>

                <div class="space-y-1 my-5">
                  <p class="text-green-500">{{ message }}</p>
                </div>

                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="remember-me" class="ml-2 block text-sm"> Remember me </label>
                  </div>

                  <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500"> Forgot your password? </a>
                  </div>
                </div>

                <div>
                  <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ this.isFetching ? 'Loading...' : 'Login' }}
                  </button>
                </div>
                <div class="mt-5">
                  <span class="text-red-600 text-sm text-center">{{ errorM }}</span>
                </div>
              </form>
            </div>
            <div class="mt-6">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                  <span class="px-2 bg-block-black-accent text-gray-500"> Or </span>
                </div>
              </div>

              <div class="mt-6">
                <div>
                  <router-link to="/register" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-blue-600 hover:bg-blue-200 border border-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"> Create an account </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="hidden lg:block relative w-0 flex-1 bg-block-black"></div>
    </div>
  </div>
</template>

<script>
import Widget from 'resources/js/components/Widget/Widget';
import { mapState, mapActions } from 'vuex';
import { serverBus } from 'resources/js/main';
import config from 'resources/js/config';
import router from 'resources/js/Routes';

export default {
  name: 'LoginPage',
  components: { Widget },
  data() {
    return {
      errorM: '',
      message: '',
    };
  },
  computed: {
    ...mapState(['auth']),
    ...mapState('auth', {
      isFetching: (state) => state.isFetching,
      errorMessage: (state) => state.errorMessage,
      web3Wallet: (state) => state.web3_wallet,
    }),
  },
  methods: {
    ...mapActions('auth', ['loginUser', 'receiveLogin', 'requestLoginReset','auth_update_web3']),
    _message: function () {
      if (this.$route.query.register == 'success') {
        this.message = 'Registration successfull. Please login!';
      }
    },
    login_web3(){

        const accounts = ethereum.request({ method: 'eth_requestAccounts' }).then(accounts=>{
            const account = accounts[0];
            if(account != ""){
                if(account != this.auth.web3_wallet){
                    console.log(account);
                this.auth_update_web3(account);
                } else {
                    this.login_and_redirect();
                }
            } else {
                this.auth_update_web3("");
            }
        }).catch(error => {
             this.auth_update_web3("");
        })
        .finally(() => {
            serverBus.$emit("hide_loader", "load");
        });
    },
    silent_check(){
        if(account != this.auth.web3_wallet){
                console.log(account);
            this.auth_update_web3(account);
            } else {
                this.login_and_redirect();
            }
    },
    class_connected(){
        if(this.web3Wallet && this.web3Wallet != ""){
            return "bg-green-700"
        } else{
            return "bg-blue-700"
        }
    },
    login_and_redirect(){
         router.push("app/dashboard");
    },
    login() {
      localStorage.removeItem('authenticated');
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.clear();
      document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
      const email = this.$refs.email.value;
      const password = this.$refs.password.value;

      //   router.push("app/dashboard");

      return;

      if (email.length !== 0 && password.length !== 0) {
        this.loginUser({ email, password })
          .then(({ data }) => {
            if (data.status == 'success') {
              this.$success_notification(data.message);
            } else {
              this.errorM = data.message;
            }
          })
          .catch((error) => {
            // this.errorM = error;
          });
      }
    },
  },
  created() {
    // console.log("Auth");
    // console.log(localStorage.getItem('authenticated'));
    // if (this.isAuthenticated(localStorage.getItem('authenticated'))) {
    //   router.push("app//dashboard")
    // }
  },
  mounted() {
    if (typeof window.ethereum !== 'undefined') {
    console.log('MetaMask is installed!');
    }
        this._message();
    this.requestLoginReset();
    const creds = config.auth;
    this.$refs.email.value = creds.email;
    this.$refs.password.value = creds.password;

    serverBus.$on('login_errors', (message) => {
      this.$error_notification(message);
    });
  },
};
</script>
