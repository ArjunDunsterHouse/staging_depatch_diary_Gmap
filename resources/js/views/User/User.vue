<template>
  <br><br>
<b-container fluid class="screenLayoutsWidth">
  
      <router-link style="margin-top:55px;" to="/create-user" class="btn btn-primary">Add User </router-link>
      <ScrollToBottom
		  />
      <br><br>
  
    <FormKit
          type="text"
          name="search-vehicle"
          label=""
          suffix-icon="search"
          validation-visibility="live"
          placeholder="Search Here"
          v-model="userList.search"
          
      />
      <div class="row">
          <div class="col-lg-12 mb-4">
              <div class="card">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h3 class="m-0 font-weight-bold" style="color:#5D2296;">Users List</h3>
                  
                      <FormKit
                          type="select"
                          name="user-status"    
                          v-model="userList.profileStatus"
                          @change="userList.allUsers()">
                          <option id="searchDropdown" v-for="(status_details,index) in userList.available_status" :key="index" :value="status_details"> 
                              {{status_details}}
                          </option>
                      </FormKit>
                    
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                      <thead class="thead-light">
                        <tr>
                          <th>User ID</th>
                          <th>Name</th>
                          <th>Email ID</th>
                          <th>Role</th>
                          <th>Department Name</th>
                          <th>Status</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(user) in userList.filtersearch" :key="user.admin_user_id">
                          <td>{{user.user_name}}</td>
                          <td>{{user.name}}</td>
                          <td>{{user.email_id}}</td>
                          <td>{{user.role}}</td>
                          <td>{{user.department_name}}</td>
                          <td>{{user.profile_status}}</td>
                          <td><router-link :to="{name:'edit-user', query:{id:user.admin_user_id}}" class="btn btn-sm btn-primary">Edit</router-link></td>
                        </tr>
                      
                      </tbody>
                    </table>
                  </div>
                  <div class="card-footer"></div>
                </div>
              </div>
            </div>

            <ScrollToTop
            />
    </b-container>         
  </template>
  
  
  
  
  <script setup>
    import { onMounted } from 'vue';
    import { useRoute } from 'vue-router';
    import { listOfUsers } from '../../store/userList.js'
    import ScrollToTop from '../../components/ScrollToTop.vue';
    import ScrollToBottom from '../../components/ScrollToBottom.vue';
    
    const userList = listOfUsers();
  
    const route = useRoute();
    let user = route.query.id 
    
    onMounted(()=>{
      
      userList.allUsers(userList.profileStatus);
    });
  </script>
