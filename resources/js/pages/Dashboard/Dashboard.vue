<template>
    <div class="dashboard-page max-w-screen-lg">
        <div class="w-full grid grid-cols-2 gap-4 py-10">
            <div>
                <h1>Portfolio</h1>
                <div class="bg-block-black-accent rounded-lg h-64 mt-2"></div>
            </div>
            <div>
                <h1>Chart</h1>
                <div class="bg-block-black-accent rounded-lg h-64 mt-2"></div>
            </div>
        </div>
        <h2 class="mt-10 mb-2 text-2xl">Trending News</h2>
        <News service="Trending News" layout="2"/>
        <h2 class="mt-10 mb-2 text-2xl">Around the Markets</h2>
        <News service="Around the Markets" layout="4"/>
        <h2 class="mt-10 mb-2 text-2xl">Top News</h2>
        <News service="Top News" layout="1"/>
        <h2 class="mt-10 mb-2 text-2xl">NFTs</h2>
        <News service="NFTs" layout="11"/>
        <h2 class="mt-10 mb-2 text-2xl">DeFi</h2>
        <News service="DeFi" layout="5"/>
        <h2 class="mt-10 text-xl">Bitcoin</h2>
        <News service="Bitcoin" layout="4"/>
        <h2 class="mt-10 text-xl">Bitcoin Price News</h2>
        <News service="Bitcoin Price News" layout="12"/>
        <h2 class="mt-10 text-xl">Ethereum</h2>
        <News service="Ethereum" layout="6"/>
        <h2 class="mt-10 text-xl">Ethereum Price News</h2>
        <News service="Ethereum Price News" layout="12"/>
        <h2 class="mt-10 text-xl">Altcoin</h2>
        <News service="Altcoin" layout="15"/>
        <h2 class="mt-10 text-xl">Trending NFTs</h2>
        <h2 class="mt-10 text-xl">Press Release</h2>
        <News service="Press Releases" layout="3"/>
        <h2 class="mt-10 text-xl">Learn</h2>
        <h2 class="mt-10 text-xl">Latest News</h2>
        <News service="Latest News" layout="6"/>
        <h2 class="mt-10 text-xl">Projects to Follow (NFT)</h2>
        <h2 class="mt-10 text-xl">Projects to Follow (Crypto)</h2>
        <h2 class="mt-10 text-xl">Popular on TikTok</h2>
        <h2 class="mt-10 text-xl">TikTok Crypto</h2>
        <h2 class="mt-10 text-xl">Trending on TikTok</h2>
        <h2 class="mt-10 text-xl">NFT TikTok</h2>
        <h2 class="mt-10 text-xl">LOL TikTok</h2>
        <!-- <h2 class="mt-10 text-xl">Lean</h2>
        <News service="Learn" layout="8"/>
        <h2 class="mt-10 text-xl">Layout 3</h2>
        <News :news="extract_news(10,0)" layout="3"/>
        <h2 class="mt-10 text-xl">Layout 4</h2>
        <News :news="extract_news(10,0)" layout="4"/>
        <h2 class="mt-10 text-xl">Layout 5</h2>
        <News :news="extract_news(10,0)" layout="5"/>
        <h2 class="mt-10 text-xl">Layout 6</h2>
        <News :news="extract_news(10,0)" layout="6"/>
        <h2 class="mt-10 text-xl">Layout 7</h2>
        <News :news="extract_news(10,0)" layout="7"/>
        <h2 class="mt-10 text-xl">Layout 8</h2>
        <p>
            8 and 5 are same
        </p>
        <h2 class="mt-10 text-xl">Layout 9</h2>
        <News :news="extract_news(10,0)" layout="9"/>
        <h2 class="mt-10 text-xl">Layout 10</h2>
        This i need to find a good components on the internet.
        <h2 class="mt-10 text-xl">Layout 11</h2>
        <News :news="extract_news(10,0)" layout="11"/>
        <h2 class="mt-10 text-xl">Layout 12</h2>
        <News :news="extract_news(10,0)" layout="12"/>
        <h2 class="mt-10 text-xl">Layout 13</h2>
        <News :news="extract_news(10,0)" layout="13"/>
        <h2 class="mt-10 text-xl">Layout 14</h2>
        <News :news="extract_news(10,0)" layout="14"/>
        <h2 class="mt-10 text-xl">Layout 15</h2>
        <News :news="extract_news(10,0)" layout="15"/>
        <h2 class="mt-10 text-xl">Layout 16</h2>
        <News :news="extract_news(10,0)" layout="16"/>
        <h2 class="mt-10 text-xl">Layout 17</h2>
        <News :news="extract_news(10,0)" layout="17"/>
        <h2 class="mt-10 text-xl">Layout 18</h2>
        Will do
        <h2 class="mt-10 text-xl">Layout 19</h2>
        Same as layout 6
        <h2 class="mt-10 text-xl">Layout 20</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 21</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 22</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 23</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 24</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 25</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 26</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 27</h2>
        Will Do
        <h2 class="mt-10 text-xl">Layout 28</h2>
        Will Do -->
    </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import { serverBus } from "resources/js/main";
import { globalStore } from "resources/js/main.js";
import News from "@/components/News/News";

export default {
    name: "Dashboard",
    components: {
        News
    },
    data() {
        return {
            selected_news: [],
            show_loading_news: true,
            latest_tab_clicked: "latest",
            query: "",
            latest: true,
            recommended: false,
            club: false,
            modal_enabled: false,
            share_news: {},
            tabs: ["latest", "recommended", "club"],
            filter: {
                no: 0,
                some: 0,
                ai: 0
            },
            local_list: { filter: 0 },
            ticker_news: [],
            ticker_news_club: [],
            ticker_news_recommended: []
        };
    },
    methods: {
        ...mapActions("news", ["get_news"]),
        callBackShare: function() {},

        extract_news: function(items,offset){
            var counter = 0
            var pre_selection = []
            for(counter = 0; counter<items; counter++){
                pre_selection.push(this.news.all_news[counter]);
            }
            return {grouped_news:[pre_selection]};
        },



    },

    computed: {
        ...mapState(["news"]),
        filteredList() {
            if (this.query == "") {
                return this.news.all_news;
            } else {
                return this.news.all_news.filter(article => {
                    if (article.text.toLowerCase().includes(this.query.toLowerCase())) {
                        return article;
                    }

                    if (article.author.toLowerCase().includes(this.query.toLowerCase())) {
                        return article;
                    }

                    if (article.content.toLowerCase().includes(this.query.toLowerCase())) {
                        return article;
                    }
                });
            }
        }
    },
    mounted() {

    }
};
</script>
<style>
.cursor {
    cursor: pointer;
}
</style>
<style src="./Dashboard.scss" lang="scss" />
