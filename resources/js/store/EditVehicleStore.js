import { defineStore } from 'pinia'
import moment from "moment";
import axios from "axios";
import routes from '../router/index.js';
import  Swal from 'sweetalert2';
import { VueCookieNext } from 'vue-cookie-next';
import {useLoading} from 'vue-loading-overlay'

export const EditVehicleStore = defineStore('EditVehicleStore',{

      state: () => ({

            vehicleForm:'',
            id:'',
            validationError:false,
            
            // branches:[    
            //       'Bedford',
            //       'Faversham',
            //       'Flixborough', 
            //       'Warminster',
            //       'Basingstoke',
            //       'Liverpool',
            //       'Witham'
            // ], 
            branches_fix:true,
            branches:[],
            branches_data:[],       
            vehiclestatus : [ 
                {index: 0, name: "Not Active"}, 
                {index: 1, name: "Active"},
                {index: 2, name: "Inservice"},
                {index: 3, name: "Accident or Repair"}
            ],           
            vehicleForm1:{
                van_number:'',
                start_date:'',
                end_date:'',
                branch_id:'',
                shipping_agent_service_code:'',
                delivery_capacity:'',
                target_amount:'',
                registration_number:'',
                vehicle_type:'',
                vehicle_status:'',
                display_order:''
            },

            previousVehicleStatus:"",
      }),

      actions:{

        setBranches(){
            this.branches_fix = false;
            this.branches_data=VueCookieNext.getCookie('branch_list');
            this.branches_data=JSON.parse(this.branches_data);
            //console.log(this.branches_data);
            this.branches_data.forEach((object,index,array) => {

                this.branches.push({branch_id:object['branch_id'],branch_location:object['branch_location']});
                //  this.branches[object['branch_id']] = object['branch_location']
                //    if(this.branches[0]!=''){
                //         //this.branches.push({branch_id:0, branch_location:'All'});
                //         //this.branches[0]='All';
                //    }
                  

            });
            //console.log(this.branches);
      }  ,
         async  updateVehicleDetails(vid){
                this.vehicleStatusEndDateCheck();
             
                if(!this.validationError){
                    if (this.controller) this.controller.abort();
                    this.controller = new AbortController();
                    const signal = this.controller.signal



                    const $loading = useLoading()

                    const loader = $loading.show({
                          // Optional parameters
                        color: '#5D2296',
                        loader: 'dots',
                      });

               await  axios.post('/api/update-vehicle',{
                        vehicleForm:this.vehicleForm1,
                        vehicleId:vid,}
                     ,{signal})
                    .then((response)=>{
                      //console.log(response);

                   
                        setTimeout(() => {
                              loader.hide()
                        }, 100)
                   
                        if(response.data!='')
                             Swal.fire({
                                title: "Updated sucessfully!",
                                icon: "success",
                            })
                        else
                             Swal.fire({
                                title: "Record not saved",
                                text: "Error in Database",
                                icon: "warning"
                            })      
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

             deleteVehicle(vid){
                   Swal.fire({
                      title: "Delete this record?",
                      showDenyButton: true,
                      confirmButtonText: 'Yes',
                      denyButtonText: 'No',
                      text: "Are you sure? Once deleted you won't be able to recover this!",
                      icon: "warning",
                      //buttons: true,
                      //dangerMode: true,
                  })
                  .then((willDelete) => {
                      if (willDelete.isConfirmed) {
                        if (this.controller) this.controller.abort();
                        this.controller = new AbortController();
                        const signal = this.controller.signal



                        const $loading = useLoading()

                        const loader = $loading.show({
                            // Optional parameters
                            color: '#5D2296',
                            loader: 'dots',
                        });

                        axios.post('/api/delete-vehicle',{
                              vehicleId:vid,
                            
                          },{signal}).then((response)=>{

                            setTimeout(() => {
                                loader.hide()
                          }, 100)
                             
                             if(response.data!=''){
                                  Swal.fire({
                                      title: "Vehicle record deleted sucessfully!",
                                      icon: "success",
                                  }).then((result)=>{
                                    //if(result.isConfirmed)
                                    //{
                                        routes.push({name: "vehicle"});
                                    //}
                                  })

                                 
                             }
                             else
                               Swal.fire({
                                  title: "Record not deleted",
                                  text: "Error in Database",
                                  icon: "warning"
                              }) 
                          })
                      } else {
                          Swal.fire("Your vehicle record is not Deleted, its safe!");
                      }
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

            async  InitializeEditVehicle(vid)
              {
                  let id = vid;
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal

                  const $loading = useLoading()

                        const loader = $loading.show({
                            // Optional parameters
                            color: '#5D2296',
                            loader: 'dots',
                        });

    
                await  axios.post('/api/edit-vehicle',{
                            vehicleId:id,
                      },{signal}).then((response)=>{


                            this.vehicleForm= response.data

                            setTimeout(() => {
                                loader.hide()
                          }, 100)


                      this.vehicleForm1.van_number=this.vehicleForm[0].van_number
                      this.vehicleForm1.start_date=moment(this.vehicleForm[0].start_date).format("yyyy-MM-DD")
                      this.vehicleForm1.end_date=moment(this.vehicleForm[0].end_date).format("yyyy-MM-DD")
                      this.vehicleForm1.branch_id=this.vehicleForm[0].branch_id
                      this.vehicleForm1.shipping_agent_service_code=this.vehicleForm[0].shipping_agent_service_code
                      this.vehicleForm1.delivery_capacity=this.vehicleForm[0].delivery_capacity
                      this.vehicleForm1.target_amount=this.vehicleForm[0].target_amount
                      this.vehicleForm1.registration_number=this.vehicleForm[0].registration_number
                      this.vehicleForm1.vehicle_type=this.vehicleForm[0].vehicle_type
                      this.vehicleForm1.vehicle_status=this.vehicleForm[0].vehicle_status 
                      this.vehicleForm1.display_order=this.vehicleForm[0].display_order

                      this.previousVehicleStatus = this.vehicleForm1.vehicle_status;
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

              vehicleStatusEndDateCheck(){
                
                if(this.previousVehicleStatus == 0 && this.vehicleForm1.vehicle_status == 1)
                {
                    let currentDate = new Date().toISOString().slice(0,10);
                    if(this.vehicleForm1.end_date  <= currentDate )
                    {
            
                        //console.log("working fine");
                        Swal.fire({
                                          title: "Issue in end date",
                                          text: "End date must be greater than current date",
                                          icon: "warning"
                                      })   
                                      this.validationError=true;  
                    }
                    else{
                        this.validationError=false;  
                    }
                   
                }
              }
      },
      getters:
      {
        getbranches_fix(state)
        {
          return state.branches_fix
        }
      }

      
      //persist:true
  
})
