
import { defineStore } from 'pinia'
import routes from '../router/index.js';
import axios from "axios";
import { VueCookieNext } from 'vue-cookie-next';




export const search = defineStore('search',{

      state: () => ({
            branches:[    
                        'Bedford',
                        'Faversham (Kent)',
                        'Flixborough (lincolnshire)',
                        'Warminster (wiltshire)',
                        'Basingstoke',
                        'Liverpool',
                        'Witham',
                        'All'
                  ],
            previousBranch:1,
            branchName:'',
            selectedBranch:1, /*BEDFORD BRANCH ID-1*/
            selectedDate:new Date().toISOString().slice(0,10),    /*Current date*/
            
            // selectedBranch:'', /*BEDFORD BRANCH ID-1*/
            // selectedDate:'',    /*Current date*/

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
            
            

      }),

      actions:{
      //actions are like setters
      // since we rely on `this`, we cannot use an arrow function in actions

            testLatAndLong(){
                  axios.post('/api/latAndLong',{
                        testValue:'MK4101LF'
                  }).then((response)=>{
                        // console.log("lat and long response");
                        // console.log(response);
                  })
            },

            SearchRequestedData(selectedBranch,selectedDate){
                  
                  
				axios.post('/api/vehiclediary',{
					selectedBranch,
					selectedDate
				}).then((response)=>{
                             
                              console.log(response.data);
                              this.allOrders=response.data;
                              this.selectedData = response.data.vehicleOrdersWithRuns;
                              this.branchName=response.data.getBranchName;
                              this.collectionsData=response.data.collectionOrdersForSelectedWeek;
                              this.couriersOrders=response.data.courierDataForSelectedWeek;
                              this.postData=response.data.postDataForSelectedWeek;
                              if(this.selectedData!=''){
                                    routes.push( {path: '/dashboard'});
                                    this.divVisibility='false';
                                    this.refresh_time = (new Date(Date.now() - ((new Date()).getTimezoneOffset()*60000))).toISOString().slice(0, 19).replace('T', ' ');
                                   
                                    this.refresh_order_interval=setInterval(() =>this.notificationAlertForOrders(this.refresh_time),6000);
                                     
                              }
					
				});
            },
            notificationAlertForOrders(last_refresh){
                  // const cookie = useCookie();
                  VueCookieNext.removeCookie('last_refresh_value');
                  VueCookieNext.setCookie('last_refresh_value', last_refresh, { path: '/' });
                  this.cookie_value=VueCookieNext.getCookie('last_refresh_value')
	            axios.post('/api/notifications',{
                        cookieValue:this.cookie_value,
                        selectedBranch:this.selectedBranch,
                        selectedDate:this.selectedDate,
                  }).then(({data})=>{
                        if(data.error!='' && data.new_orders!=''){
                              this.tableRefreshData=data.new_orders;
                              // console.log("new orders");
                              // console.log(data.new_orders);
                              this.divVisibility='true';
                        }
                  })
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
      
       //persist:true

  
})
