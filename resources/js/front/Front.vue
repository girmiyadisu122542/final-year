<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue';
</script>
<template>
    
    <Header/>
<!--====== Header End ======-->
   <RouterView></RouterView>

<!-- Footer section start -->
 <Footer/>
<!-- Footer section End -->
</template>
