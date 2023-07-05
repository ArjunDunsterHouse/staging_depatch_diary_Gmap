import { defineStore } from 'pinia'
import axios from "axios";
import {useLoading} from 'vue-loading-overlay'

export const vehicleStore = defineStore('vehicleStore',{

      state: () => ({

            searchTerm:'',
            vehicleEditId:'',
            vehicles:[],
            vehicleStatus:1,
            vehiclestatus:[ 
            {index: 4, name: "All"},
            {index: 0, name: "Not Active"}, 
            {index: 1, name: "Active"},
            {index: 2, name: "Inservice"},
            {index: 3, name: "Accident or Repair"}
            ]
            
      }),

      actions:{
            async allVehicles(){
                  //console.log(this.vehicleStatus);
                  console.log("vehicle data");
                  console.log(this.controller);
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal


                  //starting to show page loader
                  const $loading = useLoading()

                  const loader = $loading.show({
                        // Optional parameters
                      color: '#5D2296',
                      loader: 'dots',
                    });



            await axios.post('/api/vehicles',{
                        vehicleStatus:this.vehicleStatus
                    },{signal}).then((response)=>{

                                    this.vehicles= response.data.vehicles;

                                    setTimeout(() => {
                                          loader.hide()
                                    }, 100)
                                          

                  }).catch(function(e) {

                        setTimeout(() => {
                              loader.hide()
                        }, 100)
                        
                        // Check the exception is was caused request cancellation
                        if (e.name === 'AbortError') {
                              console.log('Request canceled', e.name);
                              // handle cancelation error
                        } else {
                              // handle other error
                        }
                  });
            },

            
      },
      getters:{
            filtersearch(){
                 // console.log(this.vehicles);
                  return this.vehicles.filter(van=>{
                     //   return van.branchname
                    return van.branchname.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.van_number.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.start_date.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.end_date.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.shipping_agent_service_code.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.registration_number.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.vehicle_type.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.target_amount.toLowerCase().includes(this.searchTerm.toLowerCase())
                    || van.delivery_capacity.toString().toLowerCase().includes(this.searchTerm.toLowerCase())
                   })
                }
      }
      ,
     // persist:true
  
})
