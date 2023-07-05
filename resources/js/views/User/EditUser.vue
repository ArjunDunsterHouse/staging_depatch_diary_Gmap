<template>
     <b-container fluid class="screenLayoutsWidth2">
        <div>
            <br>
            <b-row style="margin-top:55px;">
                <b-col cols="4">
                    <router-link to="/users" class="btn btn-primary"><span class="arrow_styles material-icons">arrow_back</span></router-link>
                </b-col>
                <b-col cols="3"  class="headingAdjust">
        
                    <h1 class="text-center" > User Profile Update</h1>
                </b-col>
                <b-col cols="5"></b-col>
            </b-row>
        </div>
        <hr>


        <b-form-row class="editUserForm">
            <FormKit
            type="form"
            @submit ="userEdit"
            >
                <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        
                        <FormKit
                        type="text"
                            name="user-id"
                            label="User Name"
                            help="Logging username(Ex:JoeL)"
                            :validation="[['required'],['length:0,25']]"
                            placeholder="JoeL"
                            v-model="editUserDetail.userForm.user_name"
                        />
                        <!--['matches', /^[A-Z]{1}[a-z]{0,10}[A-Z]{1}$/] regex for user name-->
                    </b-col>
                  
                    <b-col cols="4">
                        <FormKit
                        type="text"
                            name="name"
                            label="Name"
                            validation="required|length:1,20"
                            placeholder="Joe Lewis"
                            help="FullName(Ex: Joe Lewis)"
                            v-model="editUserDetail.userForm.name"
                        />
                    </b-col>
                    <b-col cols="2"></b-col>
                </b-row>


                <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        <FormKit
                        type="select"
                            name="role"
                            label="Role"
                            help="Console acess role"
                            validation="required"
                            v-model="editUserDetail.userForm.role"
                        >
                            <option id="searchDropdown" v-for="(role,index) in editUserDetail.roles" :key="index" :value="role">
                                {{role}}
                            </option>
                        </FormKit>
                        </b-col>
                        <b-col cols="4">
                            <FormKit
                                type="text"
                                name="email-id"
                                label="Email Id"
                                help="Offical email id(Ex:JoeLewis@dunsterhouse.co.uk)"
                                validation="required|ends_with:@dunsterhouse.co.uk"
                                placeholder="JoeLewis@dunsterhouse.co.uk"
                                v-model="editUserDetail.userForm.email_id"
                            />
                        </b-col>
                        <b-col cols="2"></b-col>
                    </b-row>

                    <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        <FormKit
                            type="select"
                            name="department-name"
                            label="Department Name"
                            help="User from which department?"
                            validation="required"
                            v-model="editUserDetail.userForm.department_name "
                            
                        >
                            <option id="searchDropdown" v-for="(department,index) in editUserDetail.departments" :key="index" :value="department">
                                {{department}}
                            </option>
                        </FormKit>
                    </b-col>
                    <b-col cols="4">
                        <FormKit
                        type="select"
                            name="status"
                            label="Status"
                            validation="required"
                            help="Only active users can access the site"
                            v-model="editUserDetail.userForm.profile_status"
                        >
                            <option id="searchDropdown" v-for="(status,index) in editUserDetail.available_status" :key="index" :value="status">
                                {{status}}
                            </option>
                        </FormKit>
                    </b-col>
                    
                
                    <b-col cols="2"></b-col>
                </b-row>
            
            </FormKit>

                        <FormKit
                            type="button"
                            @click="editUserDetail.deleteUser(userid)"
                        >Delete
                        </FormKit>
        </b-form-row>

    </b-container> 

</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { editUser } from '../../store/editUser.js'
  
  const editUserDetail = editUser();
  const route = useRoute();

  let userid = route.query.id

  const userEdit = () =>{
    editUserDetail.updateUserDetails(userid)
  }
  onMounted(()=>{

        editUserDetail.EditUser(userid);
    
    });
    
                
      

 
    </script>