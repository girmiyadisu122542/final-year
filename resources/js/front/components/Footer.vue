<template>
      <section class="footer footer-3 pt-250">
	<div class="footer-mid">
		<div class="container">
			<div class="row">
				<div class="col-xl-3 col-sm-8 me-auto">
					<div class="widget footer-widget mb-5 mb-lg-0">
						<div class="footer-logo">
							<img src="static_image/wollo.jfif" alt="" width="100" height="100"  id="logo_image" class="img-fluid" />
						</div>
						
						<p class="mt-4">This system is a web based research and project repository for wollo university that stores different douments that are prepared by wollo univeristy community. </p>

						
					</div>
				</div>

				<div class="col-xl-2 col-sm-4">
					<div class="footer-widget mb-5 mb-lg-0">
						<h5 class="widget-title">Explore</h5>
						<ul class="list-unstyled footer-links">
							<li><RouterLink :to="{name:'About'}">About us</RouterLink></li>
							<li><RouterLink :to="{name:'Contact'}">Contact us</RouterLink></li>
							
						</ul>
					</div>
				</div>

				<div class="col-xl-2 col-sm-4">
					<div class="footer-widget mb-5 mb-lg-0">
						<h5 class="widget-title ">Programs</h5>
						<ul class="list-unstyled footer-links">
							<li><a href="#">Software Enginnering</a></li>
							<li><a href="#">Medicine</a></li>
							<li><a href="#">Law</a></li>
							<li><a href="#">Electrical Engineering</a></li>
						</ul>
					</div>
				</div>

				<div class="col-xl-2 col-sm-4">
					<div class="footer-widget mb-5 mb-lg-0">
						<h5 class="widget-title">Links</h5>
						<ul class="list-unstyled footer-links">
							<li><RouterLink :to="{name:'Index'}" >Home</RouterLink></li>
							<li><RouterLink :to="{name:'Document'}" >Documents</RouterLink></li>
							<li><RouterLink :to="{name:'Index'}" >Call for Research</RouterLink></li>
							<li><a href="/login" >Login</a></li>
						</ul>
					</div>
				</div>

				<div class="col-xl-2 col-sm-4">
					<div class="footer-widget mb-5 mb-lg-0">
						<h5 class="widget-title">Address</h5>
						<ul class="list-unstyled footer-links">
							<li><a href="#">+033 327 8534</a></li>
							<li><a href="#">info@wu.edu.et</a></li>
							<li><a href="#">Main Campus, Dessie 1145</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer-btm">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-xl-6 col-sm-12 col-lg-6">
					<p class="mb-0 copyright text-sm-center text-lg-start">© 2023 Software Engineering Department </p>
				</div>

			</div>
		</div>
	</div>

	<div class="fixed-btm-top">
		<a href="#" class="js-scroll-trigger scroll-to-top"><i class="fa fa-angle-up"></i></a>
	</div>
	
</section>
</template>
<script setup></script>