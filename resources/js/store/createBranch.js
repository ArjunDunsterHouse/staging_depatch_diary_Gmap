import { defineStore } from 'pinia'
import axios from "axios";
import  Swal from 'sweetalert2';
import routes from '../router/index.js';
import {useLoading} from 'vue-loading-overlay'

export const createNewBranch = defineStore('createNewBranch',{

      state: () => ({
        
        branchForm:{
            branch_location:'',
            branch_code:'',
            shipping_agent_code:'',
            branch_postcode:'',
            latitude:'',
            longitude:''
        }
  
      }),

      actions:{
        async createNewBranch(Form){
            //write create new branch code here
            if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            const signal = this.controller.signal

            const $loading = useLoading()

                    const loader = $loading.show({
                          // Optional parameters
                        color: '#5D2296',
                        loader: 'dots',
                      });

          axios.post('/api/create-branch',{
              branchForm:Form,
          },{signal}).then(({data})=>{

                setTimeout(() => {
                  loader.hide()
                }, 100)
              //console.log(data);
              if(data.error=='' && data.result!=''){
                Swal.fire({
                  title: "Created sucessfully!",
                  icon: "success",
                }).then((result)=>{
                //  if(result.isConfirmed)
                 // {
                      routes.push({name: "branch"});
                 // }
                })
              }else{
                Swal.fire({
                   title: "Record not saved",
                   text: "Error/Duplicate record exist in database.",
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

      }
      //persist:true
  
})
