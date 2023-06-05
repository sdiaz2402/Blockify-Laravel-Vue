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
        <div class="mt-3 text-center sm:mt-5">
          <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
            {{friend.first_name }} {{friend.last_name }}'s Watchlist
          </h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500 text-left">
              Feel free to watch the same tickers as your friend
            </p>
          </div>

        </div>
        <table class="min-w-full divide-y divide-gray-200 mt-5">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="w-3 px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

              </th>
              <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ticker
              </th>

              <th scope="col" class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>

              <th scope="col" class="relative px-2 py-1">
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Odd row -->
            <tr v-for="(row,index) in objects" :key="row.id" :class="(index % 2 == 0) ? 'bg-white' : 'bg-gray-50'">
              <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-500">
                <div class="h-8 w-8 rounded-full flex relative mr-2"  v-if="row.logo != ''" >
                    <img class="h-8 w-8 rounded-full" :src="row.logo" alt="">
                </div>
                <div class="h-8 w-8 rounded-full border-2 flex p-3 relative mr-2"  v-if="row.logo == ''">
                    <div class="absolute top-0 left-7" >
                        {{row.name[0]}}
                    </div>
                </div>
              </td>
              <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700">
                ${{ row.name | capitalize}}
              </td>

              <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700">
                {{ row.context_name | capitalize}}
              </td>
               <td class="px-2 py-1 whitespace-nowrap text-right text-sm font-medium">
                <span><svg class="fill-current hover:hover:text-black " :class="row.favorite ==1 ? 'text-yellow-500' : 'text-gray-300'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .288l2.833 8.718h9.167l-7.417 5.389 2.833 8.718-7.416-5.388-7.417 5.388 2.833-8.718-7.416-5.389h9.167z"/></svg></span>
              </td>
              <td class="px-2 py-1 whitespace-nowrap text-right text-sm font-medium">
                <span @click="add_watchlist(row.id)" target="_blank" class="text-indigo-600 hover:text-indigo-900 cursor-pointer">Add to my watchlist</span>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-5 sm:mt-6 flex">
        <button @click="_close()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-smm mr-2">
          Close
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
import moment from 'moment';
import Widget from '@/components/Widget/Widget';

export default {
	name: "ModalViewWatchlist",
	components: {
		Widget
	},
	props: {
		callback: null,
        modal_enabled: false,
        objects: [],
        friend: {first_name:"",last_name:""},

	},

	data() {
		return {

			selected_news: {},
            email:""
		};
	},

	methods: {
        ...mapActions("watchlist", ["my_watchlist","add_stock"]),
        ...mapActions("news", ["share_news"]),
        resetModal() {
			this.email = "";
		},
        add_watchlist: function (id) {
			serverBus.$emit("show_loader", "add_stock");
			this.show_loading = true;
			this.add_stock({ id: id })
				.then(({ data }) => {
					// console.log(data);
					if (data.status == "success") {
						// console.log(data.data.response);
						this.$success_notification(data.message);
						this.show_loading_search = false;
						this.search_query = "";
						this.search_results = [];
						serverBus.$emit("show_loader", "list_watchlist");
						this.my_watchlist().then(({ data }) => {
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
        _close(){
            this.resetModal();
            this.modal_enabled = false;
        },
        handle_ok_modal() {
			this.email = "";

		},
        handle_submit_modal() {
			this.email = "";

		},

	},
    computed: {
	},
	mounted() {},
	watch: {

	}
};
</script>
<style src="./ModalViewWatchlist.scss" lang="scss" />
