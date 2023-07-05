<template>
    <div class="accordion" role="tablist">
        

        <b-card no-body class="mb-1">
            <b-button class="button-style" block v-b-toggle.courierAccordian style="background-color:#5D2296;border:none">
                <strong class="vanDetails"> 
                    Courier - Value: £ {{Math.floor(props.courier.courierValueBooked*100)/100}} | Weight: {{Math.floor(props.courier.courierBookedWeight*100)/100}} Kg
            
                </strong>
                <strong class="vanDetails2"> 
                   Courier - Value: £ {{Math.floor(props.courier.courierValueBooked*100)/100}} | Weight: {{Math.floor(props.courier.courierBookedWeight*100)/100}} Kg
            
                </strong>
            </b-button>
            
            <b-container fluid style="margin-left:-14px;">
                <b-collapse id="courierAccordian" :visible ="searchSelectedData.courierGosbertonStatus==true" accordion="courierAccordian" role="tabpanel">
                    <b-card-body class="Tables-card-body">
                        
                        <b-row class="display-table-inline">
                            <b-col cols="1">
                            <courier-table
                            :order="props.courier.Monday"
                            week-day="Monday">
                            </courier-table>
                        </b-col>
                        <b-col cols="1">
                            <courier-table
                            :order="props.courier.Tuesday"
                            week-day="Tuesday">
                            </courier-table>
                        </b-col>
                        <b-col cols="1">
                            <courier-table
                            :order="props.courier.Wednesday"
                            week-day="Wednesday">
                            </courier-table>
                        </b-col>
                        <b-col cols="1">
                            <courier-table
                            :order="props.courier.Thursday"
                            week-day="Thursday">
                            </courier-table>
                        </b-col>
                        <b-col cols="1">
                            <courier-table
                            :order="props.courier.Friday"
                            week-day="Friday">
                            </courier-table>
                        </b-col>
                        <b-col cols="1">
                        <courier-table
                            :order="props.courier.Saturday"
                            week-day="Saturday">
                            </courier-table>
                        </b-col>

                        <b-col cols="1">
                            <courier-table
                            :order="props.courier.Sunday"
                            week-day="Sunday">
                            </courier-table>
                        </b-col> 

                        </b-row>
                    </b-card-body>
                </b-collapse>
            </b-container>
        </b-card>
    </div>
</template>

<script setup>
    import { search } from '../store/searchData.js'
    import courierTable from './nestedComponents/table.vue';
    import { input } from '../store/input.js'
    import { VueCookieNext } from 'vue-cookie-next';
	const inputData = input();
    const searchSelectedData = search();
    const props = defineProps({
        courier: Object,
    
    });
    inputData.selectedBranch=VueCookieNext.getCookie('selected_branch');
    //console.log(inputData.selectedBranch);
    // console.log("courier vue file");
    // console.log(props.courier);
</script>