import config from 'resources/js/config';
import axios from 'axios';
import jwt from 'jsonwebtoken';
import router from 'resources/js/Routes';
import { globalStore } from 'resources/js/main.js';

axios.defaults.baseURL = config.baseURLApi;
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default {
  namespaced: true,
  state: {
    isFetching: false,
    errorMessage: '',
    user: { avatar: '', email: '', first_name: '', last_name: '', nickname: '' },
    organizations: [],
    web3_wallet: '',
  },
  mutations: {
    LOGIN_FAILURE(state, payload) {
      // console.log(payload);
      state.isFetching = false;
      state.errorMessage = payload;
    },
    SAVE_WEB3(state, payload) {
        state.web3_wallet = payload;
    },
    LOGIN_SUCCESS(state, payload) {
      state.isFetching = false;
      state.errorMessage = '';
      state.user = payload.user;
      globalStore.user = payload.user;
      localStorage.setItem('user', JSON.stringify(payload.user));
    },
    LOGIN_REQUEST(state) {
      state.isFetching = true;
    },
    LOGIN_RESET(state) {
      state.isFetching = false;
      state.errorMessage = '';
    },
  },
  actions: {
    loginUser_({ dispatch }, creds) {
      const post = {
        username: creds.email,
        password: creds.password,
        client_id: config.client,
        client_secret: config.secret,
        scope: '*',
        grant_type: 'password',
      };

      dispatch('requestLogin');
      if (creds.social) {
        window.location.href = config.baseURLApi + '/user/signin/' + creds.social + (process.env.NODE_ENV === 'production' ? '?app=sing-app-vue' : '');
      } else if (creds.email.length > 0 && creds.password.length > 0) {
        axios
          .post(config.baseURLApi + '/oauth/token', post, { crossDomain: true })
          .then((res) => {
            // console.log(res)
            const token = res.data.access_token;
            // console.log(token)
            dispatch('receiveToken', token);
          })
          .catch((err) => {
            // console.log(err)
            dispatch('loginError', err.response.data.error);
          });
      } else {
        dispatch('loginError', 'Something was wrong. Try again');
      }
    },

    auth_update_web3({commit}, account) {
        console.log("store");
        console.log(account);
      commit('SAVE_WEB3', account);
    },
    loginUser: async ({ dispatch }, creds) => {
      const post = {
        email: creds.email,
        password: creds.password,
      };
      dispatch('requestLogin');
      if (creds.email.length > 0 && creds.password.length > 0) {
        axios.defaults.withCredentials = true;
        let { data } = await axios.post(config.baseURLApi + '/user/login', post, { headers: config.headers });

        if (data.data.status == 'success') {
          localStorage.setItem('authenticated', true);
          dispatch('get_user');
        } else {
          dispatch('requestLoginReset');
          return data;
        }
      } else {
        dispatch('loginError', 'Something was wrong. Try again');
      }
    },
    register: async ({ dispatch }, object) => {
      if (object == undefined) object = {};
      let { data } = await axios.post(config.baseURLApi + '/user/register', object, { headers: config.headers }).catch((error) => {
        return Promise.reject(new Error(error));
      });
      return data;
    },
    // receiveToken({dispatch}, token) {

    //   // We check if app runs with backend mode
    //     // console.log(token);
    //     let decode = jwt.decode(token);
    //     // user_id = jwt.decode(token).aud;
    //     // delete user.id;
    //     // console.log(decode);

    //   localStorage.setItem('token', token);

    //   axios.defaults.headers.common['Authorization'] = "Bearer " + token;

    //   dispatch('get_user');
    // },
    get_user({ dispatch }) {
      // console.log(config.headers);
      axios
        .post(config.baseURLApi + '/user/info', {}, { headers: config.headers })
        .then((res) => {
          // console.log(res.data.data);
          let l_user = null;
          try {
            l_user = JSON.parse(res.data.data.response);
          } catch (e) {
            // console.log(res.data.data.response);
            l_user = res.data.data.response;
          }

          // console.log(l_user);
          localStorage.setItem('user', JSON.stringify(l_user.user));

          dispatch('receiveLogin', l_user);
        })
        .catch((err) => {
          dispatch('loginError', err.response.data);
        });
    },
    refresh_user({ dispatch }) {
      // console.log(config.headers);
      axios
        .post(config.baseURLApi + '/user/info', {}, { headers: config.headers })
        .then((res) => {
          console.log(res.data.data);
          let l_user = null;
          try {
            l_user = JSON.parse(res.data.data.response);
          } catch (e) {
            console.log(res.data.data.response);
            l_user = res.data.data.response;
          }

          console.log(l_user);
          localStorage.setItem('user', JSON.stringify(l_user.user));
        })
        .catch((err) => {});
    },
    logoutUser() {
      axios.get(config.baseURLApi + '/logout', { headers: config.headers }).then((res) => {
        localStorage.removeItem('authenticated');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        // console.log("Logout");
        router.push('/login');
      });
    },
    update_user: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object['p'] = {
        u: globalStore.user_id,
        l: globalStore.level,
      };
      let { data } = await axios.post(config.baseURLApi + '/user/update', object, { headers: config.headers }).catch((error) => {
        return Promise.reject(new Error(error));
      });
      // commit('SET_UNREAD', data)
      return data;
    },
    update_notifications: async ({ commit }, object) => {
      if (object == undefined) object = {};
      object['p'] = {
        u: globalStore.user_id,
        l: globalStore.level,
      };
      let { data } = await axios.post(config.baseURLApi + '/user/notifications', object, { headers: config.headers }).catch((error) => {
        return Promise.reject(new Error(error));
      });
      // commit('SET_UNREAD', data)
      return data;
    },
    loginError({ commit }, payload) {
      // console.log(payload);
      commit('LOGIN_FAILURE', payload);
    },
    receiveLogin({ commit }, payload) {
      commit('LOGIN_SUCCESS', payload);
      router.push('/');
    },
    requestLogin({ commit }) {
      commit('LOGIN_REQUEST');
    },
    requestLoginReset({ commit }) {
      commit('LOGIN_RESET');
    },
  },
};
