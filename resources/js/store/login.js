import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import Swal from 'sweetalert2';
import md5 from 'md5';
import { input } from '../store/input.js'
import { search } from '../store/searchData.js'
import { VueCookieNext } from 'vue-cookie-next';

export const SignIn = defineStore(
      {
            id:  'login',

      state: () => ({

            userName:"",
            userPassword:"",
            role:"",
            logged_status:'false',
            admin_user_id:'',
            login_pwd:'',
            profileData:'',
            firstResponseCheck:false,
            branchList:[],
            tokenValidationStatus:'',
            email:'',
            attempt:0
            // selectedBranch:1, /*BEDFORD BRANCH ID-1*/
            // selectedDate:new Date().toISOString().slice(0,10)
      }),

      actions:{
      //actions are like setters
      // since we rely on `this`, we cannot use an arrow function in actions
            resetPassword(){
                  Swal.fire({
                        backdrop:true,
                        allowOutsideClick: false,
                        title:'Reset Password',
                        input: 'email',
                        inputLabel: 'Enter Your email address to recieve a token',
                        inputPlaceholder: 'Enter your Dunster house email address',
                        confirmButtonText: 'Send verification code',
                  }).then((result) => {
                        if (result.isConfirmed) {
                              if (this.controller) this.controller.abort();
                              this.controller = new AbortController();
                              const signal = this.controller.signal
                              
                              this.email=result.value;
                              //alert(this.email);
                              axios.post('/api/send-email',
                                  {email:result.value},
                                  { signal }
                              ).then(({data})=>{
                                    console.log(data);
                                    // if(data.error!=''){
                                    //       Swal.fire('Verification failed', data.error, 'info') 
                                    // }
                                    if(data.error=='')
                                    {
                                          Swal.fire({
                                                backdrop:true,
                                                allowOutsideClick: false,
                                                title: 'Enter OTP/Token',
                                                input: 'text',
                                                inputLabel: 'OTP/Token(sent to your email)',
                                                confirmButtonText: 'Validate',
                                                
                                                inputValidator: (value) => {
                                                      console.log(this.attempt)
                                                      if (!value) {
                                                            return 'Token is missing!'
                                                      }else if(value!=data.token){

                                                            this.attempt++;
                                                            
                                                            console.log(this.attempt)
                                                            if(this.attempt==3)
                                                            {
                                                                  this.attempt=0
                                                                  Swal.fire('Verification failed', 'Token expired,please request for new token', 'info') 
                                                            }
                                                            else{
                                                                  if( this.attempt==1)
                                                                  {
                                                                        return `Token failed!Please try again ... 2 attempts left`
                                                                  }
                                                                  if( this.attempt==2)
                                                                  {
                                                                        return `Token failed!Please try again ... 1 attempt left`
                                                                  }
                                                                  
                                                            }   
                                                      }
                                                }
                                          }).then((result) => {
                                                      if (result.isConfirmed) {
                                                            //alert(data.token);
                                                            if(result.value==data.token){
                                                                  Swal.fire({
                                                                        backdrop:true,
                                                                        allowOutsideClick: false,
                                                                        title:'Reset Password',
                                                                        html:`<input type="password" id="newPwd" class="swal2-input" placeholder="New password">
                                                                        <input type="password" id="confirmPwd" class="swal2-input" placeholder="Confirm password">`,
                                                                        confirmButtonText: 'Confirm',
                                                                       
                                                                        customClass: {
                                                                              validationMessage: 'my-validation-message'
                                                                        },
      
      
      
                                                                        preConfirm: () => {
                                                                            
                                                                              const newPwd = Swal.getPopup().querySelector('#newPwd').value
                                                                              const confirmPwd = Swal.getPopup().querySelector('#confirmPwd').value
                                                                              
                                                                              if (!newPwd)
                                                                                    Swal.showValidationMessage('<span class="material-symbols-outlined">Please enter new password</span> ')
                                                                              if(!confirmPwd)
                                                                                    Swal.showValidationMessage('<span class="material-symbols-outlined">Please enter confirm password</span> ')
                                                                              if(md5(newPwd)=='e018efd594c8118206bce4a4e906d5e5')
                                                                                    Swal.showValidationMessage('<span class="material-symbols-outlined">New password cannot be same as default password .</span> ')
                                                                              if(newPwd!=confirmPwd)
                                                                                    Swal.showValidationMessage('<span class="material-symbols-outlined">Your password and confirmation password do not match.</span> ')  
                                                                                
                                                                              return {newPwd: newPwd, confirmPwd: confirmPwd }
                                                                        }
                                                                  
                                                                  }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                              if (this.controller) this.controller.abort();
                                                                              this.controller = new AbortController();
                                                                              const signal = this.controller.signal
                                                                              //alert(this.email)
                                                                              axios.post('/api/reset-pwd',
                                                                                  {email:this.email,
                                                                                  password:result.value.newPwd},
                                                                                  { signal }
                                                                              ).then(({data})=>{
                                                                                    //console.log(data);
                                                                                    if(data.error=='')
                                                                                    Swal.fire({
                                                                                          title: "Password Changed Successfully",
                                                                                          text: "Please login using your New Password",
                                                                                          icon: "success"
                                                                                    }) 
                                                                                    else
      
                                                                                    Swal.fire({
                                                                                          title: "Password not saved",
                                                                                          text: "username may not exist",
                                                                                          icon: "error"
                                                                                    }) 
                                                                                        
                                                                              })
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
                                                            }
                                                            else{
                                                                 // alert(result.value);
                                                                // Swal.showValidationMessage('Token Failed')
                                                            }
                                                            
                                                      }
                                          })

                                    }
                                    else{
                                          Swal.fire('Email Does Not Exist', data.error, 'info')
                                    }
                                         
                              })
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




      async checkLogin(username,password){

                this.userName =   username;
                this.userPassword = password;
              
                if (this.controller) this.controller.abort();
                this.controller = new AbortController();
                const signal = this.controller.signal
                  await axios.post('/api/login',{
                        username,
                        password
                      },{signal}).then(({data})=>{
                        //console.log(data);
                        // console.log(data['error_msg']);
                        if(data.error_msg==''){
                              this.login_pwd=data.user_details[0].password;
                              if(data.login_details[0].status=='valid' 
                              && data.login_details[0].logged_status=='true'
                              && data.user_details[0].user_name==this.userName
                              && data.user_details[0].password!='e018efd594c8118206bce4a4e906d5e5'){// e018efd594c8118206bce4a4e906d5e5' default pass
                                    this.role=data.login_details[0].role;
                                    this.logged_status=data.login_details[0].logged_status;
                                    this.admin_user_id=data.login_details[0].userid;
                                    this.profileData = data.user_details[0];
                                    const inputData = input();
                                    const searchData = search();
                                    VueCookieNext.setCookie('selected_branch', data.user_details[0].selected_branch, { path: '/' });
                                    VueCookieNext.setCookie('selected_date', new Date().toISOString().slice(0,10), { path: '/' });
                                    inputData.setBranchValue();
                                    VueCookieNext.setCookie('branch_list', JSON.stringify(data.branch_list), { path: '/' });
                                    inputData.setBranches();
                                   // alert(inputData.selectedBranch);
                                    searchData.SearchRequestedData(inputData.selectedBranch,inputData.selectedDate)
                                    
                                    
                              }
                              /*conditon to change default password*/
                              else if(data.login_details[0].status=='valid' 
                              && data.login_details[0].logged_status=='true'
                              && data.user_details[0].user_name==this.userName
                              && data.user_details[0].password=='e018efd594c8118206bce4a4e906d5e5'){
                                    Swal.fire({
                                          title:'Change Password',
                                          html:`<input type="password" id="newPwd" class="swal2-input" placeholder="New password">
                                          <input type="password" id="confirmPwd" class="swal2-input" placeholder="Confirm password">`,
                                          confirmButtonText: 'Confirm',
                                          focusConfirm: false,
                                          showCloseButton: true,
                                          preConfirm: () => {
                                                const newPwd = Swal.getPopup().querySelector('#newPwd').value
                                                const confirmPwd = Swal.getPopup().querySelector('#confirmPwd').value
                                                if (!newPwd)
                                                      Swal.showValidationMessage('Please enter new password')
                                                if(!confirmPwd)
                                                      Swal.showValidationMessage('Please enter confirm password')
                                                if(newPwd!=confirmPwd)
                                                      Swal.showValidationMessage('Your password and confirmation password do not match.')  
                                                if(md5(newPwd)==data.user_details[0].password)
                                                      Swal.showValidationMessage('Your new password cannot be same as current password')   
                                                return { newPwd: newPwd, confirmPwd: confirmPwd }
                                          }
                                    }).then((result) => {
                                          if (result.isConfirmed) {
                                                //console.log(data.user_details[0].admin_user_id);
                                                axios.post('/api/change-pwd',{
                                                    admin_user_id:data.user_details[0].admin_user_id,
                                                    password:result.value.newPwd,
                                                }).then((response)=>{
                                                      //console.log(response);
                                                      if(response.data!='')
                                                            Swal.fire('Password updated successfully.');
                                                      else
                                                            Swal.fire('Changes are not saved', 'Error', 'info')
                                                })
                                          }
                                     })
                              }
                              else{
                                    Swal.fire({
                                          icon: 'error',
                                          title: 'Oops...',
                                          text: 'User credentials are invalid!',
                                    })
                              }
                        }
                        else{
                              Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'User credentials are invalid!',
                                  })
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
            }
      },

      getters:{
           
      },
      persist:true
  
})
