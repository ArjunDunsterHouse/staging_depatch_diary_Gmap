<template>
    <b-container fluid class="screenLayoutsWidth">
    <div>
        <br>
            <b-row style="margin-top:55px;">
                <b-col cols="4">
                    <router-link to="/vehicle" class="btn btn-primary"><span class="arrow_styles material-icons">arrow_back</span></router-link>
                </b-col>
                <b-col cols="3" class="headingAdjust">
                    <h1 class="text-center"> Vehicle Update</h1>
                </b-col>
                <b-col cols="5"></b-col>
            </b-row>
    </div>
        <hr>
    <b-form-row class="editVehicleForm">
        <FormKit
        type="form"
        @submit="updateVehicle"
        >
            <b-row>
                <b-col cols="2"></b-col>
                <b-col cols="4">
                    <FormKit
                        type="text"
                        name="van-number"
                        label="Van Number"
                        help="Van number should have a prefix of VAN,Ex: if the shipping agent service code is VAN123RUN1,the Van number must be VAN123"
                        validation="required|alphanumeric|length:5,10"
                        placeholder="VANXXX"
                        v-model="editVehicle.vehicleForm1.van_number"
                    />
                    <FormKit
                        type="date"
                        name="start-date"
                        label="Start Date"
                        placeholder="dd-mm-yyyy"
                        help="Date when van came into service"
                        v-model="editVehicle.vehicleForm1.start_date"
                    />
                    <FormKit
                        type="select"
                        name="branch-name"
                        label="Branch Name"
                        validation="required"
                        placeholder="Bedford"
                        v-model="editVehicle.vehicleForm1.branch_id"
                        help="Which branch is this van has to serve"
                    >
                       <option id="searchDropdown" v-for="(branch,index) in editVehicle.branches" :key="index" :value="branch.branch_id">
                                    {{branch.branch_location}}
                        </option>
                        
                    </FormKit>
                    <FormKit
                        type="text"
                        name="target-amount"
                        label="Target Amount"
                        placeholder="10000.00"
                        validation="required|length:3,10"
                        v-model="editVehicle.vehicleForm1.target_amount"
                        help="Daily target amount for delivery"
                    />
                    <FormKit
                        type="text"
                        name="registration-number"
                        label="Registration Number"
                        placeholder="AB12 CDE"
                        validation="required|matches:/^[A-Z]{2}[0-9]{2}\s[A-Z]{3}$/"
                        help="Registration number of the vehicle. Remember to add space between"
                        v-model="editVehicle.vehicleForm1.registration_number"
                    />
                    <FormKit
                        type="number"
                        name="display-order"
                        label="Display Order"
                        validation="required"
                        v-model="editVehicle.vehicleForm1.display_order"
                    />
                </b-col>
                
                <b-col cols="4">
                    <FormKit
                        type="text"
                        name="shipping-agent-service-code"
                        label="Shipping Agent Service Code"
                        v-model="editVehicle.vehicleForm1.shipping_agent_service_code"
                        help="Van Number-Nav vehicle code"
                        placeholder="VAN123"
                        validation="required|alphanumeric|length:5,12"
                    />
                    <FormKit
                        type="date"
                        name="end-date"
                        label="End Date"
                        placeholder="dd/mm/yyyy"
                        help="Date when will van went out of service"
                        v-model="editVehicle.vehicleForm1.end_date"
                    />
                    <FormKit
                        type="text"
                        name="vehicle-type"
                        label="Vehicle Type"
                        placeholder="7.5T Flat Bed"
                        validation="required|length:0,35"
                        v-model="editVehicle.vehicleForm1.vehicle_type"
                        help="What type of vehicle is it"
                    />
                    <FormKit
                        type="number"
                        name="delivery-capacity"
                        label="Delivery Capacity"
                        placeholder="1000"
                        validation="required"
                        v-model="editVehicle.vehicleForm1.delivery_capacity"
                        help="Delivery capacity in kg"
                    
                    />
                    <FormKit
                        type="select"
                        name="vehicle-status"
                        label="Vehicle Status"
                        validation="required"
                        v-model="editVehicle.vehicleForm1.vehicle_status"
                        @change="VehicleStatusCheck"
                    >
                        <option id="searchDropdown" v-for="(status,index) in editVehicle.vehiclestatus" :key="index" :value="index"> 
                            {{status.name}}
                        </option>
                    </FormKit>
                
                    
                </b-col>
                <b-col cols="2"></b-col>
            </b-row>


            
        
        </FormKit>


                
                    <FormKit
                        type="button"
                        @click="editVehicle.deleteVehicle(id)"
                    >Delete
                    </FormKit>
                
            
    </b-form-row>
</b-container>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { EditVehicleStore } from '../../store/EditVehicleStore.js'
import  Swal from 'sweetalert2';
import { VueCookieNext } from 'vue-cookie-next';

  const editVehicle = EditVehicleStore();


if(editVehicle.getbranches_fix==true)
{
    editVehicle.setBranches();
} 
   

  const route = useRoute();

  let id = route.query.id

  const updateVehicle=()=>{
    editVehicle.updateVehicleDetails(id)
  }


  const VehicleStatusCheck=(e)=>{

    //previous data=0 and selected data = 1
    editVehicle.vehicleStatusEndDateCheck();


  }

  onMounted(()=>{

        editVehicle.InitializeEditVehicle(id);
    
    });

</script>