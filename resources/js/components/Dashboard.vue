<template>
  <div class="leading-normal tracking-normal" id="main-body ">
    <div class="flex flex-wrap">

      <Sidebar />

      <div class="w-full bg-gray-100 pl-0 lg:pl-64" :class="sideBarOpen ? 'overlay' : ''" id="main-content">

        <Navbar />

        <div class="p-6 mb-20">
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

import Sidebar from './Sidebar'
import Navbar from './Navbar'

export default {
  name: 'Dashboard',
  computed: {
    ...mapState(['sideBarOpen'])
  },
  components: {
    Sidebar,
    Navbar
}
}
</script>
