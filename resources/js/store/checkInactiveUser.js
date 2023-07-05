import { defineStore } from 'pinia'
import {SignIn} from '../store/login.js'
import {LogOut} from '../store/logout.js'
import Swal from 'sweetalert2';

export const inactiveUserCheck = defineStore(
{
      id:  'checkIfUserInactive',

      state: () => ({

            events :[   
                  'click',
                  'mousemove',
                  'mousedown',
                  'scroll',
                  'keypress',
                  'load',
                  'input',
                  ],
            warningTimer :null,
            logOutTimer : null,
            warningZone : false,
            logOutInActiveUser:false,
            alertStatus:false
      }),
      actions:{
            
            setTimer(){
                  this.warningTimer = setTimeout(this.warningMessage,40*60*1000);

                  this.logOutTimer = setTimeout(this.logoutUser,45*60*1000);

                  this.warningZone = false;
            },
            
            logoutUser(){

                  this.logOutInActiveUser = true;

                  const userLogin = SignIn();
                  const userLogout = LogOut();

                  userLogout.logout(userLogin.admin_user_id,userLogin.logged_status)
                  userLogin.logged_status='false';
            },
           
           resetTimer(){
                  clearTimeout(this.warningTimer);
                  clearTimeout(this.logOutTimer);
                  this.setTimer()
            },

            warningMessage () {
                 this.warningZone = true;
                 let timerInterval;
                  Swal.fire({
                        title: "Are you still with us?",
                        icon: "warning",
                        html: 'You will be logged out in <b></b>.',
                        timer:5*60*1000,
                        confirmButtonText: 'Close',
                        timerProgressBar:true,
                        didOpen: () => {
                              const b = Swal.getHtmlContainer().querySelector('b')
                              timerInterval = setInterval(() => {
                                    let min = Math.floor(Swal.getTimerLeft() / 60000)
                                    let sec = ((Swal.getTimerLeft()  % 60000) / 1000).toFixed(0);
                                    b.textContent = min + ":" + (sec < 10 ? '0' : '') + sec;
                                   
                              }, 100)
                        }
                  
                  }).then((result)=>{
                     
                        if(result.isConfirmed){
                              this.alertStatus=true;
                              this.resetTimer();
                        }
                  })
            },

            registerEventListeners()
            {
                  this.events.forEach((event)=>window.addEventListener(event,this.resetTimer()));
            },
            browserLogout(){
                  this.events.forEach((event)=>window.addEventListener("beforeunload", (event),this.test()));
            },
            test(){
                  //alert('Hello');
               
            }
      },

      getters:{
        
      }
  
})
