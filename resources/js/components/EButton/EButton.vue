<template>
  <div>
    <b-button variant="success" @click="action_func" :class="button_class" class="ebutton">
        <span v-bind:class="[loading ? 'no_visible' : '']">
        {{ content }}
        <i :class="icon_class" />
        </span>
        <span v-if="loading" class="overspan">
           <i class="la la-spinner la-spin"></i>
        </span>
      </b-button>
    </div>
</template>
<script>

export default {
  name: 'EButton',
  props: {
    slot_att: "",
    variant:"",
    button_class:"",
    action:null,
    loading:false,
    content:String,
    icon_class:""
  },
  methods:{
    action_func: function(){
      if(!this.loading){
        this.action();
      }
    }
  }
}
</script>
<style scoped>
  .ebutton{
    min-width: 60px;
    position: relative;
  }
  .overspan{
    position: absolute;
    z-index:30;
    width: 100%;
        height: 100%;
        position: absolute;
        top: 6px;
        left: 0;
  }
  .overspan i{
    font-size:20px;
  }
  .no_visible{
    visibility: hidden;
  }
</style>
