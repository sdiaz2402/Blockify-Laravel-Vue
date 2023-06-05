<template>
  <div>
    <div v-if="layout == 1" class="flex flex-col">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full grid grid-cols-2 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[0].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[0].logo" />
                <span class="ml-2">
                  {{ row[0].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[0].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[0].topic | truncate(30) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[1].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[1].logo" />
                <span class="ml-2">
                  {{ row[1].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[1].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].topic | truncate(30) }}</p>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-4 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[2].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[2].logo" />
                <span class="ml-2">
                  {{ row[2].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[2].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[2].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[3].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[3].logo" />
                <span class="ml-2">
                  {{ row[3].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[3].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[3].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[4].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[4].logo" />
                <span class="ml-2">
                  {{ row[4].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[4].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[4].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none h-48">
              <img class="object-cover rounded-t-lg w-full" :src="row[5].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray flex items-center">
                <img class="h-6 rounded-t-lg" :src="row[5].logo" />
                <span class="ml-2">
                  {{ row[5].author }}
                </span>
              </p>
              <p class="text-lg">{{ row[5].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[5].topic | truncate(10) }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 2">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-2/3 flex flex-none">
              <img class="object-cover rounded-l-lg" :src="row[0].image_path" />
            </div>
            <div class="w-1/3 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray flex items-center">
                  <img class="h-6 rounded-t-lg" :src="row[0].logo" />
                  <span class="ml-2">
                    {{ row[0].author }}
                  </span>
                </p>
                <p class="text-lg">{{ row[0].text }}</p>
                <p class="text-sm text-gray-400">{{ row[0].content | truncate(100) }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-2 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray flex items-center">
                  <img class="h-6 rounded-t-lg" :src="row[1].logo" />
                  <span class="ml-2">
                    {{ row[1].author }}
                  </span>
                </p>
                <p class="text-lg">{{ row[1].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[1].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-l-lg" :src="row[1].image_path" />
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray flex items-center">
                  <img class="h-6 rounded-t-lg" :src="row[2].logo" />
                  <span class="ml-2">
                    {{ row[2].author }}
                  </span>
                </p>
                <p class="text-lg">{{ row[2].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[2].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[2].image_path" />
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-2 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray flex items-center">
                  <img class="h-6 rounded-t-lg" :src="row[3].logo" />
                  <span class="ml-2">
                    {{ row[3].author }}
                  </span>
                </p>
                <p class="text-lg">{{ row[3].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[3].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[3].image_path" />
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray flex items-center">
                  <img class="h-6 rounded-t-lg" :src="row[4].logo" />
                  <span class="ml-2">
                    {{ row[4].author }}
                  </span>
                </p>
                <p class="text-lg">{{ row[4].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[4].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[4].image_path" />
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 3">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="flex overflow-x-scroll pb-10 hide-scroll-bar">
          <div class="flex flex-nowrap">
            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[0].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[0].author }}</p>
                  <p class="text-lg">{{ row[0].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[0].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[1].author }}</p>
                  <p class="text-lg">{{ row[1].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[1].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[2].author }}</p>
                  <p class="text-lg">{{ row[2].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[2].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[3].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[3].author }}</p>
                  <p class="text-lg">{{ row[3].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[3].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[4].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[4].author }}</p>
                  <p class="text-lg">{{ row[4].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[4].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[5].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[5].author }}</p>
                  <p class="text-lg">{{ row[5].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[5].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[6].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[6].author }}</p>
                  <p class="text-lg">{{ row[6].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[6].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[6].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="inline-block px-1">
              <div class="bg-block-black-accent rounded-lg flex flex-col w-64">
                <div class="w-full flex flex-none">
                  <img class="h-48 object-cover rounded-t-lg w-full" :src="row[7].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[7].author }}</p>
                  <p class="text-lg">{{ row[7].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p>{{ row[7].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[7].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 4">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full p-2 flex space-x-3">
          <div class="flex w-2/3 flex-col space-y-3">
            <div class="bg-block-black-accent rounded-lg flex flex-1">
              <div class="w-1/2 flex flex-none">
                <img class="object-cover rounded-l-lg" :src="row[0].image_path" />
              </div>
              <div class="w-1/2 justify-end flex flex-col flex-none p-2">
                <div class="flex-col flex flex-1">
                  <p class="text-block-gray flex items-center">
                    <img class="h-6 rounded-t-lg" :src="row[0].logo" />
                    <span class="ml-2">
                      {{ row[0].author }}
                    </span>
                  </p>
                  <p class="text-lg">{{ row[0].text }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                  <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[0].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>
            <div class="flex space-x-3">
              <div class="flex w-1/2">
                <div class="bg-block-black-accent rounded-lg flex flex-col">
                  <div class="w-full flex flex-none">
                    <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
                  </div>
                  <div class="flex-col flex flex-1 p-2">
                    <p class="text-block-gray flex items-center">
                      <img class="h-6 rounded-t-lg" :src="row[1].logo" />
                      <span class="ml-2">
                        {{ row[1].author }}
                      </span>
                    </p>
                    <p class="text-lg">{{ row[1].text }}</p>
                  </div>
                  <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                    <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                    <p class="mx-1">&#8226;</p>
                    <p>{{ row[1].topic | truncate(10) }}</p>
                  </div>
                </div>
              </div>
              <div class="flex w-1/2">
                <div class="bg-block-black-accent rounded-lg flex flex-col">
                  <div class="w-full flex flex-none">
                    <img class="h-48 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
                  </div>
                  <div class="flex-col flex flex-1 p-2">
                    <p class="text-block-gray flex items-center">
                      <img class="h-6 rounded-t-lg" :src="row[2].logo" />
                      <span class="ml-2">
                        {{ row[2].author }}
                      </span>
                    </p>
                    <p class="text-lg">{{ row[2].text }}</p>
                  </div>
                  <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                    <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                    <p class="mx-1">&#8226;</p>
                    <p>{{ row[2].topic | truncate(10) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex w-1/3 flex-col space-y-3">
            <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
              <div class="w-full flex flex-col flex-none p-1 place-content-between">
                <div class="flex flex-1">
                  <div class="flex-col flex flex-1 w-2/3">
                    <p class="text-block-gray flex items-center">
                      <img class="h-6 rounded-t-lg" :src="row[3].logo" />
                      <span class="ml-2">
                        {{ row[3].author }}
                      </span>
                    </p>
                    <p class="text-base">{{ row[3].text }}</p>
                  </div>
                  <div class="w-1/3 flex flex-none">
                    <img class="object-cover rounded-lg" :src="row[3].image_path" />
                  </div>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                  <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[3].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
              <div class="w-full flex flex-col flex-none p-1 place-content-between">
                <div class="flex flex-1">
                  <div class="flex-col flex flex-1 w-2/3">
                    <p class="text-block-gray flex items-center">
                      <img class="h-6 rounded-t-lg" :src="row[4].logo" />
                      <span class="ml-2">
                        {{ row[4].author }}
                      </span>
                    </p>
                    <p class="text-base">{{ row[4].text }}</p>
                  </div>
                  <div class="w-1/3 flex flex-none">
                    <img class="object-cover rounded-lg" :src="row[4].image_path" />
                  </div>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                  <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[4].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>

            <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
              <div class="w-full flex flex-col flex-none p-1 place-content-between">
                <div class="flex flex-1">
                  <div class="flex-col flex flex-1 w-2/3">
                    <p class="text-block-gray flex items-center">
                      <img class="h-6 rounded-t-lg" :src="row[5].logo" />
                      <span class="ml-2">
                        {{ row[5].author }}
                      </span>
                    </p>
                    <p class="text-base">{{ row[5].text }}</p>
                  </div>
                  <div class="w-1/3 flex flex-none">
                    <img class="object-cover rounded-lg" :src="row[5].image_path" />
                  </div>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                  <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  <p class="mx-1">&#8226;</p>
                  <p>{{ row[5].topic | truncate(10) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 5">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-2/3 flex flex-none">
              <img class="object-cover rounded-l-lg" :src="row[0].image_path" />
            </div>
            <div class="w-1/3 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-lg">{{ row[0].text }}</p>
                <p class="text-sm text-gray-400">{{ row[0].content | truncate(100) }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[1].author }}</p>
                  <p class="text-base">{{ row[1].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[1].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[1].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[2].author }}</p>
                  <p class="text-base">{{ row[2].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[2].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[2].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[3].author }}</p>
                  <p class="text-base">{{ row[3].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[3].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[3].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 6">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full flex">
          <div class="flex w-2/3 flex-grow-1">
            <div class="bg-block-black-accent rounded-lg flex flex-col w-full">
              <div class="w-full flex flex-1">
                <img class="object-cover rounded-t-lg w-full" :src="row[0].image_path" />
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-xl">{{ row[0].text }}</p>
                <p class="text-sm text-gray-400">{{ row[0].content | truncate(100) }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="w-1/3 flex flex-grow-1">
            <div class="w-full grid grid-cols-2 gap-4 p-2">
              <div class="bg-block-black-accent rounded-lg flex flex-col">
                <div class="w-full flex flex-none">
                  <img class="h-24 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[1].author }}</p>
                  <p class="text-sm">{{ row[1].text | truncate(50) }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p class="text-xs">{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                </div>
              </div>
              <div class="bg-block-black-accent rounded-lg flex flex-col">
                <div class="w-full flex flex-none">
                  <img class="h-24 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[2].author }}</p>
                  <p class="text-sm">{{ row[2].text | truncate(50) }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p class="text-xs">{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                </div>
              </div>
              <div class="bg-block-black-accent rounded-lg flex flex-col">
                <div class="w-full flex flex-none">
                  <img class="h-24 object-cover rounded-t-lg w-full" :src="row[3].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[3].author }}</p>
                  <p class="text-sm">{{ row[3].text | truncate(50) }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p class="text-xs">{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                </div>
              </div>
              <div class="bg-block-black-accent rounded-lg flex flex-col">
                <div class="w-full flex flex-none">
                  <img class="h-24 object-cover rounded-t-lg w-full" :src="row[4].image_path" />
                </div>
                <div class="flex-col flex flex-1 p-2">
                  <p class="text-block-gray">{{ row[4].author }}</p>
                  <p class="text-sm">{{ row[4].text | truncate(50) }}</p>
                </div>
                <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                  <p class="text-xs">{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                </div>
              </div>

              <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 col-span-2">
                <div class="w-full flex flex-col flex-none p-1 place-content-between">
                  <div class="flex flex-1">
                    <div class="flex-col flex flex-1 w-2/3">
                      <p class="text-block-gray">{{ row[5].author }}</p>
                      <p class="text-sm">{{ row[5].text | truncate(50) }}</p>
                    </div>
                    <div class="w-1/3 flex flex-none">
                      <img class="object-cover rounded-lg" :src="row[5].image_path" />
                    </div>
                  </div>
                  <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
                    <p class="text-xs">{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 7">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full grid grid-cols-2 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[0].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[0].author }}</p>
              <p class="text-lg">{{ row[0].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[0].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[1].author }}</p>
              <p class="text-lg">{{ row[1].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].topic | truncate(10) }}</p>
            </div>
          </div>
        </div>
        <div class="w-full">
          <div class="flex overflow-x-scroll pb-10 hide-scroll-bar">
            <div class="flex flex-nowrap">
              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[2].author }}</p>
                        <p class="text-base">{{ row[2].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[2].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[2].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[3].author }}</p>
                        <p class="text-base">{{ row[3].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[3].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[3].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[4].author }}</p>
                        <p class="text-base">{{ row[4].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[4].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[4].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[5].author }}</p>
                        <p class="text-base">{{ row[5].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[5].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[5].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[6].author }}</p>
                        <p class="text-base">{{ row[6].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[6].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[6].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[6].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[7].author }}</p>
                        <p class="text-base">{{ row[7].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[7].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[7].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[7].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[8].author }}</p>
                        <p class="text-base">{{ row[8].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[8].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[8].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[8].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[9].author }}</p>
                        <p class="text-base">{{ row[9].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[9].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[9].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[9].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="inline-block px-1">
                <div class="bg-block-black-accent rounded-lg flex flex-1 p-1 w-64">
                  <div class="w-full flex flex-col flex-none p-1 place-content-between">
                    <div class="flex flex-1">
                      <div class="flex-col flex flex-1 w-2/3">
                        <p class="text-block-gray">{{ row[10].author }}</p>
                        <p class="text-base">{{ row[10].text }}</p>
                      </div>
                      <div class="w-1/3 flex flex-none">
                        <img class="object-cover rounded-lg" :src="row[10].image_path" />
                      </div>
                    </div>
                    <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                      <p>{{ row[10].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                      <p class="mx-1">&#8226;</p>
                      <p>{{ row[10].topic | truncate(10) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 9">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>1</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-lg">{{ row[0].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[0].topic | truncate(10) }}</p>
            </div>
          </div>

          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>2</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[1].author }}</p>
                <p class="text-lg">{{ row[1].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].topic | truncate(10) }}</p>
            </div>
          </div>

          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>3</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[2].author }}</p>
                <p class="text-lg">{{ row[2].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[2].topic | truncate(10) }}</p>
            </div>
          </div>

          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>4</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[3].author }}</p>
                <p class="text-lg">{{ row[3].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[3].topic | truncate(10) }}</p>
            </div>
          </div>

          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>5</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[4].author }}</p>
                <p class="text-lg">{{ row[4].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[4].topic | truncate(10) }}</p>
            </div>
          </div>

          <div class="bg-block-black-accent rounded-lg flex flex-col flex-1">
            <div class="flex flex-1 p-2">
              <div class="w-10 h-10 rounded-full flex justify-center items-center bg-green-600 mt-3">
                <p>6</p>
              </div>
              <div class="flex-col flex flex-1 p-2">
                <p class="text-block-gray">{{ row[5].author }}</p>
                <p class="text-lg">{{ row[5].text }}</p>
              </div>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[5].topic | truncate(10) }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 11">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-2/3 flex flex-none">
              <img class="object-cover rounded-l-lg" :src="row[0].image_path" />
            </div>
            <div class="w-1/3 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-lg">{{ row[0].text }}</p>
                <p class="text-sm text-gray-400">{{ row[0].content | truncate(100) }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[1].author }}</p>
              <p class="text-lg">{{ row[1].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[2].author }}</p>
              <p class="text-lg">{{ row[2].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[2].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[3].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[3].author }}</p>
              <p class="text-lg">{{ row[3].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[3].topic | truncate(10) }}</p>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[4].author }}</p>
                  <p class="text-base">{{ row[4].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[4].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[4].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[5].author }}</p>
                  <p class="text-base">{{ row[5].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[5].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[5].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[6].author }}</p>
                  <p class="text-base">{{ row[6].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[6].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[6].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[6].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 12">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full">
          <div class="bg-block-black-accent rounded-lg flex drop-shadow-lg shadow-md h-64 shadow-lg shadow-gray-500/50">
            CHART
          </div>
        </div>

        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-lg">{{ row[0].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[0].image_path" />
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[1].author }}</p>
                <p class="text-lg">{{ row[1].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[1].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[1].image_path" />
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-1/2 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[2].author }}</p>
                <p class="text-lg">{{ row[2].text }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[2].topic | truncate(10) }}</p>
              </div>
            </div>
            <div class="w-1/2 flex flex-none">
              <img class="object-cover rounded-r-lg" :src="row[2].image_path" />
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 15">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full">
          <div class="bg-block-black-accent rounded-lg flex">
            <div class="w-2/3 flex flex-none">
              <img class="object-cover rounded-l-lg" :src="row[0].image_path" />
            </div>
            <div class="w-1/3 justify-end flex flex-col flex-none p-2">
              <div class="flex-col flex flex-1">
                <p class="text-block-gray">{{ row[0].author }}</p>
                <p class="text-lg">{{ row[0].text }}</p>
                <p class="text-sm text-gray-400">{{ row[0].content | truncate(100) }}</p>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[0].subtopic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full grid grid-cols-4 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[1].author }}</p>
              <p class="text-lg">{{ row[1].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[2].author }}</p>
              <p class="text-lg">{{ row[2].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[2].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[3].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[3].author }}</p>
              <p class="text-lg">{{ row[3].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[3].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[4].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[4].author }}</p>
              <p class="text-lg">{{ row[4].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[4].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[5].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[5].author }}</p>
              <p class="text-lg">{{ row[5].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[5].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[6].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[6].author }}</p>
              <p class="text-lg">{{ row[6].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[6].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[6].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[7].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[7].author }}</p>
              <p class="text-lg">{{ row[7].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[7].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[7].subtopic | truncate(7) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[8].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[8].author }}</p>
              <p class="text-lg">{{ row[8].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[8].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[8].subtopic | truncate(7) }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>

    <div v-if="layout == 17">
      <div v-for="(row, index) in this.data" :key="row.id">
        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[0].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[0].author }}</p>
              <p class="text-lg">{{ row[0].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[0].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[0].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[1].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[1].author }}</p>
              <p class="text-lg">{{ row[1].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[1].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[1].topic | truncate(10) }}</p>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-col">
            <div class="w-full flex flex-none">
              <img class="h-48 object-cover rounded-t-lg w-full" :src="row[2].image_path" />
            </div>
            <div class="flex-col flex flex-1 p-2">
              <p class="text-block-gray">{{ row[2].author }}</p>
              <p class="text-lg">{{ row[2].text }}</p>
            </div>
            <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2 px-2">
              <p>{{ row[2].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
              <p class="mx-1">&#8226;</p>
              <p>{{ row[2].topic | truncate(10) }}</p>
            </div>
          </div>
        </div>

        <div class="w-full grid grid-cols-3 gap-4 p-2">
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[3].author }}</p>
                  <p class="text-base">{{ row[3].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[3].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[3].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[3].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[4].author }}</p>
                  <p class="text-base">{{ row[4].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[4].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[4].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[4].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
          <div class="bg-block-black-accent rounded-lg flex flex-1 p-1">
            <div class="w-full flex flex-col flex-none p-1 place-content-between">
              <div class="flex flex-1">
                <div class="flex-col flex flex-1 w-2/3">
                  <p class="text-block-gray">{{ row[5].author }}</p>
                  <p class="text-base">{{ row[5].text }}</p>
                </div>
                <div class="w-1/3 flex flex-none">
                  <img class="object-cover rounded-lg" :src="row[5].image_path" />
                </div>
              </div>
              <div class="flex flex-none p-1 border-t-2 border-block-gray text-sm text-block-gray mt-2">
                <p>{{ row[5].created_at | moment('timezone', date_timezone, datetime_format) | ago }}</p>
                <p class="mx-1">&#8226;</p>
                <p>{{ row[5].topic | truncate(10) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mx-auto mt-5 text-center justify-items-center" v-if="this.data.length > 0">
        <button @click="load_more" type="button" class="mx-auto text-center w-32 h-12 items-center py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-block-white bg-block-black-accent hover:text-block-white">
          <span v-if="!loading"> More </span>

          <svg v-if="loading" role="status" class="mx-auto w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"></path>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import config from '@/config';
import { serverBus } from '@/main';
import { mapState, mapActions } from 'vuex';

export default {
  name: 'News',
  components: {},
  props: {
    callback: null,
    service: String,
    layout: String,
  },

  data() {
    return {
      data: [],
      loading: true,
      pages: 1,
      layouts: {
        1: 6,
        2: 5,
        3: 10,
        4: 6,
        5: 6,
        6: 6,
        7: 5,
        8: 5,
        9: 6,
        10: 6,
        11: 7,
        12: 4,
        13: 6,
        14: 6,
        15: 10
      },
    };
  },

  methods: {
    ...mapActions('news', ['get_news']),
    load_more: function () {
      this.pages = this.pages + 1;
      this.load_news();
    },
    load_news: function () {
      this.loading = true;

      this.get_news({ service: this.service, pages: this.pages })
        .then(({ data }) => {
          var offset = this.layouts[this.layout];

          this.data = [];

          for (let x = 1; x <= this.pages; x++) {
            var arr_ = [];

            for (let y = offset * (x - 1); y < offset + offset * (x - 1); y++) {
                if(this.news.all_news[this.service][y]){
                    arr_.push(this.news.all_news[this.service][y]);
                } else {
                    var empty = {
                        "text":"",
                        "created_at":"",
                        "logo":"",
                        "author":"",
                        "content":"",
                        "image_path":"",
                        "thumb_path":"",
                        "topic":""
                    }
                    arr_.push(empty);
                }

            }

            if(arr_.length < (offset*this.pages)){
                for(let z = arr_.length; z < (offset*this.pages); z++){
                    var empty = {
                        "text":"",
                        "created_at":"",
                        "logo":"",
                        "author":"",
                        "content":"",
                        "image_path":"",
                        "thumb_path":""
                    }
                    arr_.push(empty);
                }

            }
            this.data.push(arr_);
          }

          this.loading = false;
        })
        .catch((error) => {
          // this.$auth_check(error);
          this.$error_notification(error);
        });
    },
  },
  computed: {
    ...mapState(['news']),
  },
  mounted() {
    this.load_news();
  },
  watch: {},
};
</script>
<style src="./news.scss" lang="scss" />
