<template>
  
  <!--<autoLogout v-if="login.logged_status=='true'"/>-->
  
  <login-page></login-page>
  
</template>
 
<script setup>
 
  import loginPage from './components/login.vue';
  //import autoLogout from './components/AutoLogout.vue';
  
  import { SignIn } from './store/login.js'
  
  const login = SignIn();
  
  </script>
