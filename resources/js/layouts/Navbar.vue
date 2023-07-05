<template>

  <nav class="navbar fixed-top navbar-expand-lg">
    <div class="logo">
			<img :src="logo" alt="Vue" /> 
		</div>
    <a class="navbar-brand" href="#"><strong> Vehicle Diary</strong></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
     
      






<div class="navlinks">

      <router-link :to="{ path: '/dashboard' }" class="button" title="Dashboard">
        <span class="text" >Dashboard</span>
			</router-link>


      <div id="Admin" v-if="userLogin.role=='admin' || userLogin.role=='despatch_manager' || userLogin.role=='marketing_edit'">
			<router-link :to="{ path: '/vehicle' }" class="button" title="Vehicles List">
        <span class="text">Vehicles List</span>
			</router-link>

			<router-link :to="{ path: '/users' }" class="button" title="Users Profile">
        <span class="text">Users Profile</span>
			</router-link>
			<router-link :to="{ path: '/branch' }"  class="button" title="DH Branch Details">
        <span class="text">DH Branch Details</span>
			</router-link>
		
		</div>
      
</div>

       <!-- New orders refresh div-->

		<div id="lastUpdatesHeader" v-b-toggle.collapse-1 v-if="searchSelectedData.divVisibility=='true'">

      <div>New order<span class="arrow_styles material-icons">arrow_forward</span>refresh your page<span class="arrow_styles material-icons">arrow_forward</span>click to see table</div>
      <br/>
      <b-collapse id="collapse-1" class="mt-2">
        <b-card>
          <table class="table tablesRow table-bordered">
              <thead>
                <tr>
                <th scope="col">Time</th>
                <th scope="col" v-if="searchSelectedData.selectedBranch==0">Branch</th>
                <th scope="col">Order no.</th>
                <th scope="col">Van No.</th>
                <th scope="col">Promised Date</th>
                </tr>
              </thead>
              <tbody v-for="(value) in searchSelectedData.tableRefreshData" :key="value">
                <tr>
                  <td>{{new Date(value['last_update']).toLocaleTimeString()}}</td>
                  <td v-if="searchSelectedData.selectedBranch==0">{{value['location_code']}}</td>
                  <td>{{value['order_no']}}</td>
                  <td>{{value['shipping_agent_service_code']}}</td>
                  <td>{{new Date(value['promised_delivery_date']).toLocaleDateString()}}</td>
                </tr>
              </tbody>
          </table>
        </b-card>
      </b-collapse>
		</div>
        
      <ul id="moveright" class="navbar-nav mr-auto">
        
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="material-icons">person</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            
            <router-link :to="{ path: '/profile' }" class="button plink" title="Your Profile">
              <!-- <span class="material-icons" @click="loadPage">person</span> -->
              <span class="text drop plink">Your Profile</span>
			      </router-link>
            <br>
            <hr> 
            
              <button class="text drop" @click="logout">Logout</button>
           
          
          </div>
        </li>
        
        
      </ul>
      <li id="weekNumber">
            <span >
             <strong> Week {{'  '+navbarData.getWeek}}</strong>
            </span>
        </li>
        
    </div>
  </nav>
  
  </template>
   
  <script setup>
    import { navbar } from '../store/navbar.js'
    import {SignIn} from '../store/login.js'
    import {LogOut} from '../store/logout.js'
    import { search } from '../store/searchData.js'
    import logoURL from '../assets/logo.png';
    import { VueCookieNext } from 'vue-cookie-next';



    const logo = logoURL;
    const searchSelectedData = search();
    const userLogin = SignIn();
    const navbarData = navbar();
    const userLogout = LogOut();

function logout(){
  
  userLogout.logout(userLogin.admin_user_id,userLogin.logged_status,VueCookieNext.getCookie('selected_branch'))
  userLogin.logged_status='false';
  
}    

</script>

  <style lang="scss" scoped>
.logo {
			
			img {
				width: 4rem;
			}
		}
  
  .drop{
    padding-left: 38px
  }

  .plink{
    text-decoration: none;
    color:#000;
    padding-top:5px;
  }
</style>