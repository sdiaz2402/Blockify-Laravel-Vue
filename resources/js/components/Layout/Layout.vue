<template>
    <div class="leading-normal tracking-normal bg-block-black" id="main-body">
        <div v-if="show_loader" class="loading bg-blue-200 opacity-50 text-blue-700">
            <div class="m-auto w-full">
                <svg width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#3182ce" class="animate-spin h-5 w-5 mr-3 inline">
                    <g fill="none" fill-rule="evenodd">
                        <g transform="translate(1 1)" stroke-width="2">
                            <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
                            <path d="M36 18c0-9.94-8.06-18-18-18"></path>
                        </g>
                    </g>
                </svg>
                Loading
                <button @click="hide_loader()">
                    hide
                </button>
            </div>
        </div>
        <div class="flex flex-wrap">
            <Sidebar />

            <div class="flex-1 w-full bg-block-black pl-0 lg:pl-64  main-content" :class="sideBarOpen ? 'overlay' : ''" id="main-content">
                <Navbar />

                 <div class="p-4">
                    <router-view />
                </div>
            </div>



        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";

import Sidebar from "@/components/Sidebar";
import Navbar from "@/components/Navbar";
import Watchlist from "@/components/Watchlist";
import { serverBus } from "@/main";

export default {
    name: "Layout",
    data() {
        return {
            show_loader: false,
            rerquest: 0
        };
    },
    methods: {
        handleLongProcesses() {
            // console.log("Loader Timer Reached");
            if (this.rerquest > 0) {
                this.rerquest = this.rerquest - 1;
            }
            // console.log("FORCED HIDE Loader "+this.rerquest);
            if (this.rerquest <= 0) {
                this.show_loader = false;
            }
        },
        hide_loader() {
            this.rerquest = 0;
            this.show_loader = false;
        }
    },
    mounted() {
        serverBus.$on("show_loader", source => {
            console.log("SHOW Loader " + this.rerquest + " from: " + source);
            this.show_loader = true;
            this.rerquest = this.rerquest + 1;
            const self = this;
            setTimeout(function() {
                self.handleLongProcesses();
            }, 45000);
        });

        serverBus.$on("hide_loader", source => {
            console.log("HIDE Loader " + this.rerquest + " from: " + source);
            this.rerquest = this.rerquest - 1;
            if (this.rerquest <= 0) {
                this.rerquest = 0;
                this.hide_loader();
            }
        });
    },
    computed: {
        ...mapState("layout", ["sideBarOpen"])
    },
    components: {
        Sidebar,
        Navbar,
        Watchlist
    }
};
</script>
<style src="./Layout.scss" lang="scss" />
