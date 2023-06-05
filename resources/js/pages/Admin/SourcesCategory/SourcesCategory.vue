<template>
	<div class="dashboard-page">
	<nav class="text-sm font-semibold mb-6" aria-label="Breadcrumb">
              <ol class="list-none p-0 inline-flex">
                <li class="flex items-center text-blue-500">
                  <a href="#" class="text-gray-700">Home</a>
                  <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li class="flex items-center">
                  <a href="#" class="text-gray-600">Sources</a>
                </li>
              </ol>
            </nav>
<div class="flex flex-col">
<h2 class="font-medium text-gray-900 truncate mb-5">
    <a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">Sources</a>
</h2>
  <div class="">
    <div class="w-full align-middle inline-block">
      <div class="w-full shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <!-- This example requires Tailwind CSS v2.0+ -->
<!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
  <div class="">
    <div class="w-full py-2 align-middle inline-block ">
      <div class="shadow  border-b border-gray-200 sm:rounded-lg ">
        <b-input-group class="mt float-right search_holder">
             <input type="text" name="-name" autocomplete="given-name" class="bg-gray-500 text-white w-full p-1 text-left block shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-2 border-gray-300 rounded-md" v-model="query"></input>
        </b-input-group>
        <table class="w-full divide-y divide-gray-200">
          <thead class="">
            <tr>
              <th scope="col" class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Category
              </th>
              <th scope="col" class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>

              <th scope="col" class="relative px-1 py-1">
                <span class="sr-only">Remove</span>
              </th>
            </tr>
          </thead>
          <tbody class="text-white divide-y divide-gray-800">
            <tr v-for="(row,index) in filteredList" :key="row.id" :class="(index % 2 == 0) ? '' : 'bg-gray-900'">
             <td class="px-1py-4 whitespace-nowrap">
                {{ row.service }}
              </td>
             <td class="px-1py-4 whitespace-nowrap">
                  <a :href="display_url(row.url)" target="_blank">{{ display_url(row.name) }}</a>
              </td>
              <td class="px-1py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="#" class="text-indigo-600 hover:text-indigo-900">Remove</a>
              </td>
            </tr>
            <!-- More people... -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


      </div>
    </div>
  </div>
</div>


	</div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import { serverBus } from "resources/js/main";
import { globalStore } from "resources/js/main.js";

export default {
	name: "Sources",
	components: {

	},
	data() {
		return {
            selected_news:[],
            show_loading_news:true,
            query:""
		};
	},
	methods: {
    ...mapActions("sources", ["get_source_category_list"]),

    display_url:function(url){
        if(url){
            var link = url;
            if(url.includes("https//")){
                link = url.replace("https//","");
            } else if(url.includes("http//")){
                link = url.replace("http//","http://");
            } else if(!(url.includes("http://") || url.includes("https://"))){
                link = "https://"+url;
            }
            return link;
        }
        return "";

    },
	},

	computed: {
        ...mapState(["sources"]),
        filteredList() {
          if(this.query==""){
            return this.sources.sources_category;
          } else {
            return this.sources.sources_category.filter(article => {

                if(article.service.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }

                if(article.name.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }

                if(article.rss.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }

          });
      }

        },

	},
	mounted() {

     this.get_source_category_list({letter:this.$route.params.letter}).then( ({data}) => {
            this.show_loading_news = false;
        }).catch(error => {
            this.$auth_check(error);
        this.$error_notification(error);

		})


	},
};
</script>
<style>
.cursor {
	cursor: pointer;
}
</style>
<style src="./SourcesCategory.scss" lang="scss" />


