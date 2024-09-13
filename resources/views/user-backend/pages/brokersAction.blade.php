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
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('') }}/storage/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ url('') }}/storage/assets/img/favicon.png">
    <title>
        Material Dashboard 2 by Creative Tim
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ url('') }}/storage/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ url('') }}/storage/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ url('') }}/storage/assets/css/material-dashboard.css?v=3.1.0"
        rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
    @component('components.sidenav', ['active' => 'brokersAction'])
    @endcomponent
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @component('components.navbar', ['active' => 'brokersAction'])
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
                                                <option value="{{ $broker->broker_name }}">
                                                    {{ ucfirst($broker->broker_name) }}</option>
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
                                        <option value="holdings">Get Holdings</option>
                                        <option value="accounts">Accounts</option>
                                    </select>
                                </div>


                                <!-- Additional Inputs for Buy/Sell -->
                                <div id="inputContainer" class="mb-3" style="display:none;">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            placeholder="Enter quantity" min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="symbol" class="form-label">Stock Symbol</label>
                                        <input type="text" class="form-control" id="symbol" name="symbol"
                                            placeholder="Enter stock symbol">
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label mt-3">Price</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            placeholder="Enter price" step="0.01" min="0">
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
            var ws;
            @inject('userToken', 'App\Models\UserToken')
            @php
                $token = $userToken->generateToken();
            @endphp
            var userToken = `{!! $token !!}`;

            function connectSocket() {
                ws = new WebSocket('ws://localhost:8080');

                ws.onopen = function () {
                    console.log('Connected to WebSocket server');
                    ws.send(JSON.stringify({
                        login: userToken
                    }));
                };
                ws.onmessage = function (event) {
                    console.log('Message from server:', event.data);

                    try {
                        var data = JSON.parse(event.data);
                        if (data.request === "SMS" && data.for && data.broker) {
                            lightbox=document.getElementById("lightbox");
                            if(lightbox){lightbox.remove()}
                            askForSMS(data.broker,data.for);
                        } else {
                            lightbox=document.getElementById("lightbox");
                            if(lightbox){lightbox.remove()}
                            alert('Message from server:'+ event.data);
                        }
                    } catch (error) {
                        console.log("Error parsing JSON:", error);
                    }
                };

                ws.onclose = function () {
                    console.log('WebSocket connection closed');
                };
            }

            function askForSMS(broker,user) {
                var lightbox = document.createElement('div');
                lightbox.style.position = 'fixed';
                lightbox.style.top = '0';
                lightbox.style.left = '0';
                lightbox.style.width = '100%';
                lightbox.style.height = '100%';
                lightbox.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                lightbox.style.display = 'flex';
                lightbox.style.justifyContent = 'center';
                lightbox.style.alignItems = 'center';
                lightbox.style.zIndex = '1000';
                lightbox.setAttribute("id","lightbox")

                var lightboxContent = document.createElement('div');
                lightboxContent.style.backgroundColor = '#fff';
                lightboxContent.style.padding = '20px';
                lightboxContent.style.borderRadius = '5px';
                lightboxContent.style.textAlign = 'center';

                var inputLabel = document.createElement('label');
                inputLabel.innerText = 'Enter SMS Code:';
                lightboxContent.appendChild(inputLabel);

                var inputField = document.createElement('input');
                inputField.type = 'text';
                inputField.style.margin = '10px 0';
                inputField.style.padding = '5px';
                inputField.style.width = '100%';
                lightboxContent.appendChild(inputField);

                var submitButton = document.createElement('button');
                submitButton.innerText = 'Submit';
                submitButton.style.marginTop = '10px';
                submitButton.style.padding = '10px 20px';
                lightboxContent.appendChild(submitButton);

                var closeButton = document.createElement('button');
                closeButton.innerText = 'close';
                closeButton.style.marginTop = '10px';
                closeButton.style.padding = '10px 20px';
                lightboxContent.appendChild(closeButton);
                closeButton.addEventListener('click', function() {document.getElementById("lightbox").remove();});
                lightbox.appendChild(lightboxContent);
                document.body.appendChild(lightbox);

                submitButton.addEventListener('click', function() {
                    var smsCode = inputField.value;
                    if (!smsCode) {
                        alert('Please enter the SMS code.');
                        return;
                    }
                    var token = document.querySelector('input[name="_token"]').value;
                    var smsData = new FormData();
                    smsData.append('_token', token);
                    smsData.append('broker', broker);
                    smsData.append('for', user);
                    smsData.append('sms_code', smsCode);

                    fetch('{{ route('verify_sms') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: smsData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.body.removeChild(lightbox);
                        }
                    });
                });
                return lightbox;
            }

            // WebSocket connection setup
            var login = false;
            if (!login) {
                connectSocket();
                login = true;
            }

            document.querySelector('#actionForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this);
                var token = document.querySelector('input[name="_token"]').value;
                formData.append('user_token', userToken);

                // Start the fetch request asynchronously
                fetch('{{ route('do_action') }}', {
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
                    // alert('Action completed: ' + JSON.stringify(data)); // Alert the result
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });

            // Handle input visibility for buy/sell
            document.getElementById('action').addEventListener('change', function() {
                var inputContainer = document.getElementById('inputContainer');

                if (this.value === 'buy' || this.value === 'sell') {
                    var inputs = document.querySelectorAll('#inputContainer input');
                    inputs.forEach(element => {
                        if(element.getAttribute("name")=="price")element.removeAttribute('required');return;
                        element.setAttribute('required', "true");
                    });
                    inputContainer.style.display = 'block';
                } else {
                    var inputs = document.querySelectorAll('#inputContainer input');
                    inputs.forEach(element => {
                        element.removeAttribute('required');
                        if (element.getAttribute("type") == "number") {
                            element.value = 0;
                        } else {
                            element.value = "";
                        }
                    });
                    inputContainer.style.display = 'none';
                }
            });
        </script>





        <!--   Core JS Files   -->
        <script src="{{ url('') }}/storage/assets/js/core/popper.min.js"></script>
        <script src="{{ url('') }}/storage/assets/js/core/bootstrap.min.js"></script>
        <script src="{{ url('') }}/storage/assets/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="{{ url('') }}/storage/assets/js/plugins/smooth-scrollbar.min.js"></script>
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
        <script src="{{ url('') }}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
