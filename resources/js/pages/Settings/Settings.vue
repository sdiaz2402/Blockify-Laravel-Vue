<template>
	<div class="dashboard-page">
		<h1 class="page-title">
			Settings
			<small></small>
		</h1>
        <b-row class="mt-5">


<div class="hidden sm:block" aria-hidden="true">
  <div class="py-5">
    <div class="border-t border-gray-200"></div>
  </div>
</div>

<div class="mt-10 sm:mt-0">
  <div class="md:grid md:grid-cols-4 md:gap-6">
    <div class="md:col-span-1">
      <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Account Information</h3>
        <p class="mt-1 text-sm text-gray-600">
          Use a permanent address where you can receive mail.
        </p>
      </div>
    </div>
    <div class="mt-5 md:mt-0 md:col-span-3">
        <div class="shadow overflow-hidden sm:rounded-md">
          <div class="px-4 py-5 bg-white sm:p-6">
            <div class="grid grid-cols-6 gap-6">
              <div class="col-span-6 sm:col-span-3">
                <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                <input type="text" name="first-name" id="first-name" autocomplete="given-name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-50 rounded-md p-3 border"  :value="_first_name"  @input="e => _first_name = e.target.value">
              </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                <input type="text" name="last-name" id="last-name" autocomplete="family-name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-50 rounded-md p-3 border"  :value="_last_name" @input="e => _last_name = e.target.value">
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-50 rounded-md p-3 border"  :value="_email" @input="e => _email = e.target.value">
              </div>


              <div class="col-span-6 sm:col-span-4">
                <label for="email-address" class="block text-sm font-medium text-gray-700">Nickname</label>
                <div class="flex">
                     <div class="mr-1 inline-flex items-center text-gray-500 text-lg">@</div> <input type="text" name="nickname" id="nickname" autocomplete="nickname" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-50 rounded-md p-3 border"  :value="_nickname" @input="e => _nickname = e.target.value">
                    <div class="ml-5 inline-flex items-center" v-if="show && nickname_status"><svg class="fill-current text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg></div>
                    <div class="ml-5 inline-flex items-center" v-if="show && !nickname_status"><svg class="fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg></div>
                </div>
              </div>

              <div class="col-span-6 sm:col-span-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Change Password</label>
                <input type="password" name="password" id="password" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-50 border rounded-md p-3"  :value="_password" @input="e => _password = e.target.value">
              </div>

              <!-- <div class="col-span-6 sm:col-span-4">
              <label class="block text-sm font-medium text-gray-700">
                Photo
              </label>
              <div class="mt-1 flex items-center">
                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                  <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </span>
                <button type="button" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md p-3 shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Change
                </button>
              </div>
            </div>

              <div class="col-span-6 sm:col-span-3">
                <label for="country" class="block text-sm font-medium text-gray-700">Country / Region</label>
                <select id="country" name="country" autocomplete="country" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  <option>United States</option>
                  <option>Canada</option>
                  <option>Mexico</option>
                </select>
              </div>

              <div class="col-span-6">
                <label for="street-address" class="block text-sm font-medium text-gray-700">Street address</label>
                <input type="text" name="street-address" id="street-address" autocomplete="street-address" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
              </div>

              <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" name="city" id="city" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
              </div>

              <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <label for="state" class="block text-sm font-medium text-gray-700">State / Province</label>
                <input type="text" name="state" id="state" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
              </div>

              <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                <label for="postal-code" class="block text-sm font-medium text-gray-700">ZIP / Postal</label>
                <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3">
              </div> -->
            </div>
          </div>

          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="button" @click="save_user" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Save
            </button>
          </div>
        </div>
    </div>
  </div>
</div>

<div class="hidden sm:block" aria-hidden="true">
  <div class="py-5">
    <div class="border-t border-gray-200"></div>
  </div>
</div>

<div class="mt-10 sm:mt-0">
  <div class="md:grid md:grid-cols-4 md:gap-6">
    <div class="md:col-span-1">
      <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Notifications</h3>
        <p class="mt-1 text-sm text-gray-600">
          Decide which communications you'd like to receive and how.
        </p>
      </div>
    </div>
    <div class="mt-5 md:mt-0 md:col-span-3">
      <form action="#" method="POST">
        <div class="shadow overflow-hidden sm:rounded-md">
          <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
            <fieldset>
              <legend class="text-base font-medium text-gray-900">By Email</legend>
              <div class="mt-4 space-y-4">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input v-model="_mid_session" @input="e => _mid_session = e.target.value" id="mid_session" name="mid_session" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="comments" class="font-medium text-gray-700">Mid Session Summary</label>
                    <p class="text-gray-500">Summary email sent at 12:30pm EST</p>
                  </div>
                </div>
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input v-model="_closing_session" @input="e => _clossing_session = e.target.value" id="closing_session" name="closing_session" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="candidates" class="font-medium text-gray-700">Close Session Summary</label>
                    <p class="text-gray-500">Summary email sent at 4:00pm EST</p>
                  </div>
                </div>
              </div>
            </fieldset>
            <!-- <fieldset>
              <div>
                <legend class="text-base font-medium text-gray-900">Push Notifications</legend>
                <p class="text-sm text-gray-500">These are delivered via SMS to your mobile phone.</p>
              </div>
              <div class="mt-4 space-y-4">
                <div class="flex items-center">
                  <input id="push-everything" name="push-notifications" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                  <label for="push-everything" class="ml-3 block text-sm font-medium text-gray-700">
                    Everything
                  </label>
                </div>
                <div class="flex items-center">
                  <input id="push-email" name="push-notifications" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                  <label for="push-email" class="ml-3 block text-sm font-medium text-gray-700">
                    Same as email
                  </label>
                </div>
                <div class="flex items-center">
                  <input id="push-nothing" name="push-notifications" type="radio" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                  <label for="push-nothing" class="ml-3 block text-sm font-medium text-gray-700">
                    No push notifications
                  </label>
                </div>
              </div>
            </fieldset> -->
          </div>
          <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="button"  @click="save_notifications" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              Save
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


		</b-row>
	</div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import { serverBus } from "resources/js/main";
import { globalStore } from "resources/js/main.js";

export default {
	name: "Settings",
	components: {

	},
	data() {
		return {
            nickname_status:true,
            timeout:null,
            show:false
		};
	},
	methods: {
		...mapActions("auth", ["update_user","update_notifications","refresh_user"]),
        save_user:function(){
            // console.log("Diego")
            // this.$error_notification("Test on error");
            // this.$success_notification("Test on error");
            if(this.nickname_status){
                serverBus.$emit("show_loader", "save_user");
                this.update_user(this.auth.user).then(({ data }) => {
                    serverBus.$emit("hide_loader", "save_user");
                if (data.status == "success") {
                        this.$success_notification(data.message);
                        this.auth.user.password = "";
                } else {
                    this.$error_notification(data.message);
                }
                })
                .catch((error) => {
                    this.$auth_check(error);
                    this.$error_notification(error);
                });
            }
        },

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
            var object = {"nickname":this.auth.user.nickname};
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
                    this.$auth_check(error);
                })
                .finally(() => {

                });

        },
        save_notifications:function(){
            serverBus.$emit("show_loader", "save_notifications");
            this.update_notifications(this.auth.user).then(({ data }) => {
                // console.log(data.status);
                serverBus.$emit("hide_loader", "save_notifications");
			 if (data.status == "success") {
                    this.$success_notification(data.message);
            } else {
                this.$error_notification(data.message);
            }
			})
			.catch((error) => {
				this.$auth_check(error);
				this.$error_notification(error);
			});
        }
	},

	computed: {
		...mapState(["auth"]),
        _nickname: {
            get() {
                return this.auth.user.nickname;
            },
            set(val) {
                this.auth.user.nickname = val;
                this.debounce(this.check_nickname,400);
            }
        },
       _first_name: {
            get() {
                return this.auth.user.first_name;
            },
            set(val) {
                this.auth.user.first_name = val
            }
        },
        _last_name: {
            get() {
                return this.auth.user.last_name;
            },
            set(val) {
                this.auth.user.last_name = val
            }
        },
        _email: {
            get() {
                return this.auth.user.email;
            },
            set(val) {
                this.auth.user.email = val
            }
        },
        _password: {
            get() {
                return "";
            },
            set(val) {
                this.auth.user.password = val
            }
        },
         _mid_session: {
            get() {
                if(this.auth.user.mid_session == 1){
                    return true;
                }
                return false;
            },
            set(val) {
                if(val == true){
                    this.auth.user.mid_session = 1;
                } else {
                    this.auth.user.mid_session = 0;
                }

            }
         },
         _closing_session: {
            get() {
                if(this.auth.user.closing_session == 1){
                    return true;
                }
                return false;
            },
            set(val) {
                if(val == true){
                    this.auth.user.closing_session = 1;
                } else {
                    this.auth.user.closing_session = 0;
                }

            }
        }
	},
	mounted() {
        this.refresh_user();
	},
};
</script>
<style>
.cursor {
	cursor: pointer;
}
</style>
<style src="./Settings.scss" lang="scss" />


