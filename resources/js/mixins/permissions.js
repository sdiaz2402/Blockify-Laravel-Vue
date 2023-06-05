import { globalStore } from 'resources/js/main.js'

export default {
    data: () => {
        return {
        }
    },
  methods: {
    permissions: function(access_requested,author=0,explicit=false) {

        var level = false;
        var origin = false;
        if(globalStore.user){
            if(author == globalStore.user_id) origin = true;
            if (parseInt(globalStore.user.level) >= access_requested) level = true;
            if(explicit){
                return level && origin;
            } else {
                return level || origin;
            }
        } else{
            return false;
        }
    }
  }
};
