import { defineStore } from 'pinia'
import axios from "axios";
import routes from '../router/index.js';
import Swal from 'sweetalert2';
import md5 from 'md5';

export const profile = defineStore(
      {
            id:  'profile',

      state: () => ({

            emailText:'please enter your email text here'
         
      }),

      actions:{
            requestProfileEdit(currentPassword){

                  Swal.fire({
                        title:'Request Profile Edit',
                        html:`<textarea id="profileEdit" name="profileEdit" rows="10" cols="48" placeholder="please enter your email text here"> </textarea>
                        send email to dispatch@dunsterhouse.co.uk using your Outlook`,
                        confirmButtonText: "Copy" ,
                        showCloseButton: true,
                        preConfirm: () => {
                              this.emailText = Swal.getPopup().querySelector('#profileEdit').value

                              if(!this.emailText){
                                    Swal.showValidationMessage('Please type your text here')
                              }
                              
                              return { EmailText :this.emailText}
                        }
                  }).then((result) => {
                         if (result.isConfirmed) {
                              this.copyEmail();
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

            passwordChange(loginPassword,userid){

                  Swal.fire({
                        title:'Change Password',
                        html:`<input type="password" id="oldPwd" class="swal2-input" placeholder="Old Password">
                              <input type="password" id="newPwd" class="swal2-input" placeholder="New password">
                              <input type="password" id="confirmPwd" class="swal2-input" placeholder="Confirm password">`,
                        confirmButtonText: 'Confirm',
                        focusConfirm: false,
                        showCloseButton: true,
                        preConfirm: () => {
                              const oldPwd = Swal.getPopup().querySelector('#oldPwd').value
                              const newPwd = Swal.getPopup().querySelector('#newPwd').value
                              const confirmPwd = Swal.getPopup().querySelector('#confirmPwd').value
                              //console.log(md5(newPwd));
                              if(loginPassword!=md5(oldPwd))
                                    Swal.showValidationMessage('Current password is incorrect.')  
                              if (!newPwd)
                                    Swal.showValidationMessage('Please enter new password')
                              if(!confirmPwd)
                                    Swal.showValidationMessage('Please enter confirm password')
                              if(md5(newPwd)=='e018efd594c8118206bce4a4e906d5e5')
                                    Swal.showValidationMessage('New password cannot be same as default password .')  
                              if(newPwd!=confirmPwd)
                                    Swal.showValidationMessage('Your password and confirmation password do not match.')  
                              if(md5(newPwd)==loginPassword)
                                    Swal.showValidationMessage('Your new password cannot be same as current password')   
                              return { newPwd: newPwd, confirmPwd: confirmPwd }
                        }
                  }).then((result) => {

                         if (result.isConfirmed) {
                              //alert(userid); 
                              console.log("profile data");
                  console.log(this.controller);
                              if (this.controller) this.controller.abort();
                              this.controller = new AbortController();
                              const signal = this.controller.signal
                              axios.post('/api/change-pwd',{
                                    admin_user_id:userid,
                                    password:result.value.newPwd,
                                },{signal}).then((response)=>{
                                      //console.log(response);
                                      if(response.data!='')
                                            Swal.fire('Password updated successfully.');
                                      else
                                            Swal.fire('Changes are not saved', 'Error', 'info')
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
            
            async copyEmail()
              {
                  //console.log("call");
                  this.copyToClipboard= this.emailText;
                  await navigator.clipboard.writeText(this.copyToClipboard);
                  swal({
                      title: "Copied",
                      icon: "success",
                  })
              },
      },
  
})
