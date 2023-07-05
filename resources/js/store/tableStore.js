import { defineStore } from 'pinia'
import { number } from '@formkit/inputs';
import axios from "axios";
import  Swal from 'sweetalert2';
import routes from '../router/index.js';
//import {useLoading} from 'vue-loading-overlay'

export const tabledesc = defineStore('table',{

      state: () => ({

            isBookableCheck: '',
            orders: '',
            updatedComments:'',
            bookableValue:'',
            date:'',
            vehicleNo:'',
            isCheck:Boolean,
            responseStatus:Object,
            bookableBackgroundColor:Boolean
      }),

      actions:{

            getComments(comments,date,vehicleNo){
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal;
                  console.log(comments);
                  console.log(vehicleNo);
                  axios.post('/api/updatecomments',{
                        updatedComments:comments,
                        selectedDate:date,
                        selectedVehicleNo:vehicleNo
                      },{signal}).then((response)=>{
                          console.log(response.data);
                  }).catch(function(e) {
                        // Check the exception is was caused request cancellation
                        if (e.name === 'AbortError') {
                              console.log('Request canceled', e.name);
                              // handle cancelation error
                        } else {
                              // handle other error
                        }
                  });
            },
            updateBookableValue(date,vehicleNo){

                  let id = date+vehicleNo;

                  //alert (id)
             
                  let bookedOrNot = document.getElementById(id);

                  let bookableText= bookedOrNot.getAttribute('aria-label');


                  //alert(typeof bookableText);

                  if(bookableText == 'Bookable')
                  {
                        //converting bookable into not bookable (green to red) (0 to 1)
                        this.isBookableCheck=1;
                  }
                  else{
                        //converting not bookable into bookable (red to green) (1 to 0)
                        this.isBookableCheck=0;
                  }
                  
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal
                  axios.post('/api/updatebookable',{
                        bookableValue:this.isBookableCheck,     
                        selectedDate:date,
                        vehicleNo:vehicleNo
                  },{signal}).then((response)=>{
                        
                        /**bookable value updated status :bookableValue:0-available,1-not avaialble
                         * updateStatus:1-success,0-failed
                         */
                        if(response.data.error_msg=='' && response.data.updateStatus==1){
                              window.location.reload();
                        }
                        else{
                              Swal.fire({
                                    title: "Bookable status not saved",
                                    text: "Error in Database",
                                    icon: "warning"
                              })  
                        }
                  }).catch(function(e) {
                        // Check the exception is was caused request cancellation
                        if (e.name === 'AbortError') {
                              console.log('Request canceled', e.name);
                              // handle cancelation error
                        } else {
                              // handle other error
                        }
                  }); 
            }
      },

     
     // persist:true
  
})
