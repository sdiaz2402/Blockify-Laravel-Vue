<template>
<div :class="[{root: true, sidebarClose, sidebarStatic}, dashboardThemeClass, 'sing-dashboard']">
  <div class="">
    <v-touch class="content" @swipe="handleSwipe" :swipe-options="{direction: 'horizontal'}">
      <transition name="router-animation">
        <router-view />
      </transition>
    </v-touch>
  </div>
</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';
const { mapState, mapActions } = createNamespacedHelpers('layout');

import Sidebar from 'resources/js/components/Sidebar/Sidebar';
import Header from 'resources/js/components/Header/Header';
import { serverBus } from 'resources/js/main';
import './Layout.scss';

export default {
  name: 'SetupLayout',
  components: { Sidebar, Header },
  data() {
      return {
          show_loader:false,
          rerquest:0,
          interval:null
      }
  },
  methods: {
    ...mapActions(['switchSidebar', 'handleSwipe', 'changeSidebarActive', 'toggleSidebar']),
    handleWindowResize() {
      const width = window.innerWidth;

      if (width <= 768 && this.sidebarStatic) {
        this.toggleSidebar();
        this.changeSidebarActive(null);
      }
    },
    handleLongProcesses(){
        // console.log("Loader Timer Reached");
        if(this.rerquest > 0){
            this.rerquest = this.rerquest - 1;
        }
        // console.log("FORCED HIDE Loader "+this.rerquest);
        if(this.rerquest <= 0){
            this.show_loader = false;
        }
    },
    hide_loader(){
        this.rerquest = 0;
        this.show_loader=false;
    }
  },
  computed: {
    ...mapState(["sidebarClose", "sidebarStatic", "dashboardTheme"]),
    dashboardThemeClass: function () {return "dashboard-" + this.dashboardTheme}
  },
  mounted(){
        serverBus.$on("show_loader", (source) => {
            // console.log("SHOW Loader "+this.rerquest+" from: "+source);
            this.show_loader = true;
            this.rerquest = this.rerquest+1;
            const self = this;
            setTimeout(function(){
                self.handleLongProcesses();
            },45000);
        });

        serverBus.$on('hide_loader', (source) => {
            // console.log("HIDE Loader "+this.rerquest+" from: "+source);
            this.rerquest = this.rerquest-1;
            if(this.rerquest <= 0) {
                this.rerquest = 0;
                this.hide_loader();
            }

        });
  },
  created() {
    const staticSidebar = JSON.parse(localStorage.getItem('sidebarStatic'));

    if (staticSidebar) {
      this.$store.state.layout.sidebarStatic = true;
    } else if (!this.sidebarClose) {
      setTimeout(() => {
        this.switchSidebar(true);
        this.changeSidebarActive(null);
      }, 2500);
    }

    this.handleWindowResize();
    window.addEventListener('resize', this.handleWindowResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleWindowResize);
  }
};
</script>

<style src="./Layout.scss" lang="scss" />
