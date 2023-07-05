<template>


                
  <span class="scrollToTop material-icons" @click="goTop" >arrow_circle_up</span> 
 <!-- <span class="scrollToTop" @scroll="scrollTest">{{scrol}}</span> -->



</template>

<script setup>
//import { onMounted } from 'vue'
const goTop=()=>{
window.scrollTo(0,0);
}
// let scrol = window.top.scrollY //getting current value of Y scroll

//  console.log("printing window.top.scrollY");
// console.log(window.top.scrollY);

</script>

<style scoped>
.scrollToTop {

position: fixed;
bottom: -15px;
z-index: 99;
font-size: 40px;
left:97%;
opacity: 0.6;
cursor: pointer;
padding: 15px;
}

#scrollToTop:hover {
color: #555;
} 


@media(max-width: 1880px){

.scrollToTop {
left:96%;
}

}

</style>