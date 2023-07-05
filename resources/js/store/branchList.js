import { defineStore } from 'pinia'
import axios from "axios";
import {useLoading} from 'vue-loading-overlay'
export const listOfBranches = defineStore('branchList',{

      state: () => ({

            searchTerm:'',
            branchDetails:'',           
            profileStatus:'All',
            available_status:[ 
                    'All',
                    'Active',
                    'Inactive'
                ],
           
      }),

      actions:{
           async allBranchDetails(){
              // Check if an AbortController instance has been assigned to the controller variable, then call the abort method when true.
                  // To cancel a pending request the abort() method is called.
                  console.log("branch data");
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

                await axios.get('/api/branch',
                    {signal}
                  ).then((response)=>{
                      this.branchDetails= response.data.branch_details;
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
                }
      },
      
  //    persist:true
  
})
