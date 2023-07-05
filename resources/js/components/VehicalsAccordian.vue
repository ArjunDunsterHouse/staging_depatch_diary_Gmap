<template>
 
  <div class="accordion accordiansDisplay" role="tablist">
  
   

   <!-- <span v-for="(van,vanNumber) in searchSelectedData.selectedData" :key="vanNumber">

      {{vanNumber}}--{{van.parent_collapse_status}}
    </span> -->
    
    <b-card no-body class="mb-1" v-for="(van,vanNumber) in searchSelectedData.selectedData" :key="vanNumber+'1'">
     

      <!-- {{vanNumber}}--{{van.parent_collapse_status}} -->
             <b-button 
                        :id="vanNumber"
                        class="button-style" v-b-toggle="vanNumber+'1'" :style="[{background:
                        van.vehicle_type=='3.5T Flat Bed'?'#E1E8FB':
                        van.vehicle_type=='3.5T Box Van'?'#F5E7F0':
                        van.vehicle_type=='3.5T Panel Van'?'#ECECEC':
                        van.vehicle_type=='7.5T Flat Bed'?'#FFFFCC':
                        van.vehicle_type=='18T Flat Bed'?'#F9DAA8':
                        van.vehicle_type=='18T Curtain Side'?'#ECECEC':''
                        
                        },{border:none}]"
                        @click="searchSelectedData.ParentCollapseHideOrShow(vanNumber)"      
              >
              
                <strong class="vanDetails" :style="[{color:
                        '#800000'
                }]"
                >
                  {{vanNumber+` | `}}
                  {{van.registration_number+` | `}} 
                  {{van.delivery_capacity+` Kg | `}}
                  {{van.vehicle_type}}
                </strong> 
                <strong class="vanDetails2" :style="[{color:
                        '#800000'
                        }]">
                   {{vanNumber+` | `}}
                  {{van.registration_number+` | `}}
                  {{van.delivery_capacity+` Kg | `}}
                  {{van.vehicle_type}}  
                </strong>
                
        
            </b-button>

            <b-container fluid >
             <!-- OUTER COLLAPSE -->


             <!--{{van.parent_collapse_status}}-->

             <!-- :visible="van.parent_collapse_status===1" -->

              <b-collapse :id="vanNumber+'1'" :class="van.parent_collapse_status===1 ? 'show' : ''"  accordion="dd" role="tabpanel" >

                  <span  v-for="(orders,run) in van" :key="run">
                    
                      <span v-if ="run!=vanNumber && run!='delivery_capacity' && run!='target_amount' && run !='parent_collapse_status' && run!='vehicle_type' && run!='registration_number' && run!='totalDayWtAndValue'">
                        
                        <!-- above v-if is to exclude parent level properties. update this when ever adding parent level property.  -->
                        
                        <div id="accordion">

                            <div class="card">
                              

                                <button   
                                        :style="[{background:
                                          van.vehicle_type=='3.5T Flat Bed'?'#E1E8FB':
                                          van.vehicle_type=='3.5T Box Van'?'#F5E7F0':
                                          van.vehicle_type=='3.5T Panel Van'?'#ECECEC':
                                          van.vehicle_type=='7.5T Flat Bed'?'#FFFFCC':
                                          van.vehicle_type=='18T Flat Bed'?'#F9DAA8':
                                          van.vehicle_type=='18T Curtain Side'?'#ECECEC':''
                                          },{'border-style':'ridge'
                                          },
                                          {height:'35px'},
                                          
                                          {color:'#5D2296'},
                                          {'border-radius':'20px'}
                                        ]" 
                                        

                                        :id="run.includes('RUN')? '#'+run : '#'+run+'RUN'"
                                        class="VehicleHeading" 
                                        data-bs-toggle="collapse" 
                                        :data-bs-target="run.includes('RUN')? '#'+run : '#'+run+'RUN'" 
                                        aria-bs-expanded="false" 
                                        :aria-bs-controls="run"
                                        @click="searchSelectedData.hideShowVanRun(run)"
                                  >
                                      
                                        <strong class="vanDetails">
                                              {{run}} - Value: £ {{orders.totalValue}} | Weight:{{orders.totalWeight+` kg`}} 
                                        </strong>

                                        <strong class="vanDetails2">
                                            {{run}} - Value: £ {{orders.totalValue}} | Weight:{{orders.totalWeight+` kg`}} 
                                        </strong>

                                  </button>
                                  <!--{{"1-show ,0-hide::"}}
                                  {{orders.hideOrShowVehicle }}-->
                                  <div 
                                      :id="run.includes('RUN')? run : run+'RUN'" 
                                      class="collapse" 
                                      :class="orders.hideOrShowVehicle === 1 ? 'show' : ''"
                                      :aria-bs-labelledby="run" 
                                      data-bs-parent="#accordion"
                                  >
                                    <div class="card-body">

                                      <b-row class="display-table-inline" >
                                
                                          <b-col cols="1">
                                            
                                                <view-table
                                                    :order="orders.Monday"
                                                    week-day="Monday"
                                                    :vehicle-no="vanNumber"
                                                >
                                                </view-table> 

                                          </b-col>
                                            
                                          <b-col cols="1">
                        
                                            <view-table
                                                      :order="orders.Tuesday"
                                                      week-day="Tuesday"
                                                      :vehicle-no="vanNumber"
                                            >
                                            </view-table>
                                              
                                          </b-col>
                                            
                                          <b-col cols="1">
                        
                                            <view-table
                                                    :order="orders.Wednesday"
                                                    week-day="Wednesday"
                                                    :vehicle-no="vanNumber"
                                            >
                                            </view-table> 

                                          </b-col>
                                            
                                          <b-col cols="1">
                          
                                            <view-table
                                                      :order="orders.Thursday"
                                                      week-day="Thursday"
                                                      :vehicle-no="vanNumber"
                                            >
                                            </view-table> 

                                          </b-col>
                                            
                                          
                                        
                                          <b-col cols="1">
                          
                                            <view-table
                                                      :order="orders.Friday"
                                                      week-day="Friday"
                                                      :vehicle-no="vanNumber"
                                            >
                                            </view-table> 

                                          </b-col>
                                            
                                          <b-col cols="1">
                        
                                            <view-table
                                                    :order="orders.Saturday"
                                                    week-day="Saturday"
                                                    :vehicle-no="vanNumber"
                                            >
                                            </view-table>
                                          
                                          </b-col>
                                          <b-col cols="1">
                                          <view-table
                                                    :order="orders.Sunday"
                                                    week-day="Sunday"
                                                    :vehicle-no="vanNumber"
                                            >
                                            </view-table>
                                          </b-col>
                                          <b-col cols="5"></b-col>
                                      </b-row>
                                      <br/>
                                      <b-row id="belowLayoutofTables">
                                          <b-col cols="1" class="description">
                                            <data-description
                                            :order="van[run].Monday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"
                                            >
                                            </data-description>
                                          </b-col>
                                          <b-col cols="1" class="description">
                                            <data-description
                                            :order="van[run].Tuesday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"
                                            >
                                            </data-description>
                                          </b-col>
                                          <b-col cols="1" class="description">
                                            <data-description
                                            :order="van[run].Wednesday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"

                                          >
                                          </data-description>
                                          </b-col>
                                          <b-col cols="1" class="description">
                                            <data-description
                                            :order="van[run].Thursday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"
                                            >
                                            </data-description>
                                          </b-col>
                                          <b-col cols="1" class="description">
                                            <data-description
                                            :order="van[run].Friday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"
                                            >
                                            </data-description>
                                          </b-col>
                                          <b-col cols="1" class="description">
                                          <data-description
                                            :order="van[run].Saturday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"

                                          >

                                          </data-description>
                                          </b-col>

                                          <b-col cols="1" class="description">
                                          <data-description
                                            :order="van[run].Sunday"
                                            :vehicle-no="run"
                                            :targetAmount="van.target_amount"
                                            :deliveryCapacity="van.delivery_capacity"

                                          >
                                          </data-description>
                                          </b-col>
                                          <b-col cols="5"></b-col>
                                      </b-row>
                                      <br>
                                      <!--comments-->
                                      <b-row id="belowLayoutofTableComments">
                                        
                                        <b-col cols="1" class="tableComments">
                                          <table-comments
                                            :order="van[run].Monday"
                                            :vehicle-no="run"
                                            :AllRunsWtvalPerDay="van.totalDayWtAndValue.Monday"
                                            >
                                          </table-comments>
                                        </b-col>


                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Tuesday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Tuesday"
                                            >
                                            </table-comments>
                                          </b-col>
                                            
                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Wednesday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Wednesday"
                                              
                                            >
                                            </table-comments>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Thursday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Thursday"

                                            >
                                            </table-comments>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Friday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Friday"
                                              >
                                            </table-comments>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Saturday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Saturday"
                                              >
                                            </table-comments>
                                          </b-col>

                                          <b-col cols="1" class="tableComments">
                                            <table-comments
                                              :order="van[run].Saturday"
                                              :vehicle-no="run"

                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Saturday"
                                              >
                                            </table-comments>
                                          </b-col>
                                      </b-row>
                                    </div>
                                  </div>
                            </div>

                        </div>
                      </span>
                    
                  </span>

                <!--For all runs total weight and value-->
                <b-row id="belowLayoutofTableComments">
                                        
                                        <b-col cols="1" class="tableComments">
                                          <total-runs
                                            :AllRunsWtvalPerDay="van.totalDayWtAndValue.Monday"
                                            >
                                          </total-runs>
                                        </b-col>


                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Tuesday"
                                            >
                                            </total-runs>
                                          </b-col>
                                            
                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Wednesday"
                                              
                                            >
                                            </total-runs>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Thursday"

                                            >
                                            </total-runs>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Friday"
                                              >
                                            </total-runs>
                                          </b-col>
                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Saturday"
                                              >
                                            </total-runs>
                                          </b-col>

                                          <b-col cols="1" class="tableComments">
                                            <total-runs
                                              
                                              :AllRunsWtvalPerDay="van.totalDayWtAndValue.Saturday"
                                              >
                                            </total-runs>
                                          </b-col>
                                          </b-row>

              
              </b-collapse>
            
            </b-container>
     

    </b-card>

  </div>
 
  
 
</template>
 
<script setup>
  import dataDescription from './nestedComponents/tableDescriptionData.vue';
  import tableComments from './nestedComponents/tableDescriptionComments.vue';
  import totalRuns from './nestedComponents/tableTotalRunDetails.vue';
  import viewTable from './nestedComponents/table.vue';
  import searchData from '../components/SearchData.vue';
  import { search } from '../store/searchData.js'

 const searchSelectedData = search();
 
</script>
  
<style scoped>
.container-fluid{
  margin-left:-12px;
}
.description{
  width:11.719%;
}
.tableComments{
  width:14%
}

@media(max-width: 1880px){

    .tableComments{
    width:14.2%
  }
}
</style>