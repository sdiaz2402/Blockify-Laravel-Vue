<template>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-40 inset-0 overflow-y-auto" :class="modal_enabled ? 'block' : 'hidden'" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Share the news
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 text-left">
                                An email with the news snippted will be sent to your friend
                            </p>
                        </div>
                        <div class="mt-2">
                            <p class=" text-gray-800 text-left">
                                {{ news.text }}
                            </p>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <input type="text" name="first-name" id="first-name" placeholder="Email address" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-50 rounded-md p-3 border" v-model="email" v-on:keyup="search_">
                            </div>
                        </div>
                        <div class="bg-white shadow overflow-scroll mt-1 rounded-md" v-if="flag_suggestions" style="max-height:300px">
                            <ul class="divide-y divide-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-300 lowercase tracking-wider">
                                                Suggestions
                                            </th>
                                            <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            </th>

                                            <th scope="col" class="relative px-2 py-1"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Odd row -->
                                        <tr v-if="search_results.length == 0 && email != ''">
                                            <td colspan="2" class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 text-center">
                                                No results found
                                            </td>
                                        </tr>
                                        <tr v-for="(row, index) in search_results" :key="row.id" :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-100'">


                                            <td class="px-1 py-2 whitespace-nowrap text-sm text-gray-700 text-left cursor-pointer" @click="set_email(row.email)">
                                                {{ row.email | capitalize }}
                                            </td>
                                            <td class="px-1 py-2 whitespace-nowrap text-sm text-gray-700 cursor-pointer" @click="remove_email(row.email)">
                                                <svg class="fill-current text-red-300 h-3 w-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- More items... -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 flex">
                    <button @click="_close()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-smm mr-2">
                        Close
                    </button>
                    <button @click="_share()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm ml-2">
                        Share
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import config from "@/config";
import { serverBus } from "@/main";
import { mapState, mapActions } from "vuex";
import moment from "moment";
import Widget from "@/components/Widget/Widget";

export default {
    name: "ModalShare",
    components: {
        Widget
    },
    props: {
        callback: null,
        modal_enabled: false,
        news: {}
    },

    data() {
        return {
            selected_news: {},
            email: "",
            flag_suggestions: false,
            search_results: []
        };
    },

    methods: {
        ...mapActions("news", ["share_news"]),
        resetModal() {
            this.email = "";
        },
        _close() {
            this.resetModal();
            this.modal_enabled = false;
            this.$emit('close_modal', true)
        },
        _share() {
            this.share_news({ news: this.news, email: this.email })
                .then(({ data }) => {
                    this.resetModal();
                    this.$success_notification(data.message);
                    this.modal_enabled = false;
                    serverBus.$emit("show_loader", "hide");
                })
                .catch(error => {
                    this.$auth_check(error);
                    this.$error_notification(error);
                })
                .finally(() => {
                    serverBus.$emit("hide_loader", "load");
                });
        },
        set_email: function(email){
            this.email = email;
            this.flag_suggestions = false;
        },

        search_: function () {
			this.show_loading_search = true;
			this.debounce(this.search, 1000);
		},
        debounce: function (fn, delay, arg) {
			if (this.timeout) clearTimeout(this.timeout);
			var args = arg;
			var that = this;
			this.timeout = setTimeout(function () {
				// console.log("tiggering");
				fn.apply(that, args);
			}, delay);
		},
        search: function(email) {
            this.show_loading_search = true;
            this.$axios
                .post(config.baseURLApi+"/share/search", { email: this.email })
                .then(({ data }) => {
                    console.log(data);
                    if (data.data.status == "success") {
                        this.$success_notification(data.message);
                        this.search_results = data.data.response;
                        console.log(this.search_result);
                        if(this.search_results.length > 0){
                            this.flag_suggestions = true;
                        } else {
                            this.flag_suggestions = false;
                        }
                        this.show_loading_search = false;
                        // console.log(this.search_results);
                    } else {
                        this.show_loading = false;
                        this.show_loading_search = false;
                        this.$error_notification(data.data.message);
                    }

                    //
                })
                .catch(error => {
                    this.$error_notification(error);
                    this.show_loading = false;
                    this.$auth_check(error);
                })
                .finally(() => {
                    this.show_loading_search = false;
                });
        },
        remove_email: function(email) {
            this.show_loading_search = true;
            this.$axios
                .post(config.baseURLApi+"/share/remove", { email: email })
                .then(({ data }) => {
                    console.log(data);
                    if (data.data.status == "success") {
                        this.search(this.email);
                    } else {
                        this.show_loading = false;
                        this.show_loading_search = false;
                        this.$error_notification(data.data.message);
                    }

                    //
                })
                .catch(error => {
                    this.$error_notification(error);
                    this.show_loading = false;
                    this.$auth_check(error);
                })
                .finally(() => {
                    this.show_loading_search = false;
                });
        },

        handle_ok_modal() {
            this.email = "";
        },
        handle_submit_modal() {
            this.email = "";
        }
    },
    computed: {},
    mounted() {},
    watch: {}
};
</script>
<style src="./ModalShareNews.scss" lang="scss" />
