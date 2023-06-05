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
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 20 20">
					<path d="M18.121,9.88l-7.832-7.836c-0.155-0.158-0.428-0.155-0.584,0L1.842,9.913c-0.262,0.263-0.073,0.705,0.292,0.705h2.069v7.042c0,0.227,0.187,0.414,0.414,0.414h3.725c0.228,0,0.414-0.188,0.414-0.414v-3.313h2.483v3.313c0,0.227,0.187,0.414,0.413,0.414h3.726c0.229,0,0.414-0.188,0.414-0.414v-7.042h2.068h0.004C18.331,10.617,18.389,10.146,18.121,9.88 M14.963,17.245h-2.896v-3.313c0-0.229-0.186-0.415-0.414-0.415H8.342c-0.228,0-0.414,0.187-0.414,0.415v3.313H5.032v-6.628h9.931V17.245z M3.133,9.79l6.864-6.868l6.867,6.868H3.133z"></path>
				</svg>
				<span class=""><button @click="close_sidebar('/app/dashboard')" >Dashboard</button></span>
			</div>

			<div class="w-full flex items-center text-white-accent h-10 pl-4 hover:bg-block-black-accent rounded-lg cursor-pointer">
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 20 20">
					<path d="M17.431,2.156h-3.715c-0.228,0-0.413,0.186-0.413,0.413v6.973h-2.89V6.687c0-0.229-0.186-0.413-0.413-0.413H6.285c-0.228,0-0.413,0.184-0.413,0.413v6.388H2.569c-0.227,0-0.413,0.187-0.413,0.413v3.942c0,0.228,0.186,0.413,0.413,0.413h14.862c0.228,0,0.413-0.186,0.413-0.413V2.569C17.844,2.342,17.658,2.156,17.431,2.156 M5.872,17.019h-2.89v-3.117h2.89V17.019zM9.587,17.019h-2.89V7.1h2.89V17.019z M13.303,17.019h-2.89v-6.651h2.89V17.019z M17.019,17.019h-2.891V2.982h2.891V17.019z"></path>
				</svg>
				<span class=""><button @click="close_sidebar('/app/watchlist')" >Portfolio</button></span>
			</div>
			<div class="w-full flex items-center text-white-accent h-10 pl-4 hover:bg-block-black-accent rounded-lg cursor-pointer">
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 24 24">
					<path d="M21.06 16.91c0 .232-.012.432-.025.628-.503-1.725-1.315-2.131-3.413-2.688 3.438 0 3.438.847 3.438 2.06zm2.94-3.195v1.462c-.328.276-.999.447-1.001 1.418-.006 3.827-1.588 4.387-4.604 4.405-4.05 0-4.597-.927-5.273-4.148-.15-.715-.513-1.148-1.122-1.148s-.972.434-1.122 1.148c-.677 3.221-1.224 4.148-5.274 4.148-3.016-.018-4.598-.578-4.604-4.405-.001-.971-.672-1.142-1-1.418v-1.462s4.292-8.215 4.837-9.246c.53-1.005 1.208-1.469 1.934-1.469 1.06 0 2.093.933 2.229 2.332.01.125-.097.126-.206.126-.332 0-.717-.205-.801-.526-.182-.697-.586-.98-1.023-1.045-.558-.083-.948.312-1.352 1.16-.623 1.312-2.88 6.522-3.66 8.327 1.412-.217 2.909-.374 4.341-.374 3.236 0 3.525.934 5.701.923 2.176.011 2.465-.923 5.701-.923 1.432 0 2.929.157 4.341.374-.78-1.805-3.037-7.016-3.66-8.327-.403-.848-.794-1.242-1.352-1.16-.438.065-.841.348-1.023 1.045-.084.321-.469.526-.801.526-.109 0-.216-.002-.205-.127.136-1.398 1.169-2.331 2.228-2.331.726 0 1.403.464 1.934 1.469.545 1.031 4.837 9.246 4.837 9.246zm-14.19 1.313c-.405-.587-1.586-1.028-3.511-1.028-4.299 0-4.299 1.058-4.299 2.574 0 2.856.77 3.409 3.609 3.426 3.608.026 3.736-.754 4.297-3.416.152-.72.119-1.243-.096-1.556zm12.19 1.546c0-1.516 0-2.574-4.299-2.574-1.925 0-3.106.441-3.511 1.028-.216.312-.248.836-.097 1.556.561 2.661.688 3.442 4.297 3.416 2.84-.017 3.61-.57 3.61-3.426zm-19.06.336c0 .232.012.432.025.628.503-1.725 1.315-2.131 3.413-2.688-3.438 0-3.438.847-3.438 2.06z"/>
				</svg>
				<span class=""><button @click="close_sidebar('/app/readers')">Explore</button></span>
			</div>
            <div class="w-full flex items-center text-white-accent  h-10 pl-4 hover:bg-block-black-accent rounded-lg cursor-pointer">
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 24 24">
					<path d="M17.283,5.549h-5.26V4.335c0-0.222-0.183-0.404-0.404-0.404H8.381c-0.222,0-0.404,0.182-0.404,0.404v1.214h-5.26c-0.223,0-0.405,0.182-0.405,0.405v9.71c0,0.223,0.182,0.405,0.405,0.405h14.566c0.223,0,0.404-0.183,0.404-0.405v-9.71C17.688,5.731,17.506,5.549,17.283,5.549 M8.786,4.74h2.428v0.809H8.786V4.74z M16.879,15.26H3.122v-4.046h5.665v1.201c0,0.223,0.182,0.404,0.405,0.404h1.618c0.222,0,0.405-0.182,0.405-0.404v-1.201h5.665V15.26z M9.595,9.583h0.81v2.428h-0.81V9.583zM16.879,10.405h-5.665V9.19c0-0.222-0.183-0.405-0.405-0.405H9.191c-0.223,0-0.405,0.183-0.405,0.405v1.215H3.122V6.358h13.757V10.405z"></path>
				</svg>
				<span class=""><button @click="close_sidebar('/app/sources')">Swap</button></span>
			</div>
			<div class="w-full flex items-center text-white-accent  h-10 pl-4 hover:bg-block-black-accent rounded-lg cursor-pointer">
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 20 20">
					<path d="M17.283,5.549h-5.26V4.335c0-0.222-0.183-0.404-0.404-0.404H8.381c-0.222,0-0.404,0.182-0.404,0.404v1.214h-5.26c-0.223,0-0.405,0.182-0.405,0.405v9.71c0,0.223,0.182,0.405,0.405,0.405h14.566c0.223,0,0.404-0.183,0.404-0.405v-9.71C17.688,5.731,17.506,5.549,17.283,5.549 M8.786,4.74h2.428v0.809H8.786V4.74z M16.879,15.26H3.122v-4.046h5.665v1.201c0,0.223,0.182,0.404,0.405,0.404h1.618c0.222,0,0.405-0.182,0.405-0.404v-1.201h5.665V15.26z M9.595,9.583h0.81v2.428h-0.81V9.583zM16.879,10.405h-5.665V9.19c0-0.222-0.183-0.405-0.405-0.405H9.191c-0.223,0-0.405,0.183-0.405,0.405v1.215H3.122V6.358h13.757V10.405z"></path>
				</svg>
				<span class=""><button @click="close_sidebar('/app/filters')">Baskets</button></span>
			</div>

            <div class="w-full flex items-center text-white-accent  h-10 pl-4 hover:bg-block-black-accent rounded-lg cursor-pointer">
				<svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 20 20">
					<path d="M17.283,5.549h-5.26V4.335c0-0.222-0.183-0.404-0.404-0.404H8.381c-0.222,0-0.404,0.182-0.404,0.404v1.214h-5.26c-0.223,0-0.405,0.182-0.405,0.405v9.71c0,0.223,0.182,0.405,0.405,0.405h14.566c0.223,0,0.404-0.183,0.404-0.405v-9.71C17.688,5.731,17.506,5.549,17.283,5.549 M8.786,4.74h2.428v0.809H8.786V4.74z M16.879,15.26H3.122v-4.046h5.665v1.201c0,0.223,0.182,0.404,0.405,0.404h1.618c0.222,0,0.405-0.182,0.405-0.404v-1.201h5.665V15.26z M9.595,9.583h0.81v2.428h-0.81V9.583zM16.879,10.405h-5.665V9.19c0-0.222-0.183-0.405-0.405-0.405H9.191c-0.223,0-0.405,0.183-0.405,0.405v1.215H3.122V6.358h13.757V10.405z"></path>
				</svg>
				<span class="">News</span>
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
