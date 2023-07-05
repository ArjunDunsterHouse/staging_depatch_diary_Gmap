<template>
    <div class="accordion" role="tablist">
        
        
        <!-- <b-card no-body class="mb-1" v-for="(orders,weekDay) in searchSelectedData.collectionsData" :key="weekDay"> -->
        <b-card no-body class="mb-1">
            <b-button class="button-style" block v-b-toggle.collectionAccordian style="background-color:#5D2296;border:none">
            <strong class="vanDetails"> 
                Collections -  Value: £ {{Math.floor(props.collections.collectionValueBooked*100)/100}} | Weight: {{Math.floor(props.collections.collectionBookedWeight*100)/100}} Kg
            </strong>
            <strong class="vanDetails2"> 
                 Collections -  Value: £ {{Math.floor(props.collections.collectionValueBooked*100)/100}} | Weight: {{Math.floor(props.collections.collectionBookedWeight*100)/100}} Kg
            </strong>
              
        </b-button>
            <!-- Day:{{weekDay}}
            Orders:{{orders}} -->
                
            <b-container fluid style="margin-left:-14px;">
                
                <b-collapse id="collectionAccordian" accordion="collectionAccordian" role="tabpanel">
                    <b-card-body class="Tables-card-body">
                    <b-row class="display-table-inline">
                        <b-col cols="1">
                            <collection-table
                            :order="props.collections.Monday"
                            week-day="Monday">
                            </collection-table>
                        </b-col>
                        <b-col cols="1">
                            <collection-table
                            :order="props.collections.Tuesday"
                            week-day="Tuesday">
                            </collection-table>
                        </b-col>
                        <b-col cols="1">
                            <collection-table
                            :order="props.collections.Wednesday"
                            week-day="Wednesday">
                            </collection-table>
                        </b-col>
                        <b-col cols="1">
                            <collection-table
                            :order="props.collections.Thursday"
                            week-day="Thursday">
                            </collection-table>
                        </b-col>
                        <b-col cols="1">
                            <collection-table
                            :order="props.collections.Friday"
                            week-day="Friday">
                            </collection-table>
                        </b-col>
                        <b-col cols="1">
                             <collection-table
                            :order="props.collections.Saturday"
                            week-day="Saturday">
                            </collection-table>
                        </b-col>

                        <b-col cols="1">
                             <collection-table
                            :order="props.collections.Sunday"
                            week-day="Sunday">
                            </collection-table>
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
    import collectionTable from './nestedComponents/table.vue';

    const searchSelectedData = search();
    const props = defineProps({
        collections: Object,
    
    });

    // console.log("collection vue file");
    // console.log(props.collections);
</script>