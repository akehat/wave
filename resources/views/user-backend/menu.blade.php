


<!--
=========================================================
* Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->


<!DOCTYPE html>
<html lang="en">
  <head>














<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="apple-touch-icon" sizes="76x76" href="{{url('')}}/storage/assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="{{url('')}}/storage/assets/img/favicon.png">

<title>

   Material Dashboard 2  by Creative Tim





</title>



<!--     Fonts and icons     -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

<!-- Nucleo Icons -->
<link href="{{url('')}}/storage/assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="{{url('')}}/storage/assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- CSS Files -->



<link id="pagestyle" href="{{url('')}}/storage/assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />





<!-- Nepcha Analytics (nepcha.com) -->
<!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>


  </head>


  <body class="g-sidenav-show  bg-gray-100">




 @component('components.sidenav')
 @endcomponent

      <main class="main-content border-radius-lg ">
        <!-- Navbar -->
@component('components.navbar',["active"=>"portal"])
@endcomponent
<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card shadow-lg">
          <div class="card-header bg-gradient-secondary text-white text-center py-4">
            <h3 class="text-warning">Navigation and Feature Guide</h3>
          </div>
          <div class="card-body p-4">
            <p class="h6">This page provides a quick overview of the available sections within the portal. Click on any button to navigate to the corresponding section.</p>

            <h4>General Pages</h4>
            <p class="h6">Access general information and settings across the portal.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{ route('general') }}'">General</button>

            <h4>Site Management</h4>
            <p class="h6">Navigate to manage overall site settings and configurations.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{ route('site') }}'">Site</button>

            <h4>Menu Configuration</h4>
            <p class="h6">Configure menu options and structure for easier navigation.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{ route('menu') }}'">Menu</button>

            <h4>Contact Support</h4>
            <p class="h6">Reach out to support for assistance with platform features.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{ route('contact') }}'">Contact</button>

            <h4>How-To Guide</h4>
            <p class="h6">View a step-by-step guide on how to use different portal features.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{ route('howto') }}'">How-To</button>

            <h4>Logout</h4>
            <p class="h6">Log out from your current session and exit the portal securely.</p>
            <button class="btn btn-danger btn-block my-2 w-100" onclick="window.location.href='{{ route('logout') }}'">Logout</button>

            <h4>Dashboard</h4>
            <p class="h6">Access your personal dashboard with a summary of key metrics and actions.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/dashboard'">Dashboard</button>

            <h4>Holdings</h4>
            <p class="h6">Review and manage your current holdings across all connected brokers.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/holdings'">Holdings</button>

            <h4>Broker Definition</h4>
            <p class="h6">Define and manage broker-specific information for trading access.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/brokersDefinition'">Brokers Definition</button>

            <h4>Broker Actions</h4>
            <p class="h6">Perform actions with your brokers, such as checking holdings, accounts, or executing trades.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/brokersAction'">Brokers Action</button>

            <h4>Billing</h4>
            <p class="h6">Manage billing information, view invoices, and update payment methods.</p>
            <button class="btn btn-primary btn-block my-2 w-100 " onclick="window.location.href='{{url("")}}/pages/billing'">Billing</button>

            <h4>Notifications</h4>
            <p class="h6">View and manage platform notifications and alerts.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/notifications'">Notifications</button>

            <h4>Profile</h4>
            <p class="h6">Update your personal profile information and account settings.</p>
            <button class="btn btn-primary btn-block my-2 w-100" onclick="window.location.href='{{url("")}}/pages/profile'">Profile</button>

          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>


<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc --><script src="{{url('')}}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
  </body>

</html>
