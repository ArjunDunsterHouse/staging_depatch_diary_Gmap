<template>
    <div class="accordion" role="tablist">
        
        <b-card no-body class="mb-1">
            <b-button class="button-style" block v-b-toggle.postAccordian style="background-color:#5D2296;border:none">
                <strong class="vanDetails"> 
                    
                    Post -  Value: £ {{Math.floor(props.post.postValueBooked*100)/100}} | Weight: {{Math.floor(props.post.postBookedWeight*100)/100}} Kg
            
                </strong>
                <strong class="vanDetails2"> 
                     Post -  Value: £ {{Math.floor(props.post.postValueBooked*100)/100}} | Weight: {{Math.floor(props.post.postBookedWeight*100)/100}} Kg
                </strong>
            </b-button>
            
            <b-container fluid style="margin-left:-14px;">
                <b-collapse id="postAccordian" accordion="postAccordian" role="tabpanel">
                    <b-card-body class="Tables-card-body">
                        
                        <b-row class="display-table-inline">
                            <b-col cols="1">
                            <post-table
                            :order="props.post.Monday"
                            week-day="Monday">
                            </post-table>
                        </b-col>
                        <b-col cols="1">
                            <post-table
                            :order="props.post.Tuesday"
                            week-day="Tuesday">
                            </post-table>
                        </b-col>
                        <b-col cols="1">
                            <post-table
                            :order="props.post.Wednesday"
                            week-day="Wednesday">
                            </post-table>
                        </b-col>
                        <b-col cols="1">
                            <post-table
                            :order="props.post.Thursday"
                            week-day="Thursday">
                            </post-table>
                        </b-col>
                        <b-col cols="1">
                            <post-table
                            :order="props.post.Friday"
                            week-day="Friday">
                            </post-table>
                        </b-col>
                        <b-col cols="1">
                           <post-table
                            :order="props.post.Saturday"
                            week-day="Saturday">
                            </post-table>
                        </b-col>

                        <b-col cols="1">
                            <post-table
                            :order="props.post.Sunday"
                            week-day="Sunday">
                            </post-table>
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
    import postTable from './nestedComponents/table.vue';

    const searchSelectedData = search();
    const props = defineProps({
        post: Object,
    
    });
    // console.log("post vue file");
    // console.log(props.post);
</script>