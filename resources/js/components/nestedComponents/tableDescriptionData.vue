<template>
  <div class="belowContentWrapper">
    <b-row class="rowsize">
    
      <!-- {{props.order}} -->
      <b-col cols="6" 
      :style="{color:[(props.order.value_booked <=(0.8*(props.targetAmount)))?'#ff0000':      //RED
                      ((props.order.value_booked > (0.8*(props.targetAmount))) 
                   && (props.order.value_booked < props.targetAmount))?'#FF8533':             //AMBER
                      (props.order.value_booked>props.targetAmount)?'#009933':'#a89595'       //GREEN || BLACK
              ]}">  
               
        Value Booked:£ {{props.order.value_booked}}
      </b-col>
      <b-col cols="6" 
      :style="{color:[(props.order.booked_weight <=(0.8*(props.deliveryCapacity)))?'':
                     (props.order.booked_weight > (0.8*(props.deliveryCapacity)) 
                  && (props.order.booked_weight < props.deliveryCapacity))?'#FF8533':
                     (props.order.booked_weight>props.deliveryCapacity)?'#ff0000':''

    
              ]}">
      
        Booked Weight:{{props.order.booked_weight}} Kg 
      </b-col>
    </b-row>
    <br/>
  
     <b-row class="rowsize">
      <b-col cols="6" 
      :style="{color:[(props.order.value_booked <=(0.8*(props.targetAmount)))?'#ff0000':
                      (props.order.value_booked > (0.8*(props.targetAmount)) 
                      && (props.order.value_booked < props.targetAmount))?'#FF8533':
                      (props.order.value_booked>props.targetAmount)?'#009933':'#a89595'
    
              ]}"> 
        To Book:£ {{props.order.to_book}}
      </b-col>
  
      <b-col cols="6" 
      :style="{color:[(props.order.booked_weight>props.deliveryCapacity)?'#ff0000':                          //RED
                      (props.order.booked_weight > (0.8*(props.deliveryCapacity)) 
                      && (props.order.booked_weight < props.deliveryCapacity))?'#FF8533':                    //AMBER
                      (props.order.booked_weight <=(0.8*(props.deliveryCapacity)))?'#000000':''       //BLACK
    
              ]}">
        Remaining Weight:{{props.order.remaining_weight}} Kg
      </b-col>
    </b-row>
    <br/>
     <b-row class="rowsize">
      <!-- :class="`${tableDescription.bookableBackgroundColor? 'notavailableBookable' : 'availableBookable'}`" 
      :style="[{color:tabledesc.bookableBackgroundColor?
                        'green':'red'}]"-->
      <b-col cols="6"
      
      >
      <div style="display:flex" >
        <!-- <FormKit
          v-model="tabledesc.isCheck"
          type="checkbox"
          name="bookable"
          @click='tableDescription.updateBookableValue($event,props.order.date,props.vehicleNo) '
        /> -->

        <!-- <b-button
              v-model="tabledesc.isCheck"
              type="button"
              name="bookable"
              @click='bookableCheck'
              :class="`${tabledesc.isCheck==true? 'availableBookable' : 'notavailableBookable'}`"
            
            >

            <span class="bookableClass" v-if="tabledesc.isCheck==true">
              Not Bookable
            </span>
            <span class="bookableClass" v-else>
              Bookable
            </span>

          </b-button> -->

          <b-button
              class="bookableClass"
              :id ="props.order.date+props.vehicleNo"
              :aria-label="`${props.order.bookable_status==1? 'Not Bookable' : 'Bookable'}`" 
              v-model="props.order.bookable_status"
              type="button"
              name="bookable"
              @click='bookableCheck'
              :class="`${props.order.bookable_status==1? 'notavailableBookable' : 'availableBookable'}`"           
           >

                <span class="bookableClass" v-if="props.order.bookable_status==1">
                  Not Bookable
                </span>
                <span class="bookableClass" v-else>
                  Bookable
                </span>

           </b-button>

              
            
          <!-- <span class="bookableClass" :class="`${tabledesc.isCheck==true? 'availableBookable' : 'notavailableBookable'}`">Bookable</span> -->
        </div>
      </b-col>
      <b-col cols="6">
        <b-button variant="outline-primary" @click="printTable">Print</b-button>
      </b-col>
    </b-row>
    <br/>
  

    

     <b-row class="rowsize" v-if="props.order.travelTime == 0 && props.order.journeyDistance == 0 && props.order.value_booked != 0.00 && props.order.booked_weight != 0.00">
        <b-col cols="12">

          <strong>
              <span style="color:red;padding-left: 18%;">Wrong API calculation in Database</span>
          </strong>
        </b-col>
      </b-row>


      <b-row class="rowsize" v-else-if="props.order.travelTime == 0 && props.order.journeyDistance == 0 && props.order.value_booked == 0.00 && props.order.booked_weight == 0.00">
        <b-col cols="12">
          <strong >
              &nbsp;
          </strong>
        </b-col>
      </b-row>

      <b-row class="rowsize" v-else>
        <b-col cols="6">
          <strong >
            Travel Time : {{props.order.travelTime}}
          </strong>
        </b-col>

        <b-col cols="6">
          <strong >
            Journey Distance: {{props.order.journeyDistance}} miles
          </strong>
        </b-col>
      </b-row>
  </div>
   
   
  </template>
   
  <script setup>
      import { tabledesc } from '../../store/tableStore.js'
      import { PrintTableData } from '../../store/printTable.js'
     // import {useLoading} from 'vue-loading-overlay'
      const tableDescription = tabledesc();
      const printData = PrintTableData();

     
      

      const props = defineProps({
        order: Object,
        vehicleNo:String,
        targetAmount:String,
        deliveryCapacity:Number
      })
      // /**0-not available-red -true*/
      // if(props.order.bookable_status==0){
      //     tabledesc.isCheck=true
      //     tabledesc.bookableBackgroundColor=!tabledesc.isCheck
      //   }
      //   /**1-available-green */
      //   if(props.order.bookable_status==1){
      //     tabledesc.isCheck=false
      //     tabledesc.bookableBackgroundColor=!tabledesc.isCheck
      //   }
        
        const printTable = ()=>{
          
          printData.printTable(props.order)
        }
        

        const bookableCheck = ()=>{
          
          tableDescription.updateBookableValue(props.order.date,props.vehicleNo)

          
                     // const $loading = useLoading()
                    //  const loader = $loading.show({
                       // Optional parameters
                     //color: '#5D2296',
                    // loader: 'dots',
                 //  });
                   // simulate AJAX
                  // setTimeout(() => {
                  //     loader.hide()
                  // }, 2000)
        }
       
//props.order.date
        // onMounted(()=>{
        //   if(props.order.bookable_status==0){
        //   tabledesc.isCheck=true
        //   tabledesc.bookableBackgroundColor=!tabledesc.isCheck
        // }
        // if(props.order.bookable_status==1){
        //   tabledesc.isCheck=false
        //   tabledesc.bookableBackgroundColor=!tabledesc.isCheck
        // }
        // })

  </script>

  <style scoped>
    .notavailableBookable{
      background:red;
    }
    .availableBookable{
      background:green;
    }
    .bookableClass{
      padding:10px;
      margin-top:-10px;
      color:#fff;
      border-radius:10px;
      font-size: 11px;
    }
  </style>
   
  