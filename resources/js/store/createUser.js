import { defineStore } from 'pinia'
import axios from "axios";
import  Swal from 'sweetalert2';
import routes from '../router/index.js';
import {useLoading} from 'vue-loading-overlay'

export const createNewUser = defineStore('createNewUser',{

      state: () => ({
        canCopy:false,
        copyToClipboard:'',
        roles:[    
                'admin',
                'despatch_manager',
                'despatch_clerk',
                'despatch_view',
                'marketing_edit',
                'marketing_view',
                'sales_enquiries'
            ],
            
            departments : [ 
                'DH',
                'sales',
                'dev team',
                'marketing'
            ],
            available_status:[ 
                'Active',
                'Inactive'
            ],
            userForm:{
                user_name:'',
                password:'dunsterhouse',
                role:'',
                email_id:'',
                name:'',
                department_name:'',
                profile_status:''
            }
  
      }),

      actions:{

        async createUserRecord(Form){
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


        await axios.post('/api/create-user',{
                userForm:Form,
            },{signal}).then((response)=>{
                
                if(response.data.error==0){
                    if(response.data.result!='no'){
                        
                        setTimeout(() => {
                                  loader.hide()
                            }, 100)
                    

                         Swal.fire({
                            title: "Created sucessfully!",
                            icon: "success",
                        }).then((result)=>{
                          //  if(result.isConfirmed)
                          //  {
                             routes.push({name: "users"});
                           // }
                          })
                    }
                    
                }
                else{
                        setTimeout(() => {
                            loader.hide()
                         }, 100)

                     Swal.fire({
                        title: "Record not saved",
                        text: response.data.error,
                        icon: "warning"
                    })  
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

        async copy(s) {
            this.copyToClipboard="Username:" +s+"\nPassword:dunsterhouse"+ "\nRole:"+this.userForm.role + "\nEmailId:"+ this.userForm.email_id+"\nName:" + this.userForm.name+"\nSite URL:https://despatchdiary.co.uk:8080";
            await navigator.clipboard.writeText(this.copyToClipboard);
             Swal.fire({
                title: "Copied",
                icon: "success",
                text:"User details copied to clipboard"
            })
        }

       },
     // persist:true
  
})
