import axios from "axios";
import config from "resources/js/config";
import { globalStore } from 'resources/js/main.js'

export default {
  namespaced: true,
  state: {
    reading_club: [],
  },
  getters: {


  },
  mutations: {

    SET_USERS: (state, payload) => {
      try {
        state.reading_club = JSON.parse(payload.data.response);
      }
      catch (e) {
        state.reading_club = payload.data.response;
      }

    },


  },
  actions: {
    reading_club: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
      let { data } = await axios.post(config.baseURLApi + '/user/reading_club', object,
      {headers:config.headers}
      )
      commit('SET_USERS', data);
      return data;
    },
    add_reader: async ({ commit }, object) => {
        let { data } = await axios.post(config.baseURLApi + '/user/add_reader',object,
        {headers:config.headers}
        )
        return data;
      },
      remove_reader: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
        let { data } = await axios.post(config.baseURLApi + '/user/remove_reader', object,
        {headers:config.headers}
        )
        return data;
      },
    search_readers: async ({ commit }, object) => {
        if (object == undefined) object = {};
        object["p"] = {
          u: globalStore.user_id,
          l: globalStore.level
        }
      let { data } = await axios.post(config.baseURLApi + '/user/search',object,
      {headers:config.headers}
      )
      return data;
    },
  }


};

