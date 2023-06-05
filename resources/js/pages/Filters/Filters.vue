<template>
	<div class="dashboard-page">
	<nav class="text-sm font-semibold mb-6" aria-label="Breadcrumb">
              <ol class="list-none p-0 inline-flex">
                <li class="flex items-center text-blue-500">
                  <a href="#" class="text-gray-700">Home</a>
                  <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li class="flex items-center">
                  <a href="#" class="text-gray-600">Filters</a>
                </li>
              </ol>
            </nav>
<div class="flex flex-col">
<h2 class="font-medium text-gray-900 truncate mb-5">
    <a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">Filters</a>
</h2>
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <!-- This example requires Tailwind CSS v2.0+ -->
<!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>

              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ticker
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Keywords
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(row,index) in filteredList" :key="row.id" :class="(index % 2 == 0) ? 'bg-white' : 'bg-gray-50'">

              <td class="px-6 py-4 whitespace-nowrap">
                  <a :href="'http://'+row.url" target="_blank">{{ row.context_name }}</a>
              </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a :href="'http://'+row.url" target="_blank">{{ row.name }}</a>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <input style="width:300px" type="text" name="-name" autocomplete="given-name" class="p-2 text-left block shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-2 border-gray-300 rounded-md" :value="row.keywords" @change="update_filter(row.id,$event.target.value)">
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
    ...mapActions("filters", ["get_filters_list"]),
    update_filter: function(id,keywords){
        serverBus.$emit("show_loader", "update_rank");
        this.$axios
            .post(config.baseURLApi + "/filters/update_filter", {
                id: id,
                keywords: keywords,
            },
            { headers: config.headers })
            .then(({ data }) => {
                // console.log(data);
                if (data.data.status == "success") {

                    this.$success_notification(data.data.message);
                } else {
                    this.$error_notification(data.data.message);
                }
            })
            .catch(error => {
                this.$error_notification(error);
                this.$auth_check(error);
            })
            .finally(() => {
                serverBus.$emit("hide_loader", "update_filter");
            });
    },




	},

	computed: {
        ...mapState(["filters"]),
        filteredList() {
          if(this.query==""){
            return this.filters.filters;
          } else {
            return this.filters.filters.filter(article => {

                if(article.name.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }

                if(article.ticker.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }

                if(article.keywords.toLowerCase().includes(this.query.toLowerCase())){
                     return article;
                }


          });
      }

        },

	},
	mounted() {

     this.get_filters_list().then( ({data}) => {
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
<style src="./Filters.scss" lang="scss" />


