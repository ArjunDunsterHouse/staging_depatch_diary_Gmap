<template>
    <b-container fluid class="screenLayoutsWidth2">
        <div>
          <br/>
          <b-row style="margin-top:55px;">
                <b-col cols="4">
                    <router-link to="/branch" class="btn btn-primary"><span class="arrow_styles material-icons">arrow_back</span></router-link>
                </b-col>
                <b-col cols="3"  class="headingAdjust">
                    <h1 class="text-center"> Add Branch</h1>
                </b-col>
                <b-col cols="5"></b-col>
            </b-row>
        </div>
        <hr>    
        <b-form-row class="createUserForm">

            <FormKit
            type="form"
            @submit ="newBranch"
            >
                <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                       
                        <FormKit
                            type="text"
                            name="branch-name"
                            label="Branch Name"
                            help="Which town/city this branch is located "
                            validation="required|length:0,35"
                            placeholder="Enter Branch Name"
                            v-model="createBranch.branchForm.branch_location"
                        />
                    </b-col>

                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="branch-code"
                            label="Branch Code"
                            help="Branch code as per NAV"
                            validation="required|length:0,35"
                            placeholder="Enter Branch Code"
                            v-model="createBranch.branchForm.branch_code"
                        />
                    </b-col>
                    <b-col cols="2"></b-col>
                </b-row>

                <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="shipping-agent-code"
                            label="Shipping Agent Code"
                            help="Shipping Agent code to be sent to NAV"
                            validation="required|length:0,35"
                            placeholder="Enter Shipping Agent Code"
                            v-model="createBranch.branchForm.shipping_agent_code"
                        />
                    </b-col>

                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="branch-postcode"
                            label="Branch Post code"
                            help="Post code of this branch"
                            :validation="[['required'], ['matches', /([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})$/]]"
                            placeholder="Enter Branch Post code"
                            v-model="createBranch.branchForm.branch_postcode"
                        />
                    </b-col>
                    <b-col cols="2"></b-col>
                </b-row>

                <b-row>
                    <b-col cols="2"></b-col>
                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="latitude"
                            label="latitude"
                            help="latitude for this branch"
                            validation="required|number"
                            placeholder="Enter latitude"
                            v-model="createBranch.branchForm.latitude"
                        />
                    </b-col>

                    <b-col cols="4">
                        <FormKit
                            type="text"
                            name="longitude"
                            label="longitude"
                            help="longitude for this branch"
                            validation="required|number"
                            placeholder="Enter longitude"
                            v-model="createBranch.branchForm.longitude"
                        />
                    </b-col>
                    <b-col cols="2"></b-col>
                </b-row>
            </FormKit>
        </b-form-row>

    
    </b-container>
</template>


<script setup>

import { createNewBranch } from '../../store/createBranch.js'
    //import { onMounted } from 'vue'; 

    const createBranch = createNewBranch();

    const newBranch = ()=>{
        createBranch.createNewBranch(createBranch.branchForm);
    }
           

</script>