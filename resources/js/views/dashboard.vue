<template>
	<div>
		<!--<autoLogout v-if="login.logged_status=='true'"
		/>-->
		<ScrollToBottom
		/>
		
		<searchData
		/>
		<!-- <div style="color:#5D2296;">
			<h3 v-if="inputData.branchName==''">Data for: All Branches</h3>
			<h3 v-else> Data for: {{inputData.branchName}}</h3>
		</div> -->
		<br>
		
		<Collections
		:collections="searchSelectedData.getCollections"
		/>

		<Post
		:post="searchSelectedData.getPost"
		/>

		<Courier
		:courier="searchSelectedData.getCourier"
		/>
		
		<VehicalsAccordian v-if="inputData.selectedBranch!=9"
		/>

		<WeightAndValueInOneDay
		:weightAndValue="searchSelectedData.getWeightAndValueInAday"
		/>
		
		<br/><br/><br/>

		<ScrollToTop
		/>

		<br/><br/><br/><br/>


	</div>

</template>
<script setup>
	//import autoLogout from '../components/AutoLogout.vue';
	import searchData from '../components/SearchData.vue';
	import WeightAndValueInOneDay from '../components/DayWeightAndValue.vue';
	import VehicalsAccordian from '../components/VehicalsAccordian.vue';
	import Collections from '../components/Collections.vue';
	import Post from '../components/Post.vue';
	import Courier from '../components/Courier.vue';
	import ScrollToTop from '../components/ScrollToTop.vue';
	import ScrollToBottom from '../components/ScrollToBottom.vue';
	import { SignIn } from '../store/login.js'
	import { search } from '../store/searchData.js'
	import { VueCookieNext } from 'vue-cookie-next';
	import { input } from '../store/input.js'

	const inputData = input();

	const login = SignIn();
	const searchSelectedData = search();

	const currentDate = new Date().toISOString().slice(0,10);
	inputData.selectedBranch=VueCookieNext.getCookie('selected_branch');
    inputData.selectedDate=VueCookieNext.getCookie('selected_date');
	//inputData.branchName=VueCookieNext.getCookie('branchName');
	
	if(login.firstResponseCheck==true)
	{
		inputData.selectedBranch=VueCookieNext.getCookie('selected_branch');
            inputData.selectedDate=VueCookieNext.getCookie('selected_date');
		searchSelectedData.SearchRequestedData(inputData.selectedBranch,inputData.selectedDate);	
	}
	else{
		login.firstResponseCheck=true;

		searchSelectedData.firstRes=true;
	}	
	
</script>


 


