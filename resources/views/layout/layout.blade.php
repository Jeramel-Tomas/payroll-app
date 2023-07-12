<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payroll</title>

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/css/bootstrap.css')}}">

	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/iconly/bold.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/fontawesome/all.min.css')}}">

	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/css/app.css')}}">
	<link rel="shortcut icon" href="{{ URL::asset('new-assets/assets/images/favicon.svg')}}" type="image/x-icon">
	@stack('css-imports')
</head>

<body>
	<div id="app">
		<div id="sidebar" class="active">
			<div class="sidebar-wrapper active">
				<div class="sidebar-header">
					<div class="d-flex justify-content-between">
						<div class="logo">
							Company Name
						</div>
						<div class="toggler">
							<a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
						</div>
					</div>
				</div>
				<div class="sidebar-menu">
					<ul class="menu">
						<li class="sidebar-title">Menu</li>

						<li class="sidebar-item {{ (request()->is('/')) ? 'active' : ''}}">
							<a href="{{ route('dashboard') }}" class='sidebar-link'>
								<i class="bi bi-grid-fill"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<li class="sidebar-item  has-sub {{ (request()->is('employees*')) ? 'active' : ''}}">
							<a href="#" class='sidebar-link'>
								<i class="bi bi-stack"></i>
								<span>Manage Employees</span>
							</a>
							<ul class="submenu {{ (request()->is('employees*')) ? 'active' : ''}}">
								<li class="submenu-item ">
									<a href="{{ route('employees.list') }}">Employees</a>
								</li>
								<li class="submenu-item ">
									<a href="{{ route('employees.create') }}">Create</a>
								</li>
							</ul>
						</li>

						<li class="sidebar-item  has-sub {{ (request()->is('attendance*')) ? 'active' : ''}}">
							<a href="#" class='sidebar-link'>
								<i class="bi bi-collection-fill"></i>
								<span>Attendance Log</span>
							</a>
							<ul class="submenu {{ (request()->is('attendance*')) ? 'active' : ''}}">
								<li class="submenu-item ">
									<a href="{{ route('attendance.log.index') }}">All</a>
									@stack('sites-leftside-menu')
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
			</div>
		</div>
		<div id="main">
			<header class="mb-3">
				<a href="#" class="burger-btn d-block d-xl-none">
					<i class="bi bi-justify fs-3"></i>
				</a>
			</header>

			<div class="page-heading">
				<section class="row">
					@yield('page-heading')
				</section>
			</div>

			<div class="page-content">
				@yield('page-content')
			</div>

			<footer>
				<div class="footer clearfix mb-0 text-muted">
					<div class="float-start">
						<p>2021 &copy; jeramel</p>
					</div>
				</div>
			</footer>
		</div>
	</div>
	<script src="{{ URL::asset('new-assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/bootstrap.bundle.min.js')}}"></script>

	<script src="{{ URL::asset('new-assets/assets/vendors/apexcharts/apexcharts.js')}}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/pages/dashboard.js')}}"></script>

	<script src="{{ URL::asset('new-assets/assets/js/main.js')}}"></script>
	@stack('js-imports')

	<script type="text/javascript">
		$(document).ready(function() {
			$('.submenu a').click(function(e) {
				e.stopPropagation();
			});
			@stack('jq-code')
		});
	</script>

</body>

</html>