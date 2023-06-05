<template>
	<div class="dashboard-page">
		<nav class="text-sm font-semibold mb-6" aria-label="Breadcrumb">
			<ol class="list-none p-0 inline-flex">
				<li class="flex items-center text-blue-500">
					<a href="#" class="text-gray-700">Home</a>
					<svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" /></svg>
				</li>
				<li class="flex items-center">
					<a href="#" class="text-gray-600">News</a>
					<svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" /></svg>
				</li>
				<li class="flex items-center">
					<a href="#" class="text-gray-600">{{ ticker }}</a>
				</li>
			</ol>
		</nav>
		<div class="relative pb-5 border-b border-gray-200 sm:pb-0">
			<div class="md:flex md:items-center md:justify-between">
				<h3 class="text-lg leading-6 font-medium text-gray-900">Filters</h3>
			</div>
			<div class="mt-4">
                <div class="mt-4 flex justify-start item-center">
                    <h3 class=" leading-6 font-medium text-gray-600">AI Filtering: </h3>
                  <div class="flex items-center ml-4">
                    <input @click="_update_filter(0)" value="0" v-model="local_list.filter" id="filter-0" name="filter" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                    <label for="filter-0" class="hidden md:block ml-1 text:xs  md:text-sm font-medium text-gray-700">
                      No filerting <span class="text-gray-500">( {{ filter.no }} News )</span>
                    </label>
                    <label for="filter-0" class="ml-1 block md:hidden text:xs  md:text-sm font-medium text-gray-700">
                      All ({{ filter.no }})
                    </label>
                  </div>
                  <div class="flex items-center ml-4">
                    <input @click="_update_filter(50)" value="50" v-model="local_list.filter" id="filter-50" name="filter" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                    <label for="filter-50" class="hidden md:block  ml-1  text:xs  md:text-sm font-medium text-gray-700">
                      Some filerting <span class="text-gray-500">({{filter.some}} News)</span>
                    </label>
                    <label for="filter-50" class="ml-1 block md:hidden text:xs  md:text-sm font-medium text-gray-700">
                      Reg: ({{filter.some}})
                    </label>
                  </div>
                  <div class="flex items-center ml-4">
                    <input @click="_update_filter(75)" value="75" v-model="local_list.filter" id="filter-75" name="filter" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                   <label for="filter-50" class="hidden md:block ml-1 text:xs  md:text-sm font-medium text-gray-700">
                      AI + NLP:  <span class="text-gray-500">({{filter.some}} News)</span>
                    </label>
                    <label for="filter-50" class="ml-1 block md:hidden text:xs  md:text-sm font-medium text-gray-700">
                      AI+NLP: ({{filter.some}})
                    </label>
                  </div>
                </div>
                <p class="text-gray-500 text-xs">This filtering setting will be saved per stock. Will affect the number of unread news on the sidebar</p>
            </div>
			<div class="mt-4">
				<!-- Dropdown menu on small screens -->
				<div class="sm:hidden">
					<label for="current-tab" class="sr-only">Select a tab</label>
					<select id="current-tab" name="current-tab" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 border focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" @change="switch_tab($event.target.value)">
						<option :selected="latest" value="latest">Latest News</option>

						<option :selected="club" value="club">Read By my reading club</option>

						<!-- <option :selected="recommended" value="recommended">Recommended News</option> -->

					</select>
				</div>
				<!-- Tabs at small breakpoint and up -->
				<div class="hidden sm:block">
					<nav class="-mb-px flex space-x-8">
						<!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
						<a href="#" @click="switch_tab('latest')" :class="latest ? 'border-indigo-500 text-indigo-600': 'border-transparent '"  aria-current="page" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"> Latest News </a>

						<a href="#" @click="switch_tab('club')" :class="club ? 'border-indigo-500 text-indigo-600': 'border-transparent '" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"> Read By my reading club</a>

						<!-- <a href="#" @click="switch_tab('recommended')" :class="recommended ? 'border-indigo-500 text-indigo-600': 'border-transparent '" class="text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"> Recommended News </a> -->

					</nav>
				</div>
			</div>
		</div>
		<div class="flex flex-col mt-5" v-if="latest">
			<h2 class="font-medium text-gray-900 truncate mb-5">
				<a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">Latest News for {{ ticker }}</a>
			</h2>
			<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
					<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
						<ul class="space-y-1">
							<ul class="space-y-1">
								<li v-for="(row, index) in filteredList" :key="row.id" :class="[index % 2 == 0 ? 'bg-white shadow' : 'bg-gray-200', row.read == 1 ? 'bg-blue-200 opacity-70' : '']" class="p-2">
									<div class="flex space-x-3 relative">
										<span style="font-size:10px" v-if="row.read == 1" class="absolute right-0 bottom-0 inline-flex items-center px-3 py-0.5 rounded-lg text-xs bg-blue-600 text-white p-1"> READ </span>
										<div class="flex-shrink-0 justify-center px-2 text-center hidden md:block" style="width: 100px">
											<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
											<img class="object-cover w-30 rounded-full mx-auto mt-3" :src="row.logo" alt="" v-if="row.logo" />
											<div class="rounded-full border-2 flex p-3 relative h-10 w-10 mx-auto" v-if="!row.logo">
												<div class="absolute top-5 left-10 text-lg text-gray-500 font-bold">
													{{ row.author[0] }}
												</div>
											</div>
                                            <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
										</div>
										<div class="flex-grow">
											<div class="flex items-center justify-between text-sm md:hidden">
												<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
                                                <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
                                            </div>
											<div v-if="row.image_path != ''" class="w-full rounded-md md:hidden">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
                                                    <img :src="row.image_path" class="object-cover w-full rounded-md mx-auto object-top h-56" />
                                                </a>
											</div>
											<div class="text-sm mt-2 md:mt-0">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="text-lg font-medium text-gray-600"
													><img class="object-cover w-5 h-5 rounded-full mx-auto md:hidden inline" :src="row.logo" alt="" v-if="row.logo" /> <span class="text-gray-800 text-lg cursor-pointer">{{ row.text | capitalize }}</span> | {{ row.author | uppercase }} </a
												>
											</div>
											<!-- <div class="mt-1 text-sm text-gray-700 md:hidden">
												<p>{{ row.content | capitalize | truncate(300) }}</p>
											</div> -->
											<div class="mt-1 text-sm text-gray-700 hidden md:block">
												<p>{{ row.content | capitalize | truncate(600) }}</p>
											</div>
											<div class="mt-2 text-sm space-x-2 flex">
												<span class="text-blue-500 font-medium">{{ row.subtopic | capitalize | tickers }}</span>
												<span class="text-gray-500 font-medium hidden md:block ">&middot;</span>
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="hidden md:block text-blue-700 font-medium cursor-pointer">Read the Full Story</a>
												<span class="text-gray-500 font-medium">&middot;</span>
												<button @click="show_modal_share(row)"  type="button" class="text-blue-700 font-medium">Share</button>
											</div>
										</div>
										<div v-if="row.image_path != '' && row.image_path != null" class="flex-shrink-0 w-1/4 rounded-md pl-10 hidden md:block">
											<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
													<img :src="row.image_path" class="object-cover w-full rounded-md mx-auto" />
                                            </a>
										</div>
									</div>
								</li>
							</ul>
							<li v-if="displayed_break == true">
								<div class="bg-blue-100 flex justify-center pd-3 text-gray-600">
									Unread News <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M11 2.206l-6.235 7.528-.765-.645 7.521-9 7.479 9-.764.646-6.236-7.53v21.884h-1v-21.883z" /></svg>
								</div>
							</li>
							<ul class="space-y-1">
								<li v-for="(row, index) in filteredList2" :key="row.id" :class="[index % 2 == 0 ? 'bg-white shadow' : 'bg-gray-200', row.read == 1 ? 'bg-blue-200 opacity-70' : '']" class="p-2">
									<div class="flex space-x-3 relative">
										<span style="font-size:10px" v-if="row.read == 1" class="absolute right-0 bottom-0 inline-flex items-center px-3 py-0.5 rounded-lg text-xs bg-blue-600 text-white p-1"> READ </span>
										<div class="flex-shrink-0 justify-center px-2 text-center hidden md:block" style="width: 100px">
											<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
											<img class="object-cover w-30 rounded-full mx-auto mt-3" :src="row.logo" alt="" v-if="row.logo" />
											<div class="rounded-full border-2 flex p-3 relative h-10 w-10 mx-auto" v-if="!row.logo">
												<div class="absolute top-5 left-10 text-lg text-gray-500 font-bold">
													{{ row.author[0] }}
												</div>
											</div>
                                            <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
										</div>
										<div class="flex-grow">
											<div class="flex items-center justify-between text-sm md:hidden">
												<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
                                                <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
                                            </div>
											<div v-if="row.image_path != ''" class="w-full rounded-md md:hidden">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
                                                    <img :src="row.image_path" class="object-cover w-full rounded-md mx-auto object-top h-56" />
                                                </a>
											</div>
											<div class="text-sm mt-2 md:mt-0">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="text-lg font-medium text-gray-600"
													><img class="object-cover w-5 h-5 rounded-full mx-auto md:hidden inline" :src="row.logo" alt="" v-if="row.logo" /> <span class="text-gray-800 text-lg cursor-pointer">{{ row.text | capitalize }}</span> | {{ row.author | uppercase }} </a
												>
											</div>
											<!-- <div class="mt-1 text-sm text-gray-700 md:hidden">
												<p>{{ row.content | capitalize | truncate(300) }}</p>
											</div> -->
											<div class="mt-1 text-sm text-gray-700 hidden md:block">
												<p>{{ row.content | capitalize | truncate(600) }}</p>
											</div>
											<div class="mt-2 text-sm space-x-2 flex">
												<span class="text-blue-500 font-medium">{{ row.subtopic | capitalize | tickers }}</span>
												<span class="text-gray-500 font-medium hidden md:block ">&middot;</span>
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="hidden md:block text-blue-700 font-medium cursor-pointer">Read the Full Story</a>
												<span class="text-gray-500 font-medium">&middot;</span>
												<button @click="show_modal_share(row)"  type="button" class="text-blue-700 font-medium">Share</button>
											</div>
										</div>
										<div v-if="row.image_path != '' && row.image_path != null" class="flex-shrink-0 w-1/4 rounded-md pl-10 hidden md:block">
											<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
													<img :src="row.image_path" class="object-cover w-full rounded-md mx-auto" />
                                            </a>
										</div>
									</div>
								</li>
							</ul>
						</ul>
					</div>
				</div>
			</div>
		</div>
        <div class="flex flex-col mt-5" v-if="club">
			<h2 class="font-medium text-gray-900 truncate mb-5">
				<a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">News read by your reading club about {{ ticker }}</a>
			</h2>
			<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
					<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <p class="text-gray-600 text-center mt-10 mb-10" v-if="ticker_news_club.length == 0"> Nobody in your reading club has read any news about {{ ticker }} </p>
                        <ul class="space-y-1" v-if="ticker_news_club.length != 0">
								<li v-for="(row, index) in ticker_news_club" :key="row.id" :class="[index % 2 == 0 ? 'bg-white shadow' : 'bg-gray-200', row.read == 1 ? 'bg-blue-200 opacity-70' : '']" class="p-2">
									<div class="flex space-x-3 relative">
										<span style="font-size:10px" v-if="row.read == 1" class="absolute right-0 bottom-0 inline-flex items-center px-3 py-0.5 rounded-lg text-xs bg-blue-600 text-white p-1"> READ </span>
										<div class="flex-shrink-0 justify-center px-2 text-center hidden md:block" style="width: 100px">
											<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
											<img class="object-cover w-30 rounded-full mx-auto mt-3" :src="row.logo" alt="" v-if="row.logo" />
											<div class="rounded-full border-2 flex p-3 relative h-10 w-10 mx-auto" v-if="!row.logo">
												<div class="absolute top-5 left-10 text-lg text-gray-500 font-bold">
													{{ row.author[0] }}
												</div>
											</div>
                                            <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
										</div>
										<div class="flex-grow">
											<div class="flex items-center justify-between text-sm md:hidden">
												<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
                                                <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
                                            </div>
											<div v-if="row.image_path != ''" class="w-full rounded-md md:hidden">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
                                                    <img :src="row.image_path" class="object-cover w-full rounded-md mx-auto object-top h-56" />
                                                </a>
											</div>
											<div class="text-sm mt-2 md:mt-0">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="text-lg font-medium text-gray-600"
													><img class="object-cover w-5 h-5 rounded-full mx-auto md:hidden inline" :src="row.logo" alt="" v-if="row.logo" /> <span class="text-gray-800 text-lg cursor-pointer">{{ row.text | capitalize }}</span> | {{ row.author | uppercase }} </a
												>
											</div>
											<!-- <div class="mt-1 text-sm text-gray-700 md:hidden">
												<p>{{ row.content | capitalize | truncate(300) }}</p>
											</div> -->
											<div class="mt-1 text-sm text-gray-700 hidden md:block">
												<p>{{ row.content | capitalize | truncate(600) }}</p>
											</div>
											<div class="mt-2 text-sm space-x-2 flex">
												<span class="text-blue-500 font-medium">{{ displayTickers(row.subtopic | capitalize | tickers) }}</span>
												<span class="text-gray-500 font-medium hidden md:block ">&middot;</span>
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="hidden md:block text-blue-700 font-medium cursor-pointer">Read the Full Story</a>
												<span class="text-gray-500 font-medium">&middot;</span>
												<button @click="show_modal_share(row)"  type="button" class="text-blue-700 font-medium">Share</button>
											</div>
										</div>
										<div v-if="row.image_path != '' && row.image_path != null" class="flex-shrink-0 w-1/4 rounded-md pl-10 hidden md:block">
											<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
													<img :src="row.image_path" class="object-cover w-full rounded-md mx-auto" />
                                            </a>
										</div>
									</div>
								</li>
							</ul>
					</div>
				</div>
			</div>
		</div>
        <div class="flex flex-col mt-5" v-if="recommended">
			<h2 class="font-medium text-gray-900 truncate mb-5">
				<a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">Recommended News for {{ ticker }}</a>
			</h2>
			<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
					<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
							<p class="text-gray-600 text-center mt-10 mb-10" v-if="ticker_news_recommended.length == 0"> Nobody has recommendations about this {{ ticker }} </p>
                            <ul class="space-y-1" v-if="ticker_news_recommended.length != 0">
								<li v-for="(row, index) in ticker_news_recommended" :key="row.id" :class="[index % 2 == 0 ? 'bg-white shadow' : 'bg-gray-200', row.read == 1 ? 'bg-blue-200 opacity-70' : '']" class="p-2">
									<div class="flex space-x-3 relative">
										<span style="font-size:10px" v-if="row.read == 1" class="absolute right-0 bottom-0 inline-flex items-center px-3 py-0.5 rounded-lg text-xs bg-blue-600 text-white p-1"> READ </span>
										<div class="flex-shrink-0 justify-center px-2 text-center hidden md:block" style="width: 100px">
											<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
											<img class="object-cover w-30 rounded-full mx-auto mt-3" :src="row.logo" alt="" v-if="row.logo" />
											<div class="rounded-full border-2 flex p-3 relative h-10 w-10 mx-auto" v-if="!row.logo">
												<div class="absolute top-5 left-10 text-lg text-gray-500 font-bold">
													{{ row.author[0] }}
												</div>
											</div>
                                            <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
										</div>
										<div class="flex-grow">
											<div class="flex items-center justify-between text-sm md:hidden">
												<span class="text-gray-500 font-medium text-sm">{{ row.created_at | moment("timezone", date_timezone, datetime_format) | ago }}</span>
                                                <p class="space-x-1 inline-flex items-center text-sm text-gray-400 mt-2">
                                                    <svg class="fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.25 6c.414 0 .75.336.75.75v9.5c0 .414-.336.75-.75.75h-9.5c-.414 0-.75-.336-.75-.75v-9.5c0-.414.336-.75.75-.75h9.5zm2.75 0c0-1.104-.896-2-2-2h-11c-1.104 0-2 .896-2 2v11c0 1.104.896 2 2 2h11c1.104 0 2-.896 2-2v-11zm-11 14v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm-8-20v3h-1v-3h1zm4 0v3h-1v-3h1zm2 0v3h-1v-3h1zm-4 0v3h-1v-3h1zm6 0v3h-1v-3h1zm4 15h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1zm-20 8h3v1h-3v-1zm0-4h3v1h-3v-1zm0-2h3v1h-3v-1zm0 4h3v1h-3v-1zm0-6h3v1h-3v-1z"/></svg>
                                                    <span class=""> AI: {{row.relevance }}</span>
                                                </p>
                                            </div>
											<div v-if="row.image_path != ''" class="w-full rounded-md md:hidden">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
                                                    <img :src="row.image_path" class="object-cover w-full rounded-md mx-auto object-top h-56" />
                                                </a>
											</div>
											<div class="text-sm mt-2 md:mt-0">
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="text-lg font-medium text-gray-600"
													><img class="object-cover w-5 h-5 rounded-full mx-auto md:hidden inline" :src="row.logo" alt="" v-if="row.logo" /> <span class="text-gray-800 text-lg cursor-pointer">{{ row.text | capitalize }}</span> | {{ row.author | uppercase }} </a
												>
											</div>
											<!-- <div class="mt-1 text-sm text-gray-700 md:hidden">
												<p>{{ row.content | capitalize | truncate(300) }}</p>
											</div> -->
											<div class="mt-1 text-sm text-gray-700 hidden md:block">
												<p>{{ row.content | capitalize | truncate(600) }}</p>
											</div>
											<div class="mt-2 text-sm space-x-2 flex">
												<span class="text-blue-500 font-medium">{{ row.subtopic | capitalize | tickers }}</span>
												<span class="text-gray-500 font-medium hidden md:block ">&middot;</span>
												<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="hidden md:block text-blue-700 font-medium cursor-pointer">Read the Full Story</a>
												<span class="text-gray-500 font-medium">&middot;</span>
												<button @click="show_modal_share(row)"  type="button" class="text-blue-700 font-medium">Share</button>
											</div>
										</div>
										<div v-if="row.image_path != '' && row.image_path != null" class="flex-shrink-0 w-1/4 rounded-md pl-10 hidden md:block">
											<a target="_blank" @click="_record_read(row.id, row)" :href="row.link" class="">
													<img :src="row.image_path" class="object-cover w-full rounded-md mx-auto" />
                                            </a>
										</div>
									</div>
								</li>
							</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="flex flex-col mt-20 hidden">
			<h2 class="font-medium text-gray-900 truncate mb-5">
				<a href="#component-3de290cc969415f170748791a9d263a6" class="mr-1">Social</a>
			</h2>
			<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
					<div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticker</th>
									<th scope="col" class="relative px-6 py-3">
										<span class="sr-only">Read</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<!-- Odd row -->
								<tr v-if="ticker_news.length == 0">
									<td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">No results found</td>
								</tr>
								<tr class="bg-white">
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Jane Cooper</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Regional Paradigm Technician</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">jane.cooper@example.com</td>
									<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Admin</td>
									<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
										<a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
        <ModalShareNews :callback="callBackShare" :modal_enabled="modal_enabled"  :news="share_news" v-on:close_modal="close_modal"></ModalShareNews>

	</div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import config from "resources/js/config";
import { serverBus } from "resources/js/main";
import { globalStore } from "resources/js/main.js";
import ModalShareNews from "@/components/ModalShareNews/ModalShareNews";



export default {
	name: "News",
	components: {
        ModalShareNews
    },
	data() {
		return {
			ticker: "",
			displayed_break: false,
			filter: {
                no:0,
                some:0,
                ai:0,
            },
            modal_enabled:false,
			local_list: { filter:0 },
			ticker_news: [],
			ticker_news_club: [],
			ticker_news_recommended: [],
			show_loading_news: true,
            latest_tab_clicked:"latest",
			query: "",
            share_news:{},
            latest:true,
            recommended:false,
            club:false,
            tabs:["latest","recommended","club"]
		};
	},
	methods: {
		...mapActions("news", ["get_news_ticker", "record_read"]),
		...mapActions("watchlist", ["mark_read", "my_watchlist_unread", "my_watchlist_unread_single", "my_watchlist","update_filter","get_filters_count"]),
		reset_view: function () {
			this.ticker_news = [];
            this.filter = {
                no:0,
                some:0,
                ai:0,
            };
            this.latest_tab_clicked = "latest";
            this.latest = true;
            this.local_list = { filter:0 };
            this.club = false;
            this.recommended = false;
			this.displayed_break = false;
			// console.log(this.displayed_break);
		},
         callBackShare:function(){

    },
    close_modal: function(payload){
        this.modal_enabled = false;
    },
    show_modal_share: function(news){
        this.share_news = news
        this.modal_enabled = true
    },
		_record_read: function (id,row) {
            row.read = 1;
			this.record_read({ id: id })
				.then(({ data }) => {})
				.catch((error) => {})
				.finally(() => {});
		},
        _get_filters_count: function (id) {
			this.get_filters_count({ ticker: this.ticker, })
				.then(({ data }) => {
                    this.filter = data.response;
                })
				.catch((error) => {})
				.finally(() => {});
		},
        _update_filter:function(level){
            serverBus.$emit("show_loader", "load");
            this.show_loading_news = true;
            this.update_filter({ ticker: this.ticker, filter:level })
                .then(({ data }) => {
                    console.log("here");
                    this.switch_tab("latest");
                    this.my_watchlist_unread_single({ ticker: ticker });

                    serverBus.$emit("show_loader", "hide");
                })
                .catch((error) => {
                    this.$auth_check(error);
                    this.$error_notification(error);
                })
                .finally(() => {
                    serverBus.$emit("hide_loader", "load");
                });
        },
        switch_tab:function(tab){
            console.log("Entering Tab");
            this.latest = false;
            this.club = false;
            this.recommended = false;
            if(tab == "latest"){
                this.latest_tab_clicked = "latest";
                this.latest = true;
                 console.log("Getting news Tab");
                this.get_news_ticker({ instrument: this.ticker, type:tab })
                    .then(({ data }) => {

                        this.ticker_news = data.response;
                        this.show_loading_news = false;

                        if (this.ticker_news.length > 0) {
                            this.mark_read_ticker(this.ticker, this.ticker_news[0].id);
                        }
                    })
                    .catch((error) => {
                        this.$auth_check(error);
                        this.$error_notification(error);
                    })
                    .finally(() => {
                        serverBus.$emit("hide_loader", "load");
                    });
            }
            if(tab == "club"){
                this.latest_tab_clicked = "club";
                this.club = true;
                this.get_news_ticker({ instrument: this.ticker, type:tab })
                    .then(({ data }) => {
                        this.ticker_news_club = data.response;
                        this.show_loading_news = false;

                    })
                    .catch((error) => {
                        this.$auth_check(error);
                        this.$error_notification(error);
                    })
                    .finally(() => {
                        serverBus.$emit("hide_loader", "load");
                    });
            }
            if(tab == "recommended"){
                this.recommended = true;
                this.latest_tab_clicked = "recommended";
                this.get_news_ticker({ instrument: this.ticker, type:tab })
                    .then(({ data }) => {
                        this.ticker_news_recommended = data.response;
                        this.show_loading_news = false;
                    })
                    .catch((error) => {
                        this.$auth_check(error);
                        this.$error_notification(error);
                    })
                    .finally(() => {
                        serverBus.$emit("hide_loader", "load");
                    });
            }
        },
		mark_read_ticker: function (ticker, id) {
			this.mark_read({ ticker: ticker, last_id_read: id })
				.then(({ data }) => {
					this.my_watchlist_unread_single({ ticker: ticker });
				})
				.catch((error) => {
					this.$auth_check(error);
					this.$error_notification(error);
				})
				.finally(() => {
					serverBus.$emit("hide_loader", "load");
				});
		},
	},

	computed: {
		...mapState(["watchlist"]),
		filteredList() {

            if(this.watchlist.watchlist != undefined){

            }

			return this.ticker_news.filter((article) => {
				if (this.local_list.last_id_read < article.id || this.local_list.last_id_read == null) {
					this.displayed_break = true;
					// console.log(local_list)
					// console.log(local_list.last_id_read+" "+article.id)

					if (this.query != "") {
						if (article.text.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}

						if (article.author.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}

						if (article.content.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}
					} else {
						// console.log(article);
						return article;
					}
				}
			});
		},



		filteredList2() {

			return this.ticker_news.filter((article) => {
				if (this.local_list.last_id_read >= article.id && this.local_list.last_id_read != null) {
					if (this.query != "") {
						if (article.text.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}

						if (article.author.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}

						if (article.content.toLowerCase().includes(this.query.toLowerCase())) {
							return article;
						}
					} else {
						return article;
					}
				}
			});
		},
	},
	watch: {
		$route(to, from) {
			serverBus.$emit("show_loader", "load");
			this.ticker = this.$route.params.ticker;
			this.reset_view();
			this.get_news_ticker({ instrument: this.ticker })
				.then(({ data }) => {
					this.ticker_news = data.response;
					this.show_loading_news = false;
                    this._get_filters_count();
					if (this.ticker_news.length > 0) {
						this.mark_read_ticker(this.ticker, this.ticker_news[0].id);
						this.my_watchlist()
							.then(({ data }) => {})
							.catch((error) => {});
					}
                    this.local_list = this.watchlist.watchlist.filter((item) => {
                    if (item.ticker == this.ticker) return item;
                    })[0];
                    this._get_filters_count();
				})
				.catch((error) => {
					this.$auth_check(error);
					this.$error_notification(error);
				})
				.finally(() => {
					serverBus.$emit("hide_loader", "load");
				});
		},
	},
	mounted() {
		this.ticker = this.$route.params.ticker;

		this.get_news_ticker({ instrument: this.ticker, type:"latest" })
			.then(({ data }) => {
				this.ticker_news = data.response;
				this.show_loading_news = false;

				if (this.ticker_news.length > 0) {
					this.mark_read_ticker(this.ticker, this.ticker_news[0].id);
				}
                this.local_list = this.watchlist.watchlist.filter((item) => {
				if (item.ticker == this.ticker) return item;
                })[0];
                this._get_filters_count();
			})
			.catch((error) => {
				this.$auth_check(error);
				this.$error_notification(error);
			})
			.finally(() => {
				serverBus.$emit("hide_loader", "load");
			});
		this.$nextTick(function () {
			serverBus.$emit("show_loader", "load");
		});


	},
};
</script>
<style>
.cursor {
	cursor: pointer;
}
</style>
<style src="./News.scss" lang="scss" />


