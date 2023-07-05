<template>



  <div class="inlineLayout">
    <!-- {{props.order.date}} -->
  
      <div class="tableHeading">{{props.weekDay}}-{{props.order.date}}</div>
        <table class="table tablesRow table-bordered">
              <thead>
                <tr>
                  <th scope="col">Order no.</th>
                  <th scope="col">Name</th>
                  <th scope="col">Postcode</th>
                  <th scope="col">Area</th>
                  <th scope="col">Items</th>
                  <th scope="col">Wt.</th>
                  <th scope="col">£</th>
                  <th scope="col">Type</th>
                </tr>
              </thead>
              <tbody v-for="(value,index) in props.order" :key="value">
                <!-- && value['dispatch_requested_date']!=value['promised_delivery_date'] -->
                <tr v-if="index!='value_booked' && index!='to_book' && index!='booked_weight' 
                  && index!='remaining_weight' && index!='comments' && index!='date'  
                  && index!='bookable_status' && index!='journeyDistance' && index!='travelTime'
                  && index!='display_order_position' && index!='orginalSequence'"
              :style="[{background:
                      (value['balance_amount'] > 0.00 && value['delivery_confirmed'] == 1)?'#FFC671':                                        //no paid full-amber
                      (value['balance_amount'] <= 0.00 && value['delivery_confirmed'] == 1)?'#D7F6C8':                                       //confirmed order - green
                      (value['delivery_confirmed'] == 0 && value['balance_amount'] >= 0.00 &&      
                      value['dispatch_requested_date'] != '1970-01-01 00:00:00' && value['dispatch_requested_date'] != '1753-01-01 00:00:00' //planning order -pink
                      )?'#ffb3ff':'#F9F3E0'                              //cream if nothing
                    }]">
            <td>{{value["order_no"]}}
                      <span v-if="(value['ship_status'] == 'Shipped')"><br>
                      <strong>Shipped</strong></span>
                      <span v-else><br/></span>
                      <span v-if="(value['ship_status'] == 'Part Shipped')"><br>
                      <strong>Pt Shipped</strong></span>
                      <span v-else><br/></span>
                  </td>
                  <td>{{value["ship_to_name"]}}</td>
                  <td>{{value["ship_to_post_code"]}}</td>
                  <td>{{value["ship_to_city"]}}</td>
                  <td>{{value["type_of_supply_code"]}}</td>
                  <td>{{value["order_weight"]}} Kg</td>
                  <td>£{{value["order_amount"]}} 
                      <span v-if="(value['balance_amount'] <=0.00 && value['delivery_confirmed']==0)">
                      <strong>Paid</strong>
                      </span>
                      <span v-else><br/></span>
                      <span v-if="(value['balance_amount'] <=0.00 && value['delivery_confirmed']==1)">
                      <strong>Paid</strong>
                      </span> 
                      <span v-else><br/></span>
                  </td>
                  <td>{{value["shipment_type"]}}</td>
  
                </tr>
            <!-- <span v-if="index!='value_booked' && index!='to_book' && index!='booked_weight' && index!='remaining_weight'"> 
              (value['balance_amount'] >= 0.00 && value['delivery_confirmed'] == 0)?'#F9F3E0':''
                    || -->
              <!-- <tr v-if="index!='value_booked' && index!='to_book' && index!='booked_weight' 
                  && index!='remaining_weight' && index!='comments' && index!='date'  && index!='bookable_status'"
              :style="[{background:
                      (value['balance_amount'] > 0.00 && value['delivery_confirmed'] == 1)?'#FFC671':                                        //no paid full-amber
                      (value['balance_amount'] <= 0.00 && value['delivery_confirmed'] == 1)?'#D7F6C8':                                       //confirmed order - green
                      (value['delivery_confirmed'] == 0 && value['balance_amount'] >= 0.00 &&      
                      value['dispatch_requested_date'] != '1970-01-01 00:00:00' && value['dispatch_requested_date'] != '1753-01-01 00:00:00' //planning order -pink
                      && value['dispatch_requested_date']!=value['promised_delivery_date'])?'#ffb3ff':'#F9F3E0'                              //cream if nothing
                    }]">
                  
                  <td>{{value["order_no"]}}
                  <br>
                      <span v-if="(value['ship_status'] == 'Shipped')">
                      <strong>Shipped</strong></span>
                      <span v-else><br/></span>
                      <span v-if="(value['ship_status'] == 'Part Shipped')">
                      <strong>Pt Shipped</strong></span>
                      <span v-else><br/></span>
                  </td>
                  <td>{{value["ship_to_name"]}}</td>
                  <td>{{value["ship_to_post_code"]}}</td>
                  <td>{{value["ship_to_city"]}}</td>
                  <td>{{value["type_of_supply_code"]}}</td>
                  <td>{{value["order_weight"]}} Kg</td>
                  <td>£{{value["order_amount"]}} <br>
                      <span v-if="(value['balance_amount'] <=0.00 && value['delivery_confirmed']==0)">
                      <strong>Paid</strong>
                      </span>
                      <span v-if="(value['balance_amount'] <=0.00 && value['delivery_confirmed']==1)">
                      <strong>Paid</strong>
                      </span> 
                  </td>
                  <td>{{value["shipment_type"]}}</td>
                  
                  
                  
                </tr> -->
                </tbody>
        </table>
  </div>
  </template>
  
  <script setup>
  
  import moment from "moment";
    const props = defineProps({
      order: Object,
      weekDay:String,
      vehicleNo:String
  });
  
  </script>