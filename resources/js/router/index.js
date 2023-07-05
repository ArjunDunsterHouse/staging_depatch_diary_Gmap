import {createRouter,createWebHistory} from 'vue-router';
import swal from 'sweetalert';
import { SignIn } from '../store/login.js'
import { input } from '../store/input.js'
import {useLoading} from 'vue-loading-overlay'
import { search } from '../store/searchData.js'
import { VueCookieNext } from 'vue-cookie-next';


const routes = createRouter(
      {
            history:createWebHistory(),
            routes:[
                  { 
                        path:'/',
                        name: 'Home',
                        component: () => import('../home.vue'),
                        beforeEnter:[login_Status_check],//check if user is typing URL directly into the address bar
                  },
                  { 
                        path:'/profile',
                        name:'profile',
                        component: () => import('../views/profile.vue'),
                        beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                      
                  },
                  {
                         path:'/dashboard',
                         name:'dashboard',
                         component: () => import('../views/dashboard.vue'),
                         beforeEnter:[login_Status_check,LoadDashboard],//check if user is typing URL directly into the address bar
                         props:true
                  },
                  {
                         path:'/vehicle',
                         name:'vehicle',
                         component: () => import('../views/Vehicle/Vehicle.vue'),
                         beforeEnter:[login_Status_check,VehiclepageLoad],//check if user is typing URL directly into the address bar
                         props:true
                  },
                  {
                         path:'/edit-vehicle',
                         name:'edit-vehicle',
                         component: () => import('../views/Vehicle/EditVehicle.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                         path:'/create-vehicle',
                         name:'create-vehicle',
                         component: () => import('../views/Vehicle/createNewVehicle.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  }
                  ,
                  {
                         path:'/users',
                         name:'users',
                         component: () => import('../views/User/User.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                         path:'/edit-user',
                         name:'edit-user',
                         component: () => import('../views/User/EditUser.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                         path:'/create-user',
                         name:'create-user',
                         component: () => import('../views/User/CreateUser.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                         path:'/branch',
                         name:'branch',
                         component: () => import('../views/Branch/Branch.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                            path:'/edit-branch',
                            name:'edit-branch',
                            component: () => import('../views/Branch/EditBranch.vue'),
                            props:true,
                            beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                            path:'/create-branch',
                            name:'create-branch',
                            component: () => import('../views/Branch/CreateBranch.vue'),
                            props:true,
                            beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
                  {
                         path:'/test',
                         name:'test',
     
                         component: () => import('../Tabs.vue'),
                         props:true,
                         beforeEnter:[login_Status_check,pageLoad],//check if user is typing URL directly into the address bar
                  },
 
            ]
      }
);

function pageLoad(){
      
       document.documentElement.style.overflowX = 'hidden';//hide X-scroll on all pages except dashboard

       const $loading = useLoading()
  
       
       const loader = $loading.show({
                      // Optional parameters
                    color: '#5D2296',
                    loader: 'dots',
                  });
                  // simulate AJAX
                  setTimeout(() => {
                      loader.hide()
                  }, 200)
             
} 

function VehiclepageLoad(){

       document.documentElement.style.overflowX = 'hidden';//hide X-scroll on all pages except dashboard

       const $loading = useLoading()
  
       
       const loader = $loading.show({
                      // Optional parameters
                    color: '#5D2296',
                    loader: 'dots',
                  });
                  // simulate AJAX
                  setTimeout(() => {
                      loader.hide()
                  }, 700)

}
// function scrollDisplay(screen){ // media queries with javascript

//        if (screen.matches) { // If media query matches
//               document.documentElement.style.overflowX = 'hidden';
//        }
//        else 
//        {
//               document.documentElement.style.overflowX = 'scroll'
//        }

// }
function LoadDashboard(){
      
        document.documentElement.style.overflowX = 'scroll'; // show X-scroll only on dashboard

       //document.documentElement.style.overflowX = 'hidden';
       //if screen width is 1920px or greater match media is like media queries
       //let x = window.matchMedia("(min-width: 1880px)");
       //scrollDisplay(x);

       const inputData = input(); 
       inputData.setBranches(); 
  }

function login_Status_check(to){
//check if user is typing URL directly into the address bar
       const login = SignIn();
       const searchSelectedData = search();
       //const cookie = useCookie();
       if((to.path ==='/'))
       {//fixing flikering of sidebar and navbar on login page
              
              login.logged_status = 'false';
              VueCookieNext.removeCookie('selected_branch');
              VueCookieNext.removeCookie('selected_date');
              login.firstResponseCheck=false;
              /*Clear refresh orders interval*/
              clearInterval(searchSelectedData.refresh_order_interval);
             // cookie.removeCookie('last_refresh');

       }

        if((to.path ==='/dashboard' || to.path=='/vehicle'|| to.path=='/profile'
        || to.path=='/edit-vehicle' || to.path=='/create-vehicle' || to.path=='/users' 
        || to.path=='/edit-user' || to.path=='/create-user' || to.path=='/branch' 
        || to.path=='/edit-branch' || to.path=='/create-branch'|| to.path=='/test')
        && (login.logged_status!='true') 
        )
       {
              swal({
                     title: "User not logged in",
                     text: "please login to continue",
                     icon: "error"
              })
              /*Clear refresh orders interval*/
              clearInterval(searchSelectedData.refresh_order_interval);
              return { path: '/'}
              
       }
    
}

export default routes;