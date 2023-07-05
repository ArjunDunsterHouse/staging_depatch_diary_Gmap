import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import { search } from '../store/searchData.js'
import { VueCookieNext } from 'vue-cookie-next';
export const LogOut = defineStore(
{
    id:  'logout',
   
    actions:{
        logout(admin_user_id,logged_status,selected_branch){
          const searchSelectedData = search();
          clearInterval(searchSelectedData.refresh_order_interval);
          console.log("logout");
                
          console.log(this.controller);
          //this.controller.abort();
          if (this.controller) this.controller.abort();
          this.controller = new AbortController();
          const signal = this.controller.signal
          
              axios.post('/api/logout',{
                admin_user_id:admin_user_id,
                logged_status:logged_status,
                selected_branch:selected_branch,
              },{signal})
              .then(({data})=>{
                VueCookieNext.removeCookie('selected_branch');
                  if(data=='Inactive'){
                    // VueCookieNext.removeCookie('selected_branch');
                    // VueCookieNext.removeCookie('selected_date');
                   
                    /*Clear refresh orders interval*/
                    //clearInterval(searchSelectedData.refresh_order_interval);
                    routes.push({name: "Home"});
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
              routes.push({name: "Home"});
        }
    }
  
})
