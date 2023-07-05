
import { defineStore } from 'pinia'
import routes from '../router/index.js';
import axios from "axios";
import { VueCookieNext } from 'vue-cookie-next';
import { input } from '../store/input.js'
import {useLoading} from 'vue-loading-overlay'



export const search = defineStore('search',{

      state: () => ({

            submitStatus:false,
            selectedData:[],
            searchQuery:'',
            vanData:'',
            collectionsData:[],
            couriersOrders:[],
            postData:[],
            allOrders:[],
            refresh_time:'',
            cookie_value:'',
            tableRefreshData:'',
            divVisibility:'false',               //by default it must be false
            refresh_order_interval:'',
            courierGosbertonStatus:false,
            hideorShowVanRun: true,
            hideorShowParentVan: true,
                   
            
      }),

      actions:{
      //actions are like setters
      // since we rely on `this`, we cannot use an arrow function in actions
            

            ParentCollapseHideOrShow(vanNum){
              

                  let Parentcollaspe = document.getElementById(vanNum);

                  let isExpanded= Parentcollaspe.getAttribute('aria-expanded');

                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal;
                
                  if(isExpanded==="true"){
                     
                        this.hideorShowParentVan = 1;
                  }else{
                      
                        this.hideorShowParentVan = 0;
                  }


                  axios.post('/api/parentCollapseForVehicle',{
                        vanNum:vanNum,
                        parentHideOrShowStatus:this.hideorShowParentVan
                        },{ signal }).then((response)=>{
                              console.log("testing parent")
                              console.log(response.data);
                  })
            },


            hideShowVanRun(van){
                 
                  let collaspe="";
                  if(van.includes('RUN'))
                  {
                        collaspe = document.getElementById('#'+van);
                  }
                  else{
                       collaspe = document.getElementById('#'+van+'RUN');
                  } 
                 
                  let isExpanded= collaspe.getAttribute('aria-expanded');
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal;
                
                  if(isExpanded==="true"){
                       
                        this.hideorShowVanRun = 1;
                  }else{
                     
                        this.hideorShowVanRun = 0;
                  }
                 
                  axios.post('/api/updateVehicleTabStatus',{
                        vanNo:van,
                        hideOrShowStatus:this.hideorShowVanRun
                  },{ signal }).then((response)=>{
                        console.log(response);
                  })
         
            },

            testLatAndLong(){
                  axios.post('/api/latAndLong',{
                        testValue:'MK41 0LF'
                  }).then((response)=>{
                        // console.log("lat and long response");
                        // console.log(response);
                  })
            },
            // async SearchRequestedData(selectedBranch,selectedDate){
                  
            //       if(selectedBranch==9){
            //             this.courierGosbertonStatus=true;
            //       }else{
            //             this.courierGosbertonStatus=false;
            //       }
            //       /*Clear refresh orders interval*/
            //       clearInterval(this.refresh_order_interval);
            //       // Check if an AbortController instance has been assigned to the controller variable, then call the abort method when true.
            //       // To cancel a pending request the abort() method is called.
                 

            //      // this.res = '';

            //       if (this.controller) this.controller.abort();
            //       this.controller = new AbortController();
            //       const signal = this.controller.signal;
            //       const inputData = input();


            //       //starting to show page loader
            //       const $loading = useLoading()

            //       const loader = $loading.show({
            //             // Optional parameters
            //           color: '#5D2296',
            //           loader: 'dots',
            //         });


                  
            //       await axios.post('/api/vehiclediary',
            //             {selectedBranch:selectedBranch,
		// 		selectedDate:selectedDate},
            //             { signal }
            //             // {timeout:3000}
            //       ).then((response)=>{
            //             console.log(response);      
		// 	}).catch(function(e) {
            //             // Check the exception is was caused request cancellation
            //             if (e.name === 'AbortError') {
            //                   console.log('Request canceled', e.name);
            //                   // handle cancelation error
            //             } else {
            //                   // handle other error
            //             }
            //       });
            // },
            async SearchRequestedData(selectedBranch,selectedDate){
                  
                  if(selectedBranch==9){
                        this.courierGosbertonStatus=true;
                  }else{
                        this.courierGosbertonStatus=false;
                  }
                  /*Clear refresh orders interval*/
                  clearInterval(this.refresh_order_interval);
                  // Check if an AbortController instance has been assigned to the controller variable, then call the abort method when true.
                  // To cancel a pending request the abort() method is called.
                 

                 // this.res = '';

                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal;
                  const inputData = input();


                  //starting to show page loader
                  const $loading = useLoading()

                  const loader = $loading.show({
                        // Optional parameters
                      color: '#5D2296',
                      loader: 'dots',
                    });


                  
                  await axios.post('/api/vehiclediary',
                        {selectedBranch:selectedBranch,
				selectedDate:selectedDate},
                        { signal }
                        // {timeout:3000}
                  ).then((response)=>{
                              /*Clear refresh orders interval*/
                              clearInterval(this.refresh_order_interval);
                             
                              console.log(response);
                              this.allOrders=response.data;
                              this.selectedData = response.data.vehicleOrdersWithRuns;
                              console.log(this.selectedData);
                              this.collectionsData=response.data.collectionOrdersForSelectedWeek;
                              this.couriersOrders=response.data.courierDataForSelectedWeek;
                              this.postData=response.data.postDataForSelectedWeek;
                              //alert(response.data.branch_list.length);
                              VueCookieNext.setCookie('branch_list', JSON.stringify(response.data.branch_list), { path: '/' });
                              inputData.setBranches();
                              if(this.selectedData!=''){  
                                    routes.push( {path: '/dashboard'});
                             
                                    if(response.statusText == 'OK'){
                                          setTimeout(() => {
                                                loader.hide()
                                          }, 100)
                                    }
                                    this.divVisibility='false';
                                    this.refresh_time = (new Date(Date.now() - ((new Date()).getTimezoneOffset()*60000))).toISOString().slice(0, 19).replace('T', ' ');
                                   
                                    this.refresh_order_interval=setInterval(() =>this.notificationAlertForOrders(this.refresh_time,selectedBranch,selectedDate),1000*35);      
                              }
                              else{
                                    if(selectedBranch==9){
                                          routes.push( {path: '/dashboard'});
                                    }
                                    // if responce is empty still close page loader   
                                    setTimeout(() => {
                                          loader.hide()
                                    
                                          }, 100)
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
            },
            notificationAlertForOrders(last_refresh,Branch,Date){
                  // const cookie = useCookie();
                  VueCookieNext.removeCookie('last_refresh_value');
                  VueCookieNext.setCookie('last_refresh_value', last_refresh, { path: '/' });
                  this.cookie_value=VueCookieNext.getCookie('last_refresh_value');
                  console.log("notifications");
                  console.log(this.controller);
	            //axios.defaults.timeout=6000;
                  // Check if an AbortController instance has been assigned to the controller variable, then call the abort method when true.
                  // To cancel a pending request the abort() method is called.
                  if (this.controller) this.controller.abort();
                  this.controller = new AbortController();
                  const signal = this.controller.signal
                  axios.post('/api/notifications',
                        {
                              cookieValue:this.cookie_value,
                              selectedBranch:Branch,
                              selectedDate:Date
                        },
                        {signal}
                        // {timeout:1000*15}
                  ).then(({data})=>{
                        if(data.error!='' && data.new_orders!=''){
                              this.tableRefreshData=data.new_orders;
                              this.divVisibility='true';
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
            },
            
           
      },
      getters:{
            getCollections(state){
                  return state.collectionsData;
            },
            getCourier(state){
                  return state.allOrders.courierOrdersForSelectedWeek;
            },
            getPost(state){
                  return state.allOrders.postOrdersForSelectedWeek;
            },
            getWeightAndValueInAday(state){
                  return state.allOrders.dayWeightAndValueForAllDeliveries;
            },

            getHideShowVanRun(state){
                  return state.hideorShowVanRun;
            }
            // filterVehicleSearch(){
            //       var filterKey = this.searchQuery &&
            //       this.searchQuery.toLowerCase();
            //       var data=this.selectedData;
            //       if (!filterKey) {
            //             return data;
            //       }
            //       //  console.log("filter search in search js file");
            //       //  //this.vanData=this.selectedData.vanNumber;
            //       //  console.log(this.selectedData);

            //       // return Object.keys(this.selectedData).filter(function(key) {
            //       //       var row = data[key];
            //       //       return Object.keys(row).some(function(key) {
            //       //             return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
            //       //       })
            //       // })
            //       // .reduce(function(acc, key) {
            //       //       acc[key] = data[key];
            //       //       return acc;
            //       // }, {})

                  
            //       // return this.selectedData.filter(van=>{
            //       //      // return van.registration_number.toLowerCase().includes(this.searchTerm.toLowerCase())
            //       // })
            //       //  return this.vanData.filter(function(item){
            //       //       return item.registration_number.toLowerCase().includes(this.searchTerm.toLowerCase())
            //       //  })
            //       //       return 
            //       // })
            //       // return this.selectedData.filter(van=>{
            //       //   return van.registration_number.toLowerCase().includes(this.searchTerm.toLowerCase())
            //       //   || van.vehicle_type.toLowerCase().includes(this.searchTerm.toLowerCase())
            //       //   || van.target_amount.toLowerCase().includes(this.searchTerm.toLowerCase())
            //       //   || van.delivery_capacity.toString().toLowerCase().includes(this.searchTerm.toLowerCase())
            //       // })
            //     }
      },

  
})
