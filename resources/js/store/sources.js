import axios from "axios";
import config from "resources/js/config";
import { globalStore } from 'resources/js/main.js'
// import data from "../pages/Doors/data";

export default {
  namespaced: true,
  state: {
    sources:[],
    sources_category:[],
  },
  getters: {

  },
  mutations: {
    SET_: (state, payload) => {
      try {
        state.sources = JSON.parse(payload.data.response);
      }
      catch (e) {

        state.sources = payload.data.response;
      }

    },

    SET_CAT: (state, payload) => {
        try {
          state.sources_category = JSON.parse(payload.data.response);
        }
        catch (e) {

          state.sources_category = payload.data.response;
        }

      },

  },
  actions: {
    get_source_list: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object["p"] = {
        u: globalStore.user_id,
        l: globalStore.level
      }
      let { data } = await axios.post(config.baseURLApi + '/sources/list', object,{headers:config.headers}).catch(error => {
        return Promise.reject(new Error(error))
      });
      commit('SET_', data)
      return data
    },

    get_source_category_list: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/sources/list_sources_category', object,{headers:config.headers}).catch(error => {
          return Promise.reject(new Error(error))
        });
        commit('SET_CAT', data)
        return data
      },


  },
};

