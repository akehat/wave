


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
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-lg">
          <div class="card-header bg-gradient-secondary text-white text-center py-4 ">
            <h3 class="text-warning">Contact Us</h3>
          </div>
          <div class="card-body p-4">
            <form action="/submit-contact" method="POST">
                @csrf
              <!-- Name Field -->
              <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>

              <!-- Email Field -->
              <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <!-- Subject Field -->
              <div class="form-group mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
              </div>

              <!-- Message Field -->
              <div class="form-group mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
              </div>

              <!-- Submit Button -->
              <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Send Message</button>
              </div>
            </form>
          </div>
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
