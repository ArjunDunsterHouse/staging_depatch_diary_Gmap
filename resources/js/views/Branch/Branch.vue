<template>

<br><br>
<b-container fluid class="screenLayoutsWidth">
  
      <router-link style="margin-top:55px;" to="/create-branch" class="btn btn-primary">Add Branch </router-link>
      <br><br>
  
  
      <div class="row">
          <div class="col-lg-12 mb-4">
              <div class="card">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h3 class="m-0 font-weight-bold" style="color:#5D2296;">Branch Details</h3>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                      <thead class="thead-light">
                        <tr>
                          <th>Branch Location</th>
                          <th>Branch Code</th>
                          <th>Shipping Agent Code</th>
                          <th>Branch Postcode</th>
                          <th>Latitude</th>
                          <th>Longitude</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        <tr v-for="(branch) in branch.branchDetails" :key="branch.branch_id">
                          <td>{{branch.branch_location}}</td>
                          <td>{{branch.branch_code}}</td>
                          <td>{{branch.shipping_agent_code}}</td>
                          <td>{{branch.branch_postcode}}</td>
                          <td>{{branch.latitude}}</td>
                          <td>{{branch.longitude}}</td>
                          
                          <td><router-link :to="{name:'edit-branch',query:{id:branch.branch_id}}" class="btn btn-sm btn-primary">Edit</router-link></td>
                        </tr>
                      
                      </tbody>
                    </table>
                  </div>
                  <div class="card-footer"></div>
                </div>
              </div>
            </div>
    </b-container>
  </template>
  
  
  
  
  <script setup>
      import { onMounted } from 'vue';

      import { listOfBranches } from '../../store/branchList.js'
      
      const branch = listOfBranches();
      
      onMounted(()=>{
        branch.allBranchDetails();
      });
  </script>