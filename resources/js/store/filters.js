import axios from "axios";
import config from "resources/js/config";
import { globalStore } from 'resources/js/main.js'
// import data from "../pages/Doors/data";

export default {
  namespaced: true,
  state: {
    filters:[],
  },
  getters: {

  },
  mutations: {
    SET_: (state, payload) => {
      try {
        state.filters = JSON.parse(payload.data.response);
      }
      catch (e) {

        state.filters = payload.data.response;
      }

    },

  },
  actions: {
    get_filters_list: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object["p"] = {
        u: globalStore.user_id,
        l: globalStore.level
      }
      let { data } = await axios.post(config.baseURLApi + '/filters/list', object,{headers:config.headers}).catch(error => {
        return Promise.reject(new Error(error))
      });
      commit('SET_', data)
      return data
    },


  },
};

