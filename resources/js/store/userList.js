import { defineStore } from 'pinia'
import axios from "axios";
import {useLoading} from 'vue-loading-overlay'

export const listOfUsers = defineStore('userList',{

      state: () => ({
            search:'',

            userslist:[],

            user:"",

            profileStatus:'All',

            available_status:[ 
                    'All',
                    'Active',
                    'Inactive'
                ]

      }),

      actions:{
            async allUsers(){
              // Check if an AbortController instance has been assigned to the controller variable, then call the abort method when true.
                  // To cancel a pending request the abort() method is called.
                  console.log("users data");
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


                await axios.post('/api/users',
                  {profileStatus:this.profileStatus},
                  {signal}
                ).then((response)=>{

                        if(response.statusText == 'OK'){
                              setTimeout(() => {
                                    loader.hide()
                              }, 100)
                        }
                        this.userslist= response.data.userslist;
                 
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
      getters:{
            filtersearch(){
                  return this.userslist.filter(user=>{
                    return user.role.toLowerCase().includes(this.search.toLowerCase())
                    || user.user_name.toLowerCase().includes(this.search.toLowerCase())
                    
                  })
                }
      },
      
      //persist:true
  
})
