<template>
 
  <b-container fluid class="screenLayoutsWidth">

          <router-link style="margin-top:85px;" to="/create-vehicle" class="btn btn-primary">Add Vehicle </router-link>
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
              v-model="vehiclesData.searchTerm"
          />


          <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h3 class="m-0 font-weight-bold" style="color:#5D2296;">Vehicles List</h3>
                 
                  <FormKit
                          type="select"
                          name="user-status"    
                          v-model="vehiclesData.vehicleStatus"
                          @change="vehiclesData.allVehicles()">
                          <option id="searchDropdown" v-for="(status,index) in vehiclesData.vehiclestatus" :key="index" :value="status.index"> 
                              {{status.name}}
                          </option>
                      </FormKit>
                </div>
                <div class="table-responsive">
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th>Van Number</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Branch Name</th>
                        <th>Shipping Agent Service Code</th>
                        <th>Delivery Capacity</th>
                        <th>Target Amount</th>
                        <th>Registration Number</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr v-for="(van) in vehiclesData.filtersearch" :key="van.vehicle_id">
                        
                        <td>{{van.van_number}}</td>
                        <td>{{van.start_date}}</td>
                        <td>{{van.end_date}}</td>
                        <td>{{van.branchname}}</td>
                        <td>{{van.shipping_agent_service_code}}</td>
                        <td>{{van.delivery_capacity}}</td>
                        <td>{{van.target_amount}}</td>
                        <td>{{van.registration_number}}</td>
                        <td>{{van.vehicle_type}}</td>
                        <td><span v-if="(van.vehicle_status==0)">Not Active</span>
                          <span v-if="(van.vehicle_status==1)">Active</span>
                        <span v-if="(van.vehicle_status==2)">Inservice</span>
                        <span v-if="(van.vehicle_status==3)">Accident or Repair</span>
                        </td>
                        <td><router-link :to="{name:'edit-vehicle', query:{id:van.vehicle_id}}" class="btn btn-sm btn-primary">Edit</router-link></td>
                      </tr>
                    
                    </tbody>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
          </div>
          <!--Row-->

          <ScrollToTop
          />
  </b-container>
</template>

<script setup>
import { onMounted } from 'vue';
import { vehicleStore } from '../../store/vehicleStore.js'
import ScrollToTop from '../../components/ScrollToTop.vue';
import ScrollToBottom from '../../components/ScrollToBottom.vue';


const vehiclesData = vehicleStore();

onMounted(()=>{
  
  vehiclesData.allVehicles();

})

</script>

<style scoped>

</style>