import axios from "axios";
import config from "resources/js/config";
import { globalStore } from "resources/js/main.js";
// import data from "../pages/Doors/data";

export default {
    namespaced: true,
    state: {
        all_news: {
            "Trending News":[],
            "Around the Markets":[],
            "Top News":[],
            "NFTs":[],
            "DeFi":[]
        },
        grouped_news: []
    },
    getters: {},
    mutations: {
        SET_: (state, payload) => {
            try {
                state.news = JSON.parse(payload.data.response);
            } catch (e) {
                state.news = payload.data.response;
            }
        },

        SET_ALL_NEWS: (state, payload) => {

            // console.log("COMMIT");
            state.all_news[payload.service] = payload.data.data.response;

        }
    },
    actions: {
        get_news_today: async ({ commit }, object) => {
            if (object == undefined) object = {};
            object["p"] = {
                u: globalStore.user_id,
                l: globalStore.level
            };

            let { data } = await axios.post(config.baseURLApi + "/news/today", object, { headers: config.headers }).catch(error => {
                return Promise.reject(new Error(error));
            });
            commit("SET_ALL_NEWS", data);
            return data;
        },
        record_read: async ({ commit }, object) => {
            if (object == undefined) object = {};
            object["p"] = {
                u: globalStore.user_id,
                l: globalStore.level
            };

            let { data } = await axios.post(config.baseURLApi + "/news/read", object, { headers: config.headers }).catch(error => {
                return Promise.reject(new Error(error));
            });
            return data;
        },
        share_news: async ({ commit }, object) => {
            if (object == undefined) object = {};
            object["p"] = {
                u: globalStore.user_id,
                l: globalStore.level
            };

            let { data } = await axios.post(config.baseURLApi + "/news/share", object, { headers: config.headers }).catch(error => {
                return Promise.reject(new Error(error));
            });
            return data;
        },
        get_news: async ({ commit }, object) => {
            if (object == undefined) object = {};
            object["p"] = {
                u: globalStore.user_id,
                l: globalStore.level
            };

            let { data } = await axios.post(config.baseURLApi + "/news/get_news", object, { headers: config.headers }).catch(error => {
                return Promise.reject(new Error(error));
            });

            var wrapper = {"service":object.service,"data":data}

            commit("SET_ALL_NEWS", wrapper);
            return data;
        }
    }
};
