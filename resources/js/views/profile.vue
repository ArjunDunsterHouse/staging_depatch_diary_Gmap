<template>
   
   <!--<autoLogout v-if="login.logged_status=='true'"/>-->
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>   
   <b-container fluid class="screenLayoutsWidth">
   <b-row>
      <b-col cols="4"></b-col>
      <b-col cols="1" class="profileText"><strong>Name:</strong></b-col>
      <b-col cols="1" class="profileText"><span> {{login.profileData.name}}</span></b-col>
      <b-col cols="5"></b-col>
  </b-row>
 
  <b-row>

      <b-col cols="4"></b-col>
      <b-col cols="1" class="profileText"><strong>User Name:</strong></b-col>
      <b-col cols="1" class="profileText"><span>{{login.profileData.user_name}}</span></b-col>
      <b-col cols="6"></b-col>
  </b-row>

  <b-row>

      <b-col cols="4"></b-col>
      <b-col cols="1" class="profileText"><strong>Role:</strong></b-col>
      <b-col cols="1" class="profileText"><span>{{login.profileData.role}}</span></b-col>
      <b-col cols="6"></b-col>
  </b-row>


  <b-row>

      <b-col cols="4"></b-col>
      <b-col cols="1" class="profileText"><strong>Email:</strong></b-col>
      <b-col cols="1" class="profileText"><span> {{login.profileData.email_id}}</span></b-col>
      <b-col cols="6"></b-col>
  </b-row>

  
  <b-row>
      <b-col cols="4"></b-col>
      <b-col cols="1" class="profileText"><strong>Department:</strong></b-col>
      <b-col cols="1" class="profileText"><span> {{login.profileData.department_name}}</span></b-col>
      <b-col cols="6"></b-col>
  </b-row>

  <b-row>
      <b-col cols="4"></b-col>

      <b-col cols="1" class="profileButtons">
            <FormKit
               type="button"
               @click="RequestEdit"
               style="background-color:#0275ff;
                              padding-left: 25px;
                              margin-left: 10px;"
               >
               <strong>Request Edit</strong>
            </FormKit>
      </b-col>

      <b-col cols="1"  class="profileButtons">
            <FormKit
               type="button"
               @click="ChangePassword"
               style="background-color:#0275ff;
                              padding-left: 25px;
                              margin-left: 10px;"
               >
               <strong>Change Password</strong>
            </FormKit>
      </b-col>

      <b-col cols="6"></b-col>
  </b-row>
</b-container>
<br/>

</template>
 
<script setup>
   //import autoLogout from '../components/AutoLogout.vue';
   import { SignIn } from '../store/login.js'
   import { profile } from '../store/profile.js'

   const login = SignIn();

   const userDetails = profile();

   function RequestEdit(){

      userDetails.requestProfileEdit()
   }


   function ChangePassword(){
      // console.log(login.profileData.password);
      // console.log(login.admin_user_id);
      userDetails.passwordChange(login.profileData.password,login.admin_user_id);
      
   }
  

</script>
<style scoped>
.profileText{

   font-size: 22px;
   color: #5D2296;
   padding:15px;
   border-bottom: 1px solid #5D2296ad;
}

.profileButtons{
   margin-top:40px;
}

</style>
 
