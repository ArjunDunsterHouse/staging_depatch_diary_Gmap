<template>
    <b-container fluid class="screenLayoutsWidth">
    <div>
        <br>
            <b-row style="margin-top:55px;">
                <b-col cols="4">
                    <router-link  to="/vehicle" class="btn btn-primary"><span class="arrow_styles material-icons">arrow_back</span> </router-link>
                </b-col>
                <b-col cols="3" class="headingAdjust">
                    <h1 class="text-center">Add Vehicle</h1>
                </b-col>
                <b-col cols="5"></b-col>
            </b-row>
    </div>
    <hr>
    <b-form-row class="createVehicleForm">
        <FormKit
        type="form"
        @submit="AddVehicle"
        @change="createVehicle.checkDublicateVan(createVehicle.vehicleForm.shipping_agent_service_code)"
        >
                <b-row>
                     <b-col cols="2"></b-col>
                        <b-col cols="4">
                        
                        
                            <FormKit
                                type="text"
                                name="van-number"
                                label="Van Number"
                                :help="createVehicle.helpText"
                                
                                validation="required|alphanumeric|length:5,10"
                                placeholder="VANXXX"
                                v-model="createVehicle.vehicleForm.van_number"                               

                            />
                            <FormKit
                                type="date"
                                name="start-date"
                                label="Start Date"
                                placeholder="dd-mm-yyyy"
                                v-model="createVehicle.vehicleForm.start_date"
                                help="Date when van came into service"
                               
                            />
                            <FormKit
                                type="select"
                                name="branch-name"
                                label="Branch Name"
                                validation="required"
                                placeholder="Bedford"
                                v-model="createVehicle.vehicleForm.branch_id"
                                help="Which branch is this van has to serve"
                            >
                                <option id="searchDropdown" v-for="(branch,index) in createVehicle.branches" :key="index" :value="branch.branch_id">
                                    {{branch.branch_location}}
                                </option>
                            </FormKit>
                            <FormKit
                                type="text"
                                name="target-amount"
                                label="Target Amount"
                                placeholder="10000.00"
                                validation="required|length:3,10"
                                v-model="createVehicle.vehicleForm.target_amount"
                                help="Daily target amount for delivery"
                            />
                            <FormKit
                                name="registration-number"
                                label="Registration Number"
                                placeholder="AB12 CDE"
                                validation="required|matches:/^[A-Z]{2}[0-9]{2}\s[A-Z]{3}$/"
                                v-model="createVehicle.vehicleForm.registration_number"
                                help="Registration number of the vehicle. Remember to add space between"
                            />
                        
                        </b-col>
                        <b-col cols="4">
                            <FormKit
                                type="text"
                                name="shipping-agent-service-code"
                                label="Shipping Agent Service Code"
                                placeholder="VAN123"
                                validation="required|alphanumeric|length:5,12"
                                v-model="createVehicle.vehicleForm.shipping_agent_service_code"
                                help="Van Number-Nav vehicle code"
                               
                            />
                            <FormKit
                                type="date"
                                name="end-date"
                                label="End Date"
                                placeholder="dd/mm/yyyy"
                                v-model="createVehicle.vehicleForm.end_date"
                                help="Date when will van went out of service"
                            />
                            <FormKit
                                type="text"
                                name="vehicle-type"
                                label="Vehicle Type"
                                placeholder="7.5T Flat Bed"
                                validation="required|length:0,35"
                                v-model="createVehicle.vehicleForm.vehicle_type"
                                help="What type of vehicle is it (Ex:3.5T Flat Bed,18T Curtain Side)"
                            />
                            <FormKit
                                type="number"
                                name="delivery-capacity"
                                label="Delivery Capacity"
                                placeholder="1000"
                                validation="required|length:0,5"
                                v-model="createVehicle.vehicleForm.delivery_capacity"
                                help="Delivery capacity in kg"
                            />
                            <FormKit
                                type="select"
                                name="vehicle-status"
                                label="Vehicle Status"
                                validation="required"
                                v-model="createVehicle.vehicleForm.vehicle_status"
                            >
                                <option id="searchDropdown" v-for="(status,index) in createVehicle.vehiclestatus" :key="index" :value="index"> 
                                    {{status.name}}
                                </option>
                            </FormKit>
                        
                            
                        </b-col>
                        <b-col cols="2"></b-col>
                    
                </b-row>
        </FormKit>     
    </b-form-row>
    </b-container>
    
</template>

<script setup>

    import { createNewVehicle } from '../../store/createVehicle.js'
    import  Swal from 'sweetalert2';

    const createVehicle = createNewVehicle();
    
    if(createVehicle.getbranches_fix==true)
    {
        createVehicle.setBranches();
    }
    const AddVehicle = () => {

        if(createVehicle.getDublicateStatus == "")
        {
            //if new record and not dublicate then only submit

            createVehicle.createVehicleRecord()
        }
        else{
            
            Swal.fire({
                        title: "Record not saved",
                        text: "Duplicate Van Record",
                        icon: "warning"
                    })  
        }
     
    }

</script>
