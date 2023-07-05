
import { defineStore } from 'pinia'
import { VueCookieNext } from 'vue-cookie-next';
export const input = defineStore('input',{

      state: () => ({
            // branches:[    
            //             'Bedford',
            //             'Faversham (Kent)',
            //             'Flixborough (lincolnshire)',
            //             'Warminster (wiltshire)',
            //             'Basingstoke',
            //             'Liverpool',
            //             'Witham',
            //             'All'
            //       ],
            branches:[],
            branches_data:[],
            finalBranches:[],
            previousBranch:1,//without submit show previous data in drop down

            branchName:'',
            selectedBranch:'', /*BEDFORD BRANCH ID-1*/
            selectedDate:new Date().toISOString().slice(0,10),    /*Current date*/
            //selectedDate:'2022-10-25'
            // selected_branch_cookie_value:1,
            // selected_date_cookie_value:new Date().toISOString().slice(0,10)
       }),

       actions:{
            setBranchValue(){
                  this.selectedBranch=VueCookieNext.getCookie('selected_branch')
            },
                  // setBranches(){
                  //       this.branches_data=VueCookieNext.getCookie('branch_list');
                  //       this.branches_data=JSON.parse(this.branches_data);
                  //       console.log(this.branches_data);
                  //       this.branches_data.forEach((object,index,array) => {
                  //             //console.log(object['branch_location']);
                  //             this.branches[index] = object['branch_location']
                  //             if(index==1){
                  //                   //console.log(object);
                  //                   object.forEach((branch_details,branch_id,array)=>{
                  //                         // console.log(branch_details['branch_location']);
                  //                         // console.log(branch_details['branch_id']);
                  //                         this.branches[branch_details['branch_id']] = branch_details['branch_location']
                  //                   })
                  //             }
                  //       });
                  //       console.log(this.branches);
                  //       // VueCookieNext.setCookie('branch_list', this.branches_data, { path: '/' });
                  //       // this.branches=VueCookieNext.getCookie('branch_list');
                  //       // console.log(this.branches);
                  //        //this.branches.push({branch_id:0, branch_location:'All'});
                  //  },    
                  setBranches(){
                        this.branches_data=VueCookieNext.getCookie('branch_list');
                        this.branches_data=JSON.parse(this.branches_data);
                        
                        this.branches=[];
                        this.branches_data.forEach((object,index,array) => {
                              var check=this.branches.some(e1=>e1.branch_id==object['branch_id']);
                              if(!check){
                                    this.branches.push({branch_id:object['branch_id'], branch_location:object['branch_location']});
                              }
                              var found = this.branches.some(el => el.branch_id === 0);
                              if (!found) this.branches.unshift({ branch_id:0, branch_location:'All' });
                              
                        });
                  }                        
            
       },
       getters:{
            // getBranches(state){
            //       return state.branches;
            // }

      },
      //persist:true

  
})
