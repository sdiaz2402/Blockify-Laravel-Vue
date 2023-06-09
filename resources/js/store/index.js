import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersist from 'vuex-persist';

import layout from './layout';
import auth from './auth';
import dashboard from './dashboard';
import sources from './sources';
import watchlist from './watchlist';
import users from './users';
import news from './news';
import filters from './filters';

Vue.use(Vuex);

const vuexLocalStorage = new VuexPersist({
  key: 'vuex', // The key to store the state on in the storage provider.
  storage: window.localStorage, // or window.sessionStorage or localForage
  // Function that passes the state and returns the state with only the objects you want to store.
  // reducer: state => state,
  // Function that passes a mutation and lets you decide if it should update the state in localStorage.
  // filter: mutation => (true)
})


export default new Vuex.Store({
  plugins: [vuexLocalStorage.plugin],
  modules: {
    layout,
    filters,
    auth,
    dashboard,
    watchlist,
    sources,
    users,
    news,
  },
});
