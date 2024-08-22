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
    Material Dashboard 2 by Creative Tim
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

<body class="g-sidenav-show  bg-gray-200">
  @component('components.sidenav',["active"=>"brokersAction"])
  @endcomponent
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @component('components.navbar',["active"=>"brokersAction"])
        @endcomponent
    <!-- Brokers Form -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Brokers Action</h6>
                    </div>
                    <div class="card-body">
                        <form id="actionForm" action="{{ route('do_action') }}" method="POST">
                            @csrf
                            <!-- Select Broker -->
                            <div class="mb-3">
                                <label for="broker" class="form-label">Select Broker</label>
                                <select class="form-select" id="broker" name="broker" required>
                                    <option value="">Choose a broker...</option>
                                    @foreach ($brokers as $brokerKey => $broker)
                                        @if ($broker->enabled)
                                            <option value="{{ $broker->broker_name }}">{{ ucfirst($broker->broker_name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Select Action -->
                            <div class="mb-3">
                                <label for="action" class="form-label">Select Action</label>
                                <select class="form-select" id="action" name="action" required>
                                    <option value="">Choose an action...</option>
                                    <option value="buy">Buy</option>
                                    <option value="sell">Sell</option>
                                    <option value="get_holdings">Get Holdings</option>
                                </select>
                            </div>


                            <!-- Additional Inputs for Buy/Sell -->
                            <div id="inputContainer" class="mb-3" style="display:none;">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" min="1">
                                </div>
                                <div class="mb-3">
                                    <label for="symbol" class="form-label">Stock Symbol</label>
                                    <input type="text" class="form-control" id="symbol" name="symbol" placeholder="Enter stock symbol">
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label mt-3">Price</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" min="0">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#actionForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Prepare form data
            var formData = new FormData(this);
            var token = document.querySelector('input[name="_token"]').value;

            // AJAX request
            fetch('{{ route("do_action") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log the result to the console
                alert('Action completed: ' + JSON.stringify(data)); // Alert the result
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });

        // JavaScript to show/hide price and symbol input based on action
        document.getElementById('action').addEventListener('change', function() {
            var inputContainer = document.getElementById('inputContainer');

            if (this.value === 'buy' || this.value === 'sell') {
                inputs=document.querySelectorAll('#inputContainer input')
                inputs.array.forEach(element => {
                    element.setAttribute('required',"true");
                });
                inputContainer.style.display = 'block';
            } else {
                inputs=document.querySelectorAll('#inputContainer input');
                inputs.array.forEach(element => {
                    element.removeAttribute('required');

                    if(element.getAttribute("type")=="number"){element.value=0;}
                    else{element.value="";}
                });
                inputContainer.style.display = 'none';
            }
        });
    </script>




  <!--   Core JS Files   -->
  <script src="{{url('')}}/storage/assets/js/core/popper.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/core/bootstrap.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('')}}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
