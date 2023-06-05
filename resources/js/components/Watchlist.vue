<template>
    <!-- give the sidebar z-50 class so its higher than the navbar if you want to see the logo -->
    <!-- you will need to add a little "X" button next to the logo in order to close it though -->
    <div class="flex p-2 bg-block-black-accent" id="main-watchlist">
        <div class="mb-4 px-1">
            <p class="text-center text-block-gray mt-10 mb-20">
                Watchlist
            </p>

            <div v-for="(row, index) in watchlist" :key="row.id" class="py-2 border-gray-900 border-b-2 w-full items-center text-white-accent mb-2 bg-block-black-accent hover:bg-block-black cursor-pointer px-2">
                <div class="w-full flex">
                    <img class="h-6 w-6 mr-2" :src="row.icon" />
                    <span class="text-block-white">{{ row.ticker }}</span>

                </div>
                <div class="w-full flex text-sm mt-1">
                    <span class="w-2/3 flex text-block-gray">${{ row.price }}</span>
                    <span class="w-1/3 flex text-green-500">{{ row.change }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import router from "resources/js/Routes";
import { serverBus } from "resources/js/main";

export default {
    name: "Sidebar",
    data() {
        return {
            watchlist: [
                {
                    ticker: "BTC",
                    icon: "https://img.icons8.com/color/344/bitcoin--v1.png",
                    change: "2.01%",
                    price: "30,000"
                },
                {
                    ticker: "ETH",
                    icon: "https://img.icons8.com/color/344/bitcoin--v1.png",
                    change: "2.01%",
                    price: "30,000"
                },
                {
                    ticker: "LTC",
                    icon: "https://img.icons8.com/color/344/bitcoin--v1.png",
                    change: "2.01%",
                    price: "30,000"
                }
            ]
        };
    },
    methods: {},
    computed: {},
    beforeDestroy() {},
    created() {
        //
    },
    mounted() {}
};
</script>
