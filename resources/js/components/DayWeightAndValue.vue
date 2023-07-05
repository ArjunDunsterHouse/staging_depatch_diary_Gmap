<template>

      <div class="outerdivWeightAndValue">
            
                  <div id="weightAndValue" class="button-style" style="background-color:#5D2296;border:none">Total Weight and Value In a day</div>
                  <br/>

                  <div class="totalWandV">
                        <b-row class="display-table-inline">
                              <b-col cols="1" v-for="(data,index) in weightAndValue" :key="index">
                              
                                    <div class="inlineLayout">
                                          <!-- <div class="tableHeading">{{ days[i] }} - {{ index }}</div>
                                                <span v-show="false">{{i++}}</span> -->
                                          <!-- <table class="table tablesRow table-bordered" style="text-align:center">
                                             -->
                                          <table class="tablesRow" style="text-align:center">
                                                
                                                <thead>
                                                      <div class="tableHeading">{{ days[i] }} - {{ index }}</div>
                                                      <span v-show="false">{{i++}}</span>
                                                      <tr>
                                                            <th scope="col">Total Value</th>
                                                            <th scope="col">Total Weight</th>
                                                            
                                                      </tr>
                                                </thead>
                                                      <tbody>
                                                
                                                            <tr>
                                                                  <!-- Math.floor(value*100)/100 
                                                                  to see decimal up to 2 places ===>*100)/100 -->

                                                                  <td>Post,coll: £{{Math.floor(data.valuePostCollection*100)/100}}</td>
                                                                  <td>Post,coll: {{Math.floor(data.weightPostCollection*100)/100}} Kg</td>
                                                                  
                                                            </tr>
                                                            <tr>
                                                                <td>Courier: £{{Math.floor(data.valueCourier*100)/100}}</td>
                                                                  <td>Courier: {{Math.floor(data.weightCourier*100)/100}} Kg</td> 

                                                                 
                                                                  
                                                            </tr>
                                                            <tr>
                                                                  <td>Total: £{{Math.floor(data.value*100)/100}}</td>
                                                                  <td>Total: {{Math.floor(data.weight*100)/100}} Kg</td>

                                                           
                                                                  
                                                            </tr>
                                          
                                                
                                                      </tbody>
                                          </table>
                                    </div>
                              </b-col>
                              <span v-show="false">{{i=0}}</span>
                        </b-row>
                  </div>
      </div>
</template>
 
<script setup>

const props = defineProps({
      weightAndValue: Object,
});

let i=0;
const days = [
                  'Mon',
                  'Tue',
                  'Wed',
                  'Thu',
                  'Fri',
                  'Sat',
                  'Sun'
            ]
      
</script>
 
<style scoped>

.col-1{
      width: 12.25%; 
 }
 
 .tableHeading{
      background-color:#1E293B;
      width:158%;
      text-align: center;
      color:azure;
      padding:10px;
      margin-left:26%;

 }
 .display-table-inline{
      width: 254%;
      
 }
 .tablesRow{
      width:104%;
      border: none;
      font-size: 12px;
      margin-left: 11px;


}

@media(max-width: 1880px){

      .display-table-inline{
            width: 213%;
            font-size: 10px;
      
      }

      .tablesRow{
            font-size: 11px;
            margin-left: -20px;
      }


} 
</style>