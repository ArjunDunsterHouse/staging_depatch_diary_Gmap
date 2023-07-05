import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import  Swal from 'sweetalert2';
import { VueCookieNext } from 'vue-cookie-next';
import {useLoading} from 'vue-loading-overlay'

export const createNewVehicle = defineStore('createNewVehicle',{

      state: () => ({
        helpText: [
          "Van number should have a prefix of VAN",
          "Ex: if the shipping agent service code is VAN123RUN1,",
          "the Van number must be VAN123"
        ].join('\n'),
            vehicleForm:{
                  van_number:'',
                  start_date:'',
                  end_date:'',
                  branch_id:'',
                  shipping_agent_service_code:'',
                  delivery_capacity:'',
                  target_amount:'',
                  registration_number:'',
                  vehicle_type:'',
                  vehicle_status:''
              },
            //   branches:[    
            //         'Bedford',
            //         'Faversham',
            //         'Flixborough',
            //         'Warminster',
            //         'Basingstoke',
            //         'Liverpool',
            //         'Witham'
            //   ],
            branches:[],
            branches_fix: true,
            branches_data:[],
              vehiclestatus : [ 
                  {index: 0, name: "Not Active"}, 
                  {index: 1, name: "Active"},
                  {index: 2, name: "Inservice"},
                  {index: 3, name: "Accident or Repair"}
              ],

              checkDuplicate:'new'
  
      }),

      actions:{


        async checkDublicateVan(van){

          console.log(van);
          
        await axios.post('/api/check-van-validation',{
            vanNo:van,
          }).then((response)=>{
              console.log(response.data.result);
              
            
              if(response.data.result=="new"){
                this.checkDuplicate = "";
              }else{
                this.checkDuplicate="exist";
              }
              console.log(this.checkDuplicate);
              
          })
          
        },
        setBranches(){
          this.branches_fix = false;
          this.branches = [];
            this.branches_data=VueCookieNext.getCookie('branch_list');
            this.branches_data=JSON.parse(this.branches_data);
            //console.log(this.branches_data);
            this.branches_data.forEach((object,index,array) => {
                  //this.branches[object['branch_id']] = object['branch_location']
                  this.branches.push({branch_id:object['branch_id'],branch_location:object['branch_location']});
                   if(this.branches[0]!=''){
                        //this.branches.push({branch_id:0, branch_location:'All'});
                        //this.branches[0]='All';
                   }
                  

            });
            //console.log(this.branches);
      }  ,

            async createVehicleRecord(){
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



                await  axios.post('/api/create-vehicle',{
                      vehicleForm:this.vehicleForm,
                  }).then((response)=>{
                   
                      if(response.data!='')
                      {
                        if(response.statusText == 'OK'){
                            setTimeout(() => {
                                  loader.hide()
                            }, 100)
                            this.vehicleForm.van_number='';
                            this.vehicleForm.start_date='';
                            this.vehicleForm.end_date='';
                            this.vehicleForm.branch_id='';
                            this.vehicleForm.shipping_agent_service_code='';
                            this.vehicleForm.delivery_capacity='';
                            this.vehicleForm.target_amount='';
                            this.vehicleForm.registration_number='';
                            this.vehicleForm.vehicle_type='';
                            this.vehicleForm.vehicle_status='';
                          }
                           Swal.fire({
                              title: "Created sucessfully!",
                              icon: "success",
                          }).then((result)=>{
                            if(result.isConfirmed)
                            {
                                routes.push({name: "vehicle"});
                            }
                          })
                      }
                      if(response.data.includes('Could not connect'))
                      {
                            setTimeout(() => {
                              loader.hide()
                            }, 100)

                            Swal.fire({
                              title: "Record not saved",
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
      getters:
      {
        getbranches_fix(state)
        {
          return state.branches_fix
        },

        getDublicateStatus(state){
          return state.checkDuplicate
        }
      }
        
     // persist:true
  
})
