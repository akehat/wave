


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
<!-- End Navbar -->

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card shadow-lg">
          <div class="card-header bg-gradient-secondary text-white text-center py-4">
            <h3 class="text-warning">How to Use the Brokers Feature</h3>
          </div>
          <div class="card-body p-4">
            <h4>1. Define Broker Information</h4>
            <p class="h6">Navigate to the <strong class="text-warning">Brokers Definition</strong> tab. Here, you will see a list of supported brokers. Select the broker you want to set up, and enter the required information, such as:</p>
            <ul>
              <li class="h6">Username</li>
              <li class="h6">Password</li>
              <li class="h6">Token</li>
              <li class="h6">Other specific credentials (e.g., PIN, 2FA)</li>
            </ul>
            <p class="h6">Each broker has different requirements, so make sure to fill out all fields provided. Once completed, toggle the <strong class="text-warning">Enabled</strong> button to activate this broker for trading.</p>

            <h4>2. Enable the Broker</h4>
            <p class="h6">After filling in the necessary information, ensure the broker is enabled. Simply click the toggle button labeled <strong class="text-warning">Enabled</strong>. This step allows the broker to be available for actions on the Broker Action tab.</p>

            <h4>3. Perform Broker Actions</h4>
            <p class="h6">Next, go to the <strong class="text-warning">Broker Action</strong> tab to interact with your selected broker. It is important to follow these steps in order:</p>

            <h5>Check Holdings</h5>
            <p class="h6">Click on the <strong class="text-warning">Get Holdings</strong> button to retrieve your current holdings with this broker. This data will provide you with an overview of your assets and positions before making further actions.</p>

            <h5>Check Accounts</h5>
            <p class="h6">After checking your holdings, click on <strong class="text-warning">Get Accounts</strong> to retrieve your account information. This will confirm account status, balance, and other relevant details for trading.</p>

            <h5>Make Trades</h5>
            <p class="h6">With holdings and account information verified, you are ready to trade. Use the <strong class="text-warning">Buy</strong> and <strong class="text-warning">Sell</strong> buttons to execute your trades. Ensure you are aware of market conditions and available funds before proceeding with any trade actions.</p>

            <h4>Summary</h4>
            <p class="h6">In summary, to trade with any broker on this platform:</p>
            <ol>
              <li class="h6">Go to <strong class="text-warning">Brokers Definition</strong> and fill in the broker’s information.</li>
              <li class="h6">Toggle the <strong class="text-warning">Enabled</strong> button for that broker.</li>
              <li class="h6">Go to <strong class="text-warning">Broker Action</strong> and click <strong class="text-warning">Get Holdings</strong>, then <strong class="text-warning">Get Accounts</strong>.</li>
              <li class="h6">Use the <strong class="text-warning">Buy</strong> and <strong class="text-warning">Sell</strong> buttons to make trades.</li>
            </ol>

            <div class="text-center mt-4">
              <button class="btn btn-primary" onclick="window.location.href='{{url("")}}/pages/brokersDefinition'">Go to Brokers Definition</button>
              <button class="btn btn-primary" onclick="window.location.href='{{url("")}}/pages/brokersAction'">Go to Broker Actions</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


                <footer class="footer py-4  ">
  <div class="container-fluid">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="copyright text-center text-sm text-muted text-lg-start">
          © <script>
            document.write(new Date().getFullYear())
          </script>,
          made with <i class="fa fa-heart"></i> by
          <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
          for a better web.
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          <li class="nav-item">
            <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
          </li>
          <li class="nav-item">
            <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>

            </div>


       </main>



          <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p class="h6">See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-icons">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>

        <!-- Sidenav Type -->

        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>

        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>

        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>


        <!-- Navbar Fixed -->

        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>



        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">


        <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-pro">Free Download</a>


        <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard">View documentation</a>

        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>

          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>



          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>

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
