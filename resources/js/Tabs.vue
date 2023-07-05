<template>
    <div id="app">
      <br>
      <!-- @click="selected = tab;"
       @handleClicked="updataSelected" -->
       
      <button
      v-for="(tab) in tabs"
      :key="tab"
      @click="updataSelected(selected=tab)"
      :class="['tab-btn', { active: selected === tab }]"
    >
      {{ tab }}
    </button>
    <hr>
    <component :is="selected" class="tab"></component>
  
    </div>
  </template>
  
  <script>
  import User from "./views/User/User.vue";
  import Roles from "./views/Roles/Roles.vue";
  import Department from "./views/Department/Department.vue";
  
  
  export default {
    name: "App",
    components: {
      User,
      Roles,
      Department
    },
    data() {
      return {
        index:'',
        tabs: [
          "User","Roles","Department"
        ],
        selected: 0
      }
    },
    methods: {
      updataSelected(tab) {
        console.log(tab);
        
        // selected = tab;
        // this.selected = idx;
        // this.curContent = this.tabs[idx];
        // if(tab==this.tabs[index]){
        //   this.selected=index
        // }
        // console.log(this.selected)
         return tab
     }
    },
    created(){
      this.updataSelected('User')
    }
  }
  </script>
  <!-- // {
          //   title: "User",
          //   badges: "",
          //   content: "User"
          // },
          // {
          //   title: "Role",
          //   badges: "",
          //   content: "Roles"
          // },
          // {
          //   title: "Department",
          //   badges: "",
          //   content: "Department"
          // },
  created(){
     // this.updataSelected(0);
  // },
  // methods: {
  //   // updataSelected(tab,index) {
  //   //   console.log(tab);
  //   //   console.log(index);
  //   //   // selected = tab;
  //   //   // this.selected = idx;
  //   //   // this.curContent = this.tabs[idx];
  //   //   this.index=tab==this.tabs[index]
  //   //   console.log(this.index)
  //   //   return this.index

      
    }
  } -->
  
  <style>
  .tab-btn {
  padding: 6px 10px;
  background: #ffffff;
  cursor: pointer;
  margin-bottom: 1rem;
  border: 2px solid #cccccc;
  outline: none;
}

.active {
  border-bottom: 3px solid green;
  background: rgb(235, 190, 237);
}

.tab {
  border: 1px solid #ccc;
  padding: 10px;
}
  </style>
  