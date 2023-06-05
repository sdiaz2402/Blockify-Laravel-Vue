<template>
	<!-- give the sidebar z-50 class so its higher than the navbar if you want to see the logo -->
	<!-- you will need to add a little "X" button next to the logo in order to close it though -->
	<div class="w-1/3 md:w-1/3 lg:w-64 overflow-y-scroll fixed md:top-0 md:left-0 h-screen lg:block z-30 pb-20" :class="layout.sideBarOpen ? '' : 'hidden'" id="main-nav">
		<div class="w-full h-30 flex px-4 items-center mb-8 mt-8">
			<p class="font-semibold text-3xl text-white-accent  pl-4 text-center">
				<div class="w-24 py-10 bg-block-black-accent m-auto text-center text-block-white align-middle rounded-lg">
                    Logo
                </div>
			</p>
		</div>

		<div class="mb-4 px-4">

			<div class="w-full flex items-center text-white-accent h-10 pl-4 bg-block-black-accent hover:bg-block-black-accent rounded-lg cursor-pointer">

				<span class=""><button @click="close_sidebar('/admin/sources')" >Sources</button></span>
			</div>
            <div class="w-full flex items-center text-white-accent h-10 pl-4 bg-block-black-accent hover:bg-block-black-accent rounded-lg cursor-pointer mt-2">
				<span class=""><button @click="close_sidebar('/admin/sources_category')" >Category Sources</button></span>
			</div>
		</div>

	</div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import router from 'resources/js/Routes';
import { serverBus } from "resources/js/main";

export default {
	name: "Sidebar",
	data() {
		return {
			tickers_count: {},
            polling:null
		};
	},
	methods: {
		...mapActions("watchlist", ["my_watchlist_unread", "my_watchlist", "mark_all_read"]),
		debug: function (what) {
			// console.log(what);
		},
		close_sidebar: function (link="") {
			this.layout.sideBarOpen = false;
            if(link!=""){
                router.push(link);
            }
		},
        pollData () {
            console.log(this.polling);
            if(this.polling == null || this.polling == undefined){
                 this.polling = setInterval(() => {
                    this.my_watchlist();
                }, 600000);

            }
        },
		_mark_all_read: function () {
			serverBus.$emit("show_loader", "load");
			this.show_loading_selected_news = true;
			this.mark_all_read().then(({ data }) => {
				if (data.status == "success") {
					this.$success_notification(data.message);
				} else {
					this.$error_notification(data.message);
				}
				serverBus.$emit("hide_loader", "load");
				this.show_loading_selected_news = false;
				this.my_watchlist_unread();
			});
		},
	},
	computed: {
		...mapState(["auth"]),
		...mapState(["watchlist"]),
		...mapState(["layout"]),
        ...mapGetters('watchlist', [
      'favorites',
      'watch'
    ]),
	},
    beforeDestroy () {
        // clearInterval(this.polling)
    },
    created () {
        //
        // this.pollData();
    },
	mounted() {

		// this.my_watchlist()
		// 	.then(({ data }) => {})
		// 	.catch((error) => {});

	},
};
</script>
