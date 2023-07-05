<!-- the <router-view> tags is responsible for where 
    to show the rendered component based on the routes in routes.js file
    its only used once in file in app.vue
    and app.vue is not the home page, because here we use router-view here to tell view 
    that we will render our app here -->

	<template>


		<nav-bar v-if="$route.name !== 'Home'&& login.logged_status=='true'"></nav-bar>
	   
		   <div class="app">
			   
			   <!-- <side-bar v-if="$route.name !== 'Home'&& login.logged_status=='true'"></side-bar> -->
			   
			   <b-container fluid class="align-self-start overallLayout">
				   
				<!-- <b-container fluid class="align-self-start">    -->
				   <b-container fluid> 
					    
					   <!--:key="$route.fullPath" using to clear cache on route change-->

					   <!-- <router-view :key="$route.fullPath"></router-view> -->

					   <router-view></router-view>
				   
				   </b-container>
	   
			   </b-container>
		   </div>
			   
			   
			   
			   
		 
	   
	   </template>
		
	   <script setup>
	   
		   import navBar from './layouts/Navbar.vue';
		   import sideBar from "./layouts/Sidebar.vue"
		   import { SignIn } from './store/login.js'
		  
		   const login = SignIn();
		  
		   
		   
		</script>
	   
	   
	   
	   <!-- below css for all the project -->
		<style lang="scss">
			
	   /**scroll bar css**/

	   

	   html{
		width:66.4%;
	   }

	   .overallLayout{
		margin-left:-15px
	   }
		   :root {
			   
			   --primary-alt: #22c55e;
			   --grey: #64748b;
			   --dark: #1e293b;
			   --dark-alt: #334155;
			   --light: #f1f5f9;
			   --sidebar-width: 235px;
			   overflowX: scroll;
		   }
		   * {
			   margin: 0;
			   padding: 0;
			   box-sizing: border-box;
			   font-family: 'Fira sans', sans-serif;
			 
		   }
	   
		   button {
			   cursor: pointer;
			   appearance: none;
			   border: none;
			   outline: none;
			   background: none;
		   }
		   .app {
			   display: flex;
			   main {
				   flex: 1 1 0;
				   padding: 2rem;
				  
			   }
		   }
	   
		   
		   .navlinks{
			display:flex
		   }
		   .navlinks a{
			padding-left: 20px;
			color:#fff;
			text-decoration: none;
		   }
		   .navlinks a:hover{
			
			color:#0d66e4
		   }

		   .navlinks a:focus{//used to display active state of a tab in nav bar when clicked
			font-weight: bolder;
			color:#f695f1;
			font-size:16px;
		   }
		   
		   /* vehicle Accordian.vue file css*/
	   
			   .table-styles{
				   display:flex;
			   }
		   
			   .VehicleHeading{
		   
				   background-color:#1E293B;
				   width: 222.8%;
				   color:azure;
				   padding:5px;
				   border-radius:5px;
				   
			   }
		   .vanDetails{
				   font-size: smaller;
				   float:left;
		   }
		   .vanDetails2{
				font-size: smaller;
				   float:right;
		   }
		   
		   .display-table-inline{
			   width: 277.5%;
			   font-size:9.5px;
			   margin-top:-14px;
			   margin-left:-30px;
		   }
		   
		   .tablesRow > :not(caption) > * > * {
				   padding: 0.5px;
				   text-align: center;
		   }
		   .col-1{
				   width: 11.8%;
		   }
		   .col-2 {
				   width: 17%;
		   }
		   .card{
				   --bs-card-border-width:0px;
		   }
		   .button-style{
				   width:218.6%;
				   text-align:left;
		   }
		   #belowLayoutofTables{
				   width:279%;//192
				   margin-left: -29px;
		    }

		    #belowLayoutofTableComments{
			width:226%;
		    }


	  	/* Formkit submit button css*/


		[data-type="submit"] .formkit-input{ 
			//submit button css
			
			margin-left:204%
		}

		[data-message-type="ui"] {
			margin-left: 43%;
		}

		[data-type="button"] .formkit-input{
			//delete button in edit css
			background-color:red;
			padding-left: 31px;
			margin-left: 204%;
		}
		
		/* Formkit submit button css ends*/

		/*profile copy button css */

		.ProfileCopy{
			padding:10px;
			background-color: blueviolet; 
			width:10%; 
			margin-left:43.8%;
		}

		   /*Table DescriptionData.vue css*/
	   
		   .rowsize{
				   width:113%;
				   padding-left:14px;
				   font-size: 10.5px;
		   }
		   .belowContentWrapper{
	   
				border-left:1px solid black;				
		   }
	   
		   textarea{
				   resize: none;
		   }
	   
			   /*Table.vue css */
	   
	   
		   .tablesRow > thead > tr > th{
			   text-align: center;
		   }
	   
		   .tablesRow > thead{
			   font-size:11px;
		   }
		   .tablesRow{
			   width:105%;
			   border: 1px solid #1E293B;
	   
		   }
		   .tableHeading{
	   
			   background-color:#1E293B;
			   width:105%;
			   text-align: center;
			   color:azure;
			   padding:10px;
			   font-size: 10px;
		   }

		   .inlineLayout{
			box-sizing: content-box;
		   }
		   /*css for total weight and value */

		   #weightAndValue{
			font-weight: bolder;
			font-size: 18px;
			padding: 10px;
			color: #fff;
		   }
	   
		   /**nav bar css*/
	   
		   nav{
			   background-color:#5D2296;
			   color:azure;
			   width: 181.5%;
		   }
		   .navbar-brand,.nav-link,.navbar-toggler-icon {
			   
			   color: azure;
			   padding-left: 20px;
		   }
		   navbar-brand:focus{
			color:#f695f1;
		   }
		   #moveright{
			   padding-left:30%; 
			   float:right;
		   }
	   
		   #weekNumber{
			   list-style: none;
			   padding-left: 2%;
		   }
	   
		   /*search Data.vue css */
	   
		   .searchForm{
				 padding-top:80px;
			}
	   
		   .searchBtn{
			   padding-top:20px;
		   }
	   
		   #searchDropdown{
	   
			   font-size: 18px;
			   font-weight: 100;
			   padding:0px;
			   margin:0px;
		   }
		
		/*New order notification css*/

		#lastUpdatesHeader{
			cursor: pointer;
			line-height: 30px;
			text-align: center;
			width:400px;
			height:56px;
			margin-top:1px;
			background:yellow;
			// margin-left: 20%;
			margin-left: 16%;
			float:right;
			color:black;
			position: absolute;
    			z-index: 1;
		}
		.arrow_styles{
			padding-top:5px;font-size:30px;width:40px;
		}
		
	   /*media queries for responsive design */
	   

		   .printDiv{
			border: solid black;
    			border-width: 1px 1px 1px 1px;
			height: 21cm;
    			width: 20cm;
		   }

		   .screenLayoutsWidth{

			width:155%
		   }
		   .screenLayoutsWidth2{
			width:165%;
		   }

		@media(max-width: 1880px){
				
			
			html{
				width:100%;
			}
			
			.overallLayout{
				margin-left:-22px
			}

			.screenLayoutsWidth{

				width:104%
			}
			.screenLayoutsWidth2{
				width:103%;
			}
			.headingAdjust{
				width:30%
			}
			[data-type="submit"] .formkit-input{ 
			//submit button css
			
				margin-left:160%
			}

			[data-type="button"] .formkit-input{
				//delete button in edit css
				
				padding-left: 31px;
				margin-left: 160%;
			}
			#lastUpdatesHeader{
				width:342px;
				height:52px;
				margin-left:19%;
				font-size:12.1px;
				font-weight:bold;
			}

			.display-table-inline{
				width: 229%;
				font-size:9px;
				
				margin-left:-29px;
			}

			.rowsize{
				width:113%;
				font-size: 9.2px;
			}

			.tablesRow{					
				width:106%;			
			}
			.tableHeading{					
				width:106%;
				font-size: 9.5px;
			}
			#belowLayoutofTables{
				width:230.4%;
				margin-left: -29px;
			}
			
			#belowLayoutofTableComments{
				width:187.5%;
				font-size:11px;
				margin-left:-32px;
			}
			.navlinks a{
				padding-left: 20px;
				
			}
			nav{
				width: 181.5%;
			}

			.navbar-brand,.nav-link,.navbar-toggler-icon {
				padding-left: 20px;
				font-size:17px;
			}

			#moveright{
				padding-left:23%; 
				float:right;
			}
			#weightAndValue{
				font-size: smaller;
				padding: 7px;
			}

			.button-style {
				width: 181.3%
			}
			.VehicleHeading{

				width: 184.6%;
			}
			.loginLayout{
				width:55%;
			}

		    
		}
		   	
	   </style>
	   