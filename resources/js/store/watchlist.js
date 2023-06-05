import axios from "axios";
import config from "@/config";
import { globalStore } from '@/main.js'
// import data from "../pages/Doors/data";

export default {
  namespaced: true,
  state: {
    watchlist: [],
    watchlist_unread: {},
  },
  getters: {
    favorites: state => {
        if(state.watchlist != null && state.watchlist != undefined && Array.isArray(state.watchlist)){
          return state.watchlist.filter(object => object.favorite == 1)
        } else {
          return [];
        }

      },
    watch: state => {
        if(state.watchlist != null && state.watchlist != undefined && Array.isArray(state.watchlist)){
          return state.watchlist.filter(object => object.favorite == 0)
        } else {
          return [];
        }
      },
  },
  mutations: {
    SET_: (state, payload) => {
    // console.log(payload);
      try {
        state.watchlist = JSON.parse(payload.list.data.response);
        state.watchlist_unread = JSON.parse(payload.unread.data.response);
        // console.log(state.watchlist_unread)
      }
      catch (e) {
        // console.log(e)
        // console.log(payload.data);
        state.watchlist = payload.list.data.response;
        state.watchlist_unread = payload.unread.data.response
        // console.log(state.watchlist_unread)
      }

    },
    SET_UNREAD: (state, payload) => {

          state.watchlist_unread = payload.data.response;


      },

      SET_FAVORITE: (state, object) => {

        const index = state.watchlist.findIndex(item => item.id == object.id);
        if(state.watchlist[index].favorite == 1){
            state.watchlist[index].favorite = 0;
        } else {
            state.watchlist[index].favorite = 1;
        }

      },
      SET_UNREAD_SINGLE: (state, payload) => {

            state.watchlist_unread[payload.ticker] = payload.data.data.response[payload.ticker];

      },

  },
  actions: {
    my_watchlist: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object["p"] = {
        u: globalStore.user_id,
        l: globalStore.level
      }
      let { data } = await axios.post(config.baseURLApi + '/watchlist/list', object,{headers:config.headers}).catch(error => {
        // console.log(error);
        return Promise.reject(new Error(error))
      });
      let  data2  = await axios.post(config.baseURLApi + '/watchlist/unread', object,{headers:config.headers}).catch(error => {
        //   console.log(error);
        return Promise.reject(new Error(error))
      });
    //   console.log(data2);
      commit('SET_', {list:data,unread:data2.data});


      return data
    },
    my_watchlist_unread: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/unread', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        commit('SET_UNREAD', data)
        return data
      },
      my_watchlist_unread_single: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/unread', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        commit('SET_UNREAD_SINGLE',  {ticker:object.ticker,data:data})
        return data
      },
      update_filter: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/update_filter', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        return data
      },
      view_watchlist: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/view_watchlist', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        return data
      },
      get_filters_count: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/get_filters_count', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        return data
      },
      mark_read: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/mark_read', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        // commit('SET_UNREAD', data)
        return data
      },
      update_price: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/update_price', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        // commit('SET_UNREAD', data)
        return data
      },
      mark_all_read: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/mark_all_read', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        // commit('SET_UNREAD', data)
        return data
      },

    search_stocks: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object["p"] = {
        u: globalStore.user_id,
        l: globalStore.level
      }
      let { data } = await axios.post(config.baseURLApi + '/watchlist/search', object,{headers:config.headers}).catch(error => {
        return Promise.reject(new Error(error))
      });
      return data
    },

    add_stock: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/add', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        return data
      },
      remove_stock: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/watchlist/remove', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        return data
      },

      favorite_stock: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }

        let { data } = await axios.post(config.baseURLApi + '/watchlist/favorite', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });

        commit('SET_FAVORITE', object)

        return data
      },



  },
};

