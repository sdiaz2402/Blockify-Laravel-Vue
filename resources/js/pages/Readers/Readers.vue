<template>
	<div class="dashboard-page">
	<nav class="text-sm font-semibold mb-6" aria-label="Breadcrumb">
              <ol class="list-none p-0 inline-flex">
                <li class="flex items-center text-blue-500">
                  <a href="#" class="text-gray-700">Home</a>
                  <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li class="flex items-center">
                  <a href="#" class="text-gray-600">Reading Club</a>
                </li>
              </ol>
            </nav>

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
<div class="bg-white shadow sm:rounded-lg">
  <div class="px-4 py-5 sm:p-6">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
      Find your friends!
    </h3>
    <div class="mt-2 max-w-xl text-sm text-gray-500">
      <p>
        Start typing to select your friend you want to add
      </p>
    </div>
    <form class="mt-5 sm:flex sm:items-center">
      <div class="w-full sm:max-w-xs">
        <label for="email" class="sr-only">Search</label>
        <input type="text" name="search" id="search" class="p-4 border shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-50 rounded-md" placeholder="Start typing to search..." v-model="search_query" v-on:keyup="search_">
      </div>
      <button type="button" class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="search_">
            <!-- By Sam Herbert (@sherb), for everyone. More @ http://goo.gl/7AJzbL -->
            <svg v-if="show_loading_search" width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#fff" class="animate-spin h-5 w-5 mr-3">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="2">
                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"/>
                        <path d="M36 18c0-9.94-8.06-18-18-18">

                        </path>
                    </g>
                </g>
            </svg>
        Search
      </button>
    </form>
    <!-- This example requires Tailwind CSS v2.0+ -->
<div class="mt-5 max-w-xl text-sm text-gray-500 mb-3" v-if="search_query!= ''">
      <p>
       Results
      </p>
    </div>
<div class="bg-white shadow overflow-scroll rounded-md" v-if="search_query!= ''" style="max-height:300px">
  <ul class="divide-y divide-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  First Name
              </th>
              <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Last Name
              </th>
              <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nickname
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Odd row -->
            <tr v-if="search_results.length == 0 && search_query!= ''">
                <td colspan="3" class="px-2 py-2 whitespace-nowrap text-sm text-gray-700 text-center">
                    No results found
                </td>
            </tr>
            <tr v-for="(row,index) in search_results" :key="row.id" :class="(index % 2 == 0) ? 'bg-white' : 'bg-gray-100'">
            <td class="px-2 py-2 whitespace-nowrap text-sm font-medium text-gray-700">
                {{row.first_name| capitalize}}
            </td>
              <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-700">
               {{row.last_name| capitalize}}
              </td>
              <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-700">
                @{{row.nickname}}
              </td>
              <td class="px-2 py-2 whitespace-nowrap text-right text-sm font-medium">
                <span @click="add(row.id)" target="_blank" class="text-blue-600 hover:text-blue-900 cursor-pointer">Add to my reading club</span>
              </td>
            </tr>
          </tbody>
        </table>

    <!-- More items... -->
  </ul>
</div>
  </div>
</div>

<h2 class="font-medium text-gray-900 truncate mb-5 mt-10">
    <a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">My Friends</a>
</h2>
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <p class="text-gray-600 text-center mt-10 mb-10" v-if="users.reading_club.length == 0"> You do not have any friends in your reading club </p>
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg" v-if="users.reading_club.length > 0">

        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="w-3 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  First Name
              </th>

              <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Last Name
              </th>
            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nickname
              </th>
               <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Added
              </th>
               <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

              </th>
              <th scope="col" class="relative px-2 py-2">
                <span class="sr-only">Remove</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Odd row -->
            <tr v-for="(row,index) in filteredList" :key="row.id" :class="(index % 2 == 0) ? 'bg-white' : 'bg-gray-50'">
              <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-700">
                {{ row.first_name | capitalize}}
              </td>

              <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-700">
                {{ row.last_name | capitalize}}
              </td>

              <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-700">
                @{{ row.nickname }}
              </td>
               <td class="px-2 py-2 whitespace-nowrap text-sm font-medium text-gray-500">
                {{ row.created_at | moment('timezone', date_timezone,datetime_format) }}
              </td>
               <td class="px-2 py-2 whitespace-nowrap text-right text-sm font-medium">
                <span @click="view(row.id,row)" target="_blank" class="text-blue-600 hover:text-blue-900 cursor-pointer">View Watchlist</span>
              </td>
              <td class="px-2 py-2 whitespace-nowrap text-right text-sm font-medium">
                <span @click="remove(row.id)" target="_blank" class="text-blue-600 hover:text-blue-900 cursor-pointer">Remove</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <ModalViewWatchlist :friend="friend" :objects="view_watchlist_id" :modal_enabled="modal_enabled"></ModalViewWatchlist>

</div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import { serverBus } from "resources/js/main";
import { globalStore } from "resources/js/main.js";
import ModalViewWatchlist from "@/components/ModalViewWatchlist/ModalViewWatchlist";

export default {
	name: "ReadingClub",
	components: {
        ModalViewWatchlist
    },
	data() {
		return {
			show_loading: true,
			show_loading_search: false,
			search_query: "",
			query: "",
            modal_enabled:false,
            search_results: [],
            view_watchlist_id: [],
            friend: {},

		};
	},
	methods: {
		...mapActions("users", ["reading_club","search_readers","add_reader","remove_reader"]),
		...mapActions("watchlist", ["view_watchlist"]),
        search: function (id) {
        this.show_loading_search = true;

        this.search_readers({ text: this.search_query })
            .then(({ data }) => {
                // console.log(data);
                if (data.status == "success") {
                    // console.log(data.data.response);
                    this.$success_notification(data.message);
                    this.search_results = data.response;
                    this.show_loading_search = false;
                } else {
                    // console.log("error");
                    // console.log(data.data.response);
                    this.show_loading = false;
                    this.show_loading_search = false;
                    this.$error_notification(data.message);
                }

                //
            })
            .catch((error) => {
                this.$error_notification(error);
                this.show_loading = false;
                this.$auth_check(error);
            })
            .finally(() => {
                this.show_loading_search = false;
            });

		},
        view: function(id,friend){
            this.friend = friend
            this.view_watchlist({ id: id })
					.then(({ data }) => {
						console.log(data);
						if (data.status == "success") {
							// console.log(data.data.response);
                            this.view_watchlist_id = data.response;
							this.modal_enabled = true;
						} else {
							console.log("error");
							// console.log(data.data.response);
							this.$error_notification(data.message);
						}

						//
					})
					.catch((error) => {
						this.$error_notification(error);
						this.show_loading = false;
						this.$auth_check(error);
					})
					.finally(() => {
						serverBus.$emit("hide_loader", "remove_stock");
					});

        },
		remove: function (id) {
			if (
				confirm(
					"Are you sure you want to remove this user from your reading club?"
				)
			) {
				serverBus.$emit("show_loader", "remove_stock");
				this.show_loading = true;
				this.remove_reader({ id: id })
					.then(({ data }) => {
						// console.log(data);
						if (data.status == "success") {
							// console.log(data.data.response);
							this.$success_notification(data.message);
							serverBus.$emit("show_loader", "remove_stock");
							this.reading_club().then(({ data }) => {
								this.show_loading = false;
								serverBus.$emit("hide_loader", "remove_stock");
								if (data.status == "success") {
									this.$success_notification(data.message);
								} else {
									this.$error_notification(data.message);
								}
							});
						} else {
							// console.log("error");
							// console.log(data.data.response);
							this.show_loading = false;
							this.$error_notification(data.message);
						}

						//
					})
					.catch((error) => {
						this.$error_notification(error);
						this.show_loading = false;
						this.$auth_check(error);
					})
					.finally(() => {
						serverBus.$emit("hide_loader", "remove_stock");
					});
			}
		},
        search_:function(){
            this.show_loading_search = true;
            this.debounce(this.search,1000);
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
        add: function (id) {
				serverBus.$emit("show_loader", "add_stock");
				this.show_loading = true;
				this.add_reader({ id: id })
					.then(({ data }) => {
						// console.log(data);
						if (data.status == "success") {
							// console.log(data.data.response);
							this.$success_notification(data.message);
                            this.show_loading_search = false;
                            this.search_query = "";
                            this.search_results = [];
                            serverBus.$emit("show_loader", "list_watchlist");
							this.reading_club().then(({ data }) => {
								this.show_loading = false;
								serverBus.$emit("hide_loader", "list_watchlist");
								if (data.status == "success") {
									this.$success_notification(data.message);
								} else {
									this.$error_notification(data.message);
								}
							});
						} else {
							// console.log("error");
							// console.log(data.data.response);
							this.show_loading = false;
							this.$error_notification(data.message);
						}

						//
					})
					.catch((error) => {
						this.$error_notification(error);
						this.show_loading = false;
						this.$auth_check(error);
					})
					.finally(() => {
						serverBus.$emit("hide_loader", "add_stock");
					});

		},
	},

	computed: {
		...mapState(["users"]),
		filteredList() {
			if (this.query == "") {
				return this.users.reading_club;
			} else {
				return this.users.reading_club.filter((object) => {
					if (
						object.first_name
							.toLowerCase()
							.includes(this.query.toLowerCase())
					) {
						return object;
					}

					if (
						object.last_name
							.toLowerCase()
							.includes(this.query.toLowerCase())
					) {
						return object;
					}
                    	if (
						object.nickname
							.toLowerCase()
							.includes(this.query.toLowerCase())
					) {
						return object;
					}
				});
			}
		},
	},
	mounted() {
		this.reading_club()
			.then(({ data }) => {
				this.show_loading = false;
			})
			.catch((error) => {
				this.$auth_check(error);
				this.$error_notification(error);
			});
	},
};
</script>
<style>
.cursor {
	cursor: pointer;
}
</style>
<style src="./Readers.scss" lang="scss" />


