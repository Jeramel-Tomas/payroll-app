
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
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/dripicons/webfont.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/css/pages/dripicons.css')}}">

	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/toastify/toastify.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/css/app.css')}}">
	
	{{-- Custom css put all your custom css here --}}
	<link rel="stylesheet" href="{{ URL::asset('new-assets/assets/css/my-custom.css')}}">
	{{--
	<link rel="shortcut icon" href="{{ URL::asset('new-assets/assets/images/favicon.svg')}}" type="image/x-icon"> --}}
	{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
	
	@stack('css-imports')
	@livewireStyles
</head>

<body>
	<div id="app">
		<div id="sidebar" class="active">
			<div class="sidebar-wrapper active">
				<div class="sidebar-header">
					<div class="d-flex justify-content-between">
						<div class="logo">
							MGS
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

						<li class="sidebar-item  has-sub {{ (request()->is('employees/*')) ? 'active' : ''}}">
							<a href="#" class='sidebar-link'>
								<i class="bi bi-stack"></i>
								<span>Manage Employees</span>
							</a>
							<ul class="submenu {{ (request()->is('employees/*')) ? 'active' : ''}}">
								<li class="submenu-item {{ (request()->is('employees/list')) ? 'active' : ''}}">
									<a href="{{ route('employees.list') }}">Employees</a>
								</li>
								<li class="submenu-item {{ (request()->is('employees/create')) ? 'active' : ''}}">
									<a href="{{ route('employees.create') }}">Create</a>
								</li>
							</ul>
						</li>

						<li class="sidebar-item has-sub {{ (request()->is('attendance/*')) ? 'active' : ''}}">
							{{-- <a href="{{ route('attendance.log.index') }}" class='sidebar-link'> --}}
							<a href="#" class='sidebar-link'>
								<i class="bi bi-collection-fill"></i>
								<span>Manage Time Record</span>
							</a>
							<ul class="submenu {{ (request()->is('attendance/log')) ? 'active' : ''}}">
								<li class="submenu-item {{ (request()->is('attendance/log')) ? 'active' : ''}}">
									<a href="{{ route('attendance.log.index') }}">Daily Time Record</a>
								</li>
								{{-- <li class="submenu-item {{ (request()->is('attendance/log')) ? 'active' : ''}}"> --}}
								{{-- <li class="submenu-item">
									<a href="{{ route('attendance.sheet.index') }}">Attendance sheet</a>
								</li> --}}
							</ul>
						</li>

						<li class="sidebar-item has-sub {{ (request()->is('payroll/*')) ? 'active' : ''}}">
								<a href="#" class='sidebar-link'>
									<i class="bi bi-collection-fill"></i>
									<span>Manage Payroll</span>
								</a>
								<ul class="submenu {{ (request()->is('payroll/*')) ? 'active' : ''}}">
									<li class="submenu-item {{ (request()->is('payroll/employees')) ? 'active' : ''}}">
										<a href="{{ route('manage.payroll.index') }}">Payroll Settings</a>
									</li>
									<li class="submenu-item {{ (request()->is('payroll/cash-advanced')) ? 'active' : ''}}">
										<a href="{{ route('cash.advanced.index')}}">Cash advances</a>
									</li>
									<li class="submenu-item {{ (request()->is('payroll/generate')) ? 'active' : ''}}">
										<a href="{{ route('generate.payslip')}}">Generate Payslip</a>
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
				<section class="row text-center">
					@yield('page-heading')
				</section>
			</div>

			<div class="page-content ">
				@yield('page-content')
			</div>

			{{-- <footer>
				<div class="footer clearfix mb-0 text-muted">
					<div class="float-start">
						<p> &copy; </p>
					</div>
				</div>
			</footer> --}}
		</div>
	</div>
	
	@livewireScripts

	<script src="{{ URL::asset('new-assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/bootstrap.bundle.min.js')}}"></script>

	<script src="{{ URL::asset('new-assets/assets/vendors/apexcharts/apexcharts.js')}}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/pages/dashboard.js')}}"></script>

	<script src="{{ URL::asset('new-assets/assets/vendors/toastify/toastify.js') }}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/extensions/toastify.js') }}"></script>

	<script src="{{ URL::asset('new-assets/assets/js/main.js')}}"></script>
	<script src="{{ URL::asset('new-assets/assets/js/vendor.js')}}"></script>

	<script src="{{ URL::asset('new-assets/assets/js/jquery-3.3.1.min.js') }}"></script>
	{{-- @stack('js-imports') --}}


@stack('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			});
			// console.log('jquery is ready')		
			@stack('jq-code')
		});
		@stack('js-code')
		@stack('js-code-1')
	</script>
	
</body>

</html>