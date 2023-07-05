<template>

  <span class="scrollToTop material-icons" @click="goBottom">arrow_circle_down</span>
  
  
  </template>
   
  <script setup>
  
  const goBottom=()=>{
       
        //window.scrollTo(x,y);
        window.scrollTo(0,100000);//increase value if list increases
  
  }
  
  </script>
   
  <style scoped>
  .scrollToTop {
  
    position: fixed;
    top: 52px;
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