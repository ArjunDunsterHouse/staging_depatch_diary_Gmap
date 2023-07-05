<template>
      <b-container>
    
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
        <b-row>&nbsp;</b-row>
            <b-form-row>
 
                  <b-col></b-col>
                  <b-col></b-col>
                  <b-col></b-col>
                  <b-col></b-col>
                  <b-col></b-col>
                  <b-col></b-col>
                  <b-col></b-col>
                  
                  <b-col cols="4" class="loginLayout">
 
                        <FormKit type="group">
 
                                    <h2>Vehicle Diary Login</h2>
 
                              <FormKit
                                    type="text"
                                    label="Username"
                                    value="JohnL"
                                    help="Please enter your Dunster House username."
                                    
                                   
                                    v-model="login.userName"
                              />
 
                              <FormKit
                                    type="password"
                                    name="password"
                                    value="super-secret"
                                    label="Password"
                                    help="Login password"
                                    validation="required"
                                    validation-visibility="live"
                                    v-model="login.userPassword"
                              />
 
                              <FormKit
                                    type="button"
                                    @click="submit"
                                    style="background-color:#0275ff;
                                    padding-left: 25px;
                                    margin-left: 0px;"
                                    >
                                    Login
                              </FormKit>
                              <b-link href="" @click="login.resetPassword()">Reset Password</b-link> 
 
                        </FormKit>
                  </b-col>
 
                  <b-col></b-col>
 
            </b-form-row>
 
      </b-container>
 
</template>
 
<script setup>

      import { SignIn } from '../store/login.js'
     

      const login = SignIn();

      const submit = () => {
            
            login.checkLogin(login.userName,login.userPassword)
                
      }
</script>
       
 