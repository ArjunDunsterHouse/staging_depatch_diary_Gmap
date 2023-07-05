<template>
   <b-container fluid class="screenLayoutsWidth2">
        <div>
        <br>
            <b-row style="margin-top:55px;">
                <b-col cols="4">
                    <router-link to="/users" class="btn btn-primary"><span class="arrow_styles material-icons">arrow_back</span></router-link>
                </b-col>
                <b-col cols="3" class="headingAdjust">
                    <h1 class="text-center"> Add User</h1>
                </b-col>
                <b-col cols="5"></b-col>
            </b-row>
        </div>
        <hr>
        <b-form-row class="createUserForm">
            
            <FormKit
            type="form"
            @submit ="AddUser"
            >
            <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="user-id"
                            label="User Name"
                            help="Login username (Eg: JoeL for Joe Lewis)"
                            :validation="[['required'],['length:0,25']]"
                            placeholder="JoeL"
                            v-model="createUser.userForm.user_name"
                        />
                        <!-- ['matches', /^[A-Z]{1}[a-z]{0,10}[A-Z]{1}$/] regex for username-->
                    </b-col>

                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="name"
                            label="Name"
                            validation="required|length:1,20"
                            placeholder="Joe Lewis"
                            help="FullName(Ex: Joe Lewis)"
                            v-model="createUser.userForm.name"
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
                            v-model="createUser.userForm.role"
                        >
                            <option id="searchDropdown" v-for="(role,index) in createUser.roles" :key="index" :value="role">
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
                            v-model="createUser.userForm.email_id"
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
                            v-model="createUser.userForm.department_name "
                            
                        >
                            <option id="searchDropdown" v-for="(department,index) in createUser.departments" :key="index" :value="department">
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
                            v-model="createUser.userForm.profile_status"
                            help="Only active users can access the site"
                        >
                            <option id="searchDropdown" v-for="(status,index) in createUser.available_status" :key="index" :value="status">
                                {{status}}
                            </option>
                        </FormKit>
                    </b-col>
                    <b-col cols="2"></b-col>
                </b-row>
                
            </FormKit>
            
                    <button class="ProfileCopy" v-if="createUser.userForm.user_name!=''&& createUser.userForm.name!=''&& createUser.userForm.role!=''&& createUser.userForm.email_id!=''&& createUser.userForm.department_name!=''&& createUser.userForm.profile_status!=''" @click="createUser.copy(createUser.userForm.user_name)">Copy</button>
                    
        </b-form-row>
    </b-container>
   
</template>

<script setup>

    import { createNewUser } from '../../store/createUser.js'
    import { onMounted } from 'vue'; 

    const createUser = createNewUser();


    const AddUser = ()=>{
        createUser.createUserRecord(createUser.userForm)
    }
    
    onMounted(()=>{

        createUser.canCopy = !!navigator.clipboard;

    });
  


</script>