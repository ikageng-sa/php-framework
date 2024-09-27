<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Template</title>

		<link rel="stylesheet" type="text/css" href="<?php echo assets('css/custom.css') ?>">

	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

		<!-- Font Awesome icons (free version)-->
	    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

	    <!-- Google fonts-->
	    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
	    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
	</head>

	<body data-bs-theme="dark">
		<!-- Navigation-->
		<nav class="navbar navbar-expand-lg" id="mainNav">
		    <div class="container">
		    	<a href="<?php echo route('home'); ?>" class="navbar-brand fs-1">...</a>
		        <button class="navbar-toggler text-bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		            Menu
		            <i class="fas fa-bars ms-1"></i>
		        </button>
		        <div class="collapse navbar-collapse" id="navbarResponsive">
		            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
		                <li class="nav-item">
		                	<a class="nav-link" href="<?php echo route('dashboard'); ?>">Home</a>
		                </li>
		                <li class="nav-item">
		                	<button class="nav-link text-uppercase" onclick="document.getElementById('logout').submit()">Logout</button>
		                	<form id='logout' action="<?php echo route('logout') ?>" method="post"></form>
		                </li>
		            </ul>
		        </div>
		    </div>
		</nav>


		<div class="app">
			@yield
		</div>

	</body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</html>