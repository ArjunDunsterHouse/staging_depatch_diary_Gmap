<template>

<!-- <div style="position:fixed;"> -->
	<aside :class="`${toggle.is_expanded? 'is-expanded' : ''}`">
		

		<div class="menu-toggle-wrap">
			<button class="menu-toggle" @click="ToggleMenu">
				<span class="material-icons">keyboard_double_arrow_right</span>
			</button>
		</div>

		<h3>Menu</h3>
		<div class="menu">



                  
			<!-- <router-link to="/" class="button">
				<span class="material-icons">home</span>
				<span class="text">log out</span>
			</router-link> -->

			<router-link :to="{ path: '/dashboard' }" class="button">
				<span class="material-icons" @click="DashboardloadPage">home</span>
				<span class="text">Dashboard</span>
			</router-link>
			
			<router-link :to="{ path: '/profile' }" class="button" title="Your Profile">
				<span class="material-icons" @click="loadPage">person</span>
				<span class="text">Your Profile</span>
			</router-link>
			    <br>
				
			<div id="Admin" v-if="userLogin.role=='admin' || userLogin.role=='despatch_manager' || userLogin.role=='marketing_edit'">
				<h3 style="padding-left: 5%;">Admin Panel</h3>
			<router-link :to="{ path: '/vehicle' }" class="button" title="Vehicles List">
				<span class="material-icons" @click="loadPage">directions_car</span>
				<span class="text">Vehicles List</span>
			</router-link>

			<router-link :to="{ path: '/users' }" class="button" title="Users Profile">
				<span class="material-icons" @click="loadPage">group</span>
				<span class="text">Users Profile</span>
			</router-link>
			<router-link :to="{ path: '/branch' }" class="button" title="DH Branch Details">
				<!-- <span class="material-icons">business</span> -->
				<span class="material-icons" @click="loadPage">location_city</span>
				<span class="text">DH Branch Details</span>
			</router-link>
			<!-- <router-link :to="{ path: '/test' }" class="button">
				
				<span class="material-icons" @click="loadPage">build</span>
				<span class="text">tabs testing</span>
			</router-link> -->
		</div>
		</div>

		<div class="flex"></div>

	</aside>
<!-- </div>	 -->
</template>
 
<script setup>
	
	//import { useRoute } from 'vue-router';
	import { sidebar } from '../store/sidebar.js'
	import {SignIn} from '../store/login.js'

	import { search } from '../store/searchData.js'
	
	
	const searchSelectedData = search();

	const toggle = sidebar();

	const userLogin = SignIn();

	

	// const route = useRoute();
	// const User = route.query;

	

	function ToggleMenu(){
		toggle.is_expanded = !toggle.is_expanded;
	}


	const loadPage = ()=>{

		//const $loading = useLoading()
		
		//const loader = $loading.show({
						// Optional parameters
					//	color: '#5D2296',
					//	loader: 'dots',
					//});
						// simulate AJAX
				//	setTimeout(() => {
					//	loader.hide()
					//}, 100)
		}

		const DashboardloadPage = ()=>{

			//const $loading = useLoading()
			// console.log("Dashboard load");
			// console.log(searchSelectedData.selectedBranch);
			// console.log(searchSelectedData.submitStatus);
			// if(searchSelectedData.submitStatus!=true){
			// 	console.log("Not clicked");
			// 	searchSelectedData.selectedBranch =searchSelectedData.previousBranch;
			// 	console.log(searchSelectedData.selectedBranch);
			// }
			
			// searchSelectedData.selectedBranch =1;
			// searchSelectedData.selectedDate=new Date().toISOString().slice(0,10);

			//const loader = $loading.show({
						// Optional parameters
						//color: '#5D2296',
						//loader: 'dots',
					//});
						// simulate AJAX
					//setTimeout(() => {
					//	loader.hide()
					//}, 2000)

		}

</script>
 
<style lang="scss" scoped>

	aside {
		display: flex;
		flex-direction: column;
		background-color: var(--dark);
		color: var(--light);
		width: calc(2rem + 32px);
		overflow: hidden;
		min-height: 100vh;
		padding: 1rem;
		transition: 0.2s ease-in-out;
		padding-top:100px;
		.flex {
			flex: 1 1 0%;
		}
		
		.menu-toggle-wrap {
			display: flex;
			justify-content: flex-end;
			margin-bottom: 1rem;
			position: relative;
			top: 0;
			transition: 0.2s ease-in-out;
			.menu-toggle {
				transition: 0.2s ease-in-out;
				.material-icons {
					font-size: 2rem;
					color: var(--light);
					transition: 0.2s ease-out;
				}
				
				&:hover {
					.material-icons {
						color: var(--primary);
						transform: translateX(0.5rem);
					}
				}
			}
		}
		h3, .button .text {
			opacity: 0;
			transition: opacity 0.3s ease-in-out;
		}
		h3 {
			color: var(--grey);
			font-size: 0.875rem;
			margin-bottom: 0.5rem;
			text-transform: uppercase;
		}
		.menu {
			margin: 0 -1rem;
		
			.button {
				display: flex;
				align-items: center;
				text-decoration: none;
				transition: 0.2s ease-in-out;
				padding: 0.5rem 1rem;
				.material-icons {
					font-size: 2rem;
					color: var(--light);
					transition: 0.2s ease-in-out;
				}
				.text {
					color: var(--light);
					transition: 0.2s ease-in-out;
				}
				&:hover {
					background-color: var(--dark-alt);
					.material-icons, .text {
						color: var(--primary);
					}
				}
				&.router-link-exact-active {
					background-color: var(--dark-alt);
					border-right: 5px solid var(--primary);
					.material-icons, .text {
						color: var(--primary);
					}
				}
			}
		}
		.footer {
			opacity: 0;
			transition: opacity 0.3s ease-in-out;
			p {
				font-size: 0.875rem;
				color: var(--grey);
			}
		}
		&.is-expanded {
			width: var(--sidebar-width);
			.menu-toggle-wrap {
				top: -3rem;
				
				.menu-toggle {
					transform: rotate(-180deg);
				}
			}
			h3, .button .text {
				opacity: 1;
			}
			.button {
				.material-icons {
					margin-right: 1rem;
				}
			}
			.footer {
				opacity: 0;
			}
		}
		@media (max-width: 1024px) {
			position: absolute;
			z-index: 99;
		}
	}
</style>