import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import  Swal from 'sweetalert2';
import {useLoading} from 'vue-loading-overlay'

export const editUser = defineStore('editUser',{

      state: () => ({
        
        id:'',

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
            'All',
            'Active',
            'Inactive'
        ],
       
        userForm:{
            user_name:'',
            role:'',
            email_id:'',
            name:'',
            department_name:'',
            profile_status:''
        }
      }),

      actions:{
        async updateUserDetails(id){
            //alert(id);
            if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            const signal = this.controller.signal




            const $loading = useLoading()

            const loader = $loading.show({
                // Optional parameters
                color: '#5D2296',
                loader: 'dots',
            });

            await axios.post('/api/update-user',{
                userForm:this.userForm,
                userId:id,
            },{signal})
            .then((response)=>{
               
                if(response.data!=''){
                    
                    setTimeout(() => {
                            loader.hide()
                    }, 100)

                     Swal.fire({
                        title: "Updated sucessfully!",
                        icon: "success",
                    })
                }
                else{

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
        } ,
        deleteUser(id){
             Swal.fire({
                 title: "Delete this record?",
                      showDenyButton: true,
                      confirmButtonText: 'Yes',
                      denyButtonText: 'No',
                      text: "Are you sure? Once deleted you won't be able to recover this!",
                      icon: "warning",
                      //buttons: true,
                      //dangerMode: true,
            }).then((willDelete) => {
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

                    axios.post('/api/delete-user',{
                        userId:id,
                    },{signal}).then((response)=>{
                        
                        console.log(typeof response.data);
                        if(response.data!=''){

                            setTimeout(() => {
                                    loader.hide()
                            }, 100)

                             Swal.fire({
                                title: "User record deleted sucessfully!",
                                icon: "success",
                            }).then((result)=>{
                                if(result.isConfirmed)
                                { //redirect to user list page after delete
                                    routes.push({name: "users"});
                                }
                            })
                           
                            
                            }
                            else
                            {
                                setTimeout(() => {
                                    loader.hide()
                                }, 100)
                                
                                Swal.fire({
                                        title: "Record not deleted",
                                        text: "Error in Database",
                                        icon: "warning"
                                }) 
                            }
                    })
                } 
                else {

                    setTimeout(() => {
                        loader.hide()
                        }, 100)

                     Swal.fire("Your user record is safe!");
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

        async EditUser(userId){
            
            const $loading = useLoading()

                    const loader = $loading.show({
                            // Optional parameters
                            color: '#5D2296',
                            loader: 'dots',
                    });
           
        await axios.post('/api/edit-user',{
                userId:userId,
            }).then(({data})=>{

                setTimeout(() => {
                    loader.hide()
                    }, 100)

                 this.userForm= data
            })
        },
      },


      //persist:true
  
})
