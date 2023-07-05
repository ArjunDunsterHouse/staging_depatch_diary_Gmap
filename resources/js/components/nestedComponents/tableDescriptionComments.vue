<template> 
      <div class="comments-row">
            <b-row>
                  <b-col cols="12">
                  <FormKit
                  type="textarea"
                  label="Comments"
                  rows="1"
                  v-model="props.order.comments"
                  @input="Comments.getComments(props.order.comments,props.order.date,props.vehicleNo)"
                  placeholder="type here"
                  />
                  </b-col>
            </b-row>
      </div>
      <!-- <div class="AllRunsWtValTotal">
            <b-row style="background-color:#B0C4DE;padding:5px;border-radius:2px;">
                  <b-col cols="6">
                        <strong>Total Value: £ {{props.AllRunsWtvalPerDay.totalDayValue}}</strong>
                  </b-col>
                  <b-col cols="6">
                        <strong>Total Wt.: {{props.AllRunsWtvalPerDay.totalDayWeight}}Kg</strong>
                  </b-col>
                
                  
            </b-row>
             <b-row style="background-color:#B0C4DE;padding:2px;border-radius:2px;">
                  <b-col cols="6">
                        <strong>Total Time:  {{props.AllRunsWtvalPerDay.totalTravelTime}}</strong>
                  </b-col>
                  <b-col cols="6">
                        <strong>Total Mileage: {{props.AllRunsWtvalPerDay.totalJourneyDistance}} mi</strong>
                  </b-col>
                
                  
            </b-row>
      </div> -->
      

 
</template>
 
<script setup>
      import { tabledesc } from '../../store/tableStore.js'
      
      const Comments = tabledesc();

      const props = defineProps({
        order: Object,
        vehicleNo:String,
      })
 
</script>
 
<style scoped>
 
 .comments-row{
      margin-left:30px;
 }

 .AllRunsWtValTotal{

  padding-left:35px;
  /* color:#5D2296; */
  text-align: center;
  border-left: 1px solid #fff;
  font-size: 12.1px;
 }



 @media(max-width: 1880px) {

.AllRunsWtValTotal{

      font-size:10px;
      width: 88%;
}

     
}
 
</style>