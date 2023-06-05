<template>
  <div class="auth-page">
      <!--
  This example requires Tailwind CSS v2.0+

  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
   <img class="" style="width:100px; margin:auto" src="/images/logo.png"  />
    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
      Create an account
    </h2>

  </div>

  <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
      <div class="space-y-6">
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">
                Email address
              </label>
              <div class="mt-1">
                <input ref="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" v-model="user.email">
              </div>
            </div>

            <div>
              <label for="first_name" class="block text-sm font-medium text-gray-700">
                  First Name
              </label>
              <div class="mt-1">
                <input ref="first_name" id="first_name" name="first_name" type="first_name" autocomplete="first_name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" v-model="user.first_name">
              </div>
            </div>

            <div>
              <label for="last_name" class="block text-sm font-medium text-gray-700">
                Last Name
              </label>
              <div class="mt-1">
                <input ref="last_name" id="last_name" name="last_name" type="last_name" autocomplete="last_name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" v-model="user.last_name">
              </div>
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">
                Reading Club Nickname
              </label>
              <div class="mt-1 flex">
                <span class="inline-flex text-gray-500 pt-2 text-lg mr-1" >@</span>
                <input ref="nickname" id="nickname" name="nickname" type="nickname" autocomplete="nickname" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"  :value="_nickname" @input="e => _nickname = e.target.value">
                 <div class="ml-5 inline-flex items-center" v-if="show && nickname_status"><svg class="fill-current text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>
                <div class="ml-5 inline-flex items-center" v-if="show && !nickname_status"><svg class="fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg></div>
              </div>
            </div>

            <div class="space-y-1">
              <label for="password" class="block text-sm font-medium text-gray-700">
                Password
              </label>
              <div class="mt-1">
                <input ref="password" id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" v-model="user.password">
              </div>
            </div>

            <div class="space-y-1">
              <label for="password" class="block text-sm font-medium text-gray-700">
                Confirm Password
              </label>
              <div class="mt-1">
                <input :class="flag_password ? 'border border-red-600' : ''" ref="confirm_password" id="confirm_password" name="confirm_password" type="password" autocomplete="confirm_password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" v-model="user.confirm_password">
              </div>
              <p v-if="flag_password" class="text-xs text-red-600 text-left mt-1">Passwords need to be the same</p>
            </div>

            <div class="flex items-center justify-between">


              <div class="text-sm">
                 <router-link to="/login"  class="font-medium text-blue-600 hover:text-blue-500">
                  Already have an account? Sign in!
                </router-link >
              </div>
            </div>

            <div>
              <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" @click="_register()">
                {{this.isFetching ? 'Loading...' : (this.succeeded ? 'Click to Login' : "Create an account" )}}
              </button>
            </div>
            <div class="mt-5">
                <span class="text-red-600 text-sm text-center">{{ errorM }}</span>
                <ul>
                    <li class="text-red-600 text-sm text-center" v-for="(object,index) in errors" :key="index">{{object}}</li>
                    </ul>
             </div>
          </div>

      <!-- <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">
              Or continue with
            </span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-3 gap-3">
          <div>
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Sign in with Facebook</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>

          <div>
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Sign in with Twitter</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
              </svg>
            </a>
          </div>

          <div>
            <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
              <span class="sr-only">Sign in with GitHub</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</div>


  </div>
</template>

<script>
import Widget from 'resources/js/components/Widget/Widget';
import {mapState, mapActions} from 'vuex';
import { serverBus } from "resources/js/main";
import config from 'resources/js/config';
import router from 'resources/js/Routes';

export default {
  name: 'LoginPage',
  components: { Widget },
  data() {
		return {
            nickname_status:true,
            timeout:null,
            isFetching:false,
            show:false,
            errorM:"",
            succeeded:false,
            errors:[],
            same_password:true,
            user:{
                email:"",
                first_name:"",
                last_name:"",
                nickname:"",
                password:"",
                confirm_password:""

            }
		};
	},
  computed: {
    ...mapState('auth'),
  },
  methods: {
    reset:function(){
        this.user = {
                 email:"",
                first_name:"",
                last_name:"",
                nickname:"",
                password:"",
                confirm_password:""

            }
    },
    ...mapActions('auth', ['register',"loginUser"]),
    debounce:function(fn, delay) {
            if (this.timeout)
                clearTimeout(this.timeout);
            var args = arguments
            var that = this
            this.timeout = setTimeout(function () {
                // console.log("tiggering");
                fn.apply(that, args)
            }, delay);
        },
     check_nickname:function(){
            this.show = true;
            var object = {"nickname":this.user.nickname};
            this.$axios
                .post(config.baseURLApi + "/user/nickname", object,
                { headers: config.headers })
                .then(({ data }) => {
                    // console.log(data);
                    if (data.data.status == "success") {
                        this.nickname_status = true;
                    } else {
                        this.nickname_status = false;
                    }
                })
                .catch(error => {
                    this.$error_notification(error);
                })
                .finally(() => {

                });

        },
    validate_form:function(){

        if(this.user.email == "" | this.user.first_name == "" | this.user.last_name == ""| this.user.password == ""| this.user.nickname == ""){
            this.errors.push("No empty fields allowed");
            this.isFetching = false;
            return false;
        }
        if(this.validateEmail(this.user.email)){
            this.errors.push("Please enter a valid email address");
            this.isFetching = false;
            return false;
        }

        if(!this.nickname_status){
            this.errors.push("Please choose a valid nickname");
            this.isFetching = false;
            return false;
        }
        return true;
    },
    validateEmail() {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) {
            return true;
        } else {
            return false;
        }
    },
    _register:function(){
        localStorage.removeItem('authenticated');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.clear()
        document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        this.isFetching = true;
        if(this.succeeded){
            this.loginUser({email:this.user.email, password:this.user.password}).then(( {data} ) => {
                        console.log(data);
                        if (data.status == "success") {
                                this.$success_notification(data.message);
                                this.isFetching = false;
                        } else {
                            this.errorM = data.message;
                        }
                        })
                        .catch((error) => {
                            // this.errorM = error;
                        });
        }
        if(this.validate_form()){
            this.register(this.user)
            .then(({ data }) => {
                console.log(data);
                this.succeeded = true;
                if (data.status == "success") {
                    this.$success_notification(data.message);

                    this.loginUser({email:this.user.email, password:this.user.password}).then(( {data} ) => {
                        console.log(data);
                        if (data.status == "success") {
                                this.$success_notification(data.message);

                        } else {
                            this.errorM = data.message;
                        }
                        })
                        .catch((error) => {
                            console.log(error);
                            router.push('/login?register=success');
                        });
                } else {
                    this.isFetching = false;
                    this.errorM = data.message;
                    this.$error_notification(data.message);
                }
            })
            .catch((error) => {
                this.isFetching = false;
                this.$error_notification(error);
            })
            .finally(() => {
                this.isFetching = false;
            });
        }
    }
  },
  created() {

  },
  computed:{
      flag_password:function(){
          if(this.user.confirm_password != this.user.password && this.user.password != ""){
              return true
          }
          return false;
      },
      _nickname: {
            get() {
                return this.user.nickname;
            },
            set(val) {
                this.user.nickname = val;
                this.debounce(this.check_nickname,400);
            }
        },
  },
  mounted() {
      this.reset();
  }
};
</script>
