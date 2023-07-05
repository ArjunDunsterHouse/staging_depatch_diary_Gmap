import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import  Swal from 'sweetalert2';
import {useLoading} from 'vue-loading-overlay'

export const editBranch = defineStore('editBranch',{

      state: () => ({
        
        id:'',
       
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
        async updateBranchDetails(id){
           // alert(id);
           if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            const signal = this.controller.signal



            const $loading = useLoading()

                    const loader = $loading.show({
                          // Optional parameters
                        color: '#5D2296',
                        loader: 'dots',
                      });


            axios.post('/api/update-branch',{
                branchForm:this.branchForm,
                branchId:id,
            },{signal})
            .then(({data})=>{
                //console.log(data);
                if(data.error==''){

                
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
        deleteBranch(id){
            
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



                    axios.post('/api/delete-branch',{
                        branchId:id,
                    }).then(({data})=>{
                        
                    if(data.error==''){
                        
                                setTimeout(() => {
                                        loader.hide()
                                }, 100)
                 
                             Swal.fire({
                                title: "Branch record deleted sucessfully!",
                                icon: "success",
                            }).then((result)=>{
                                if(result.isConfirmed)
                                { //redirect to user list page after delete
                                    routes.push({name: "branch"});
                                }
                              })
                           
                            
                    }
                    else{

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
                } else {
                     Swal.fire("Your Branch record is not Deleted, its safe!");
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

        async EditBranch(branchId){
      
            const $loading = useLoading()

                    const loader = $loading.show({
                          // Optional parameters
                        color: '#5D2296',
                        loader: 'dots',
                      });

            await axios.post('/api/edit-branch',{
                branchId:branchId,
            }).then(({data})=>{

                setTimeout(() => {
                    loader.hide()
                }, 100)
                
                //console.log(data.error);
                if(data.error==''){
                    this.branchForm= data.result;
                    //console.log(this.branchForm);
                }else{
                    Swal.fire({
                        title: "Unable to fetch record",
                        text: "Error in Database",
                        icon: "warning"
                    }) 
                }
                 
            })
        },
      },


      //persist:true
  
})
