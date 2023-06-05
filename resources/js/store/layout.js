




export default {
  namespaced: true,
  state: {
        sideBarOpen: false
    },
    getters: {
        sideBarOpen: state => {
            return state.sideBarOpen
        }
    },
    mutations: {
        toggleSidebar (state) {
            state.sideBarOpen = !state.sideBarOpen
        }
    },
    actions: {
        toggleSidebar(context) {
            // console.log("I am toffling");
            context.commit('toggleSidebar')
        }
    }
};
