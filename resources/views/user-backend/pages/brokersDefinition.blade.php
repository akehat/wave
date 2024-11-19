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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        td {
            max-height: 50px; /* Maximum height of the cell */
            overflow: hidden; /* Hide overflow content */
            text-overflow: ellipsis; /* Show ellipsis if content overflows */
            white-space: nowrap; /* Prevent the text from wrapping to a new line */
            height: 50px; /* Force all <td> to be 50px in height */
            line-height: 50px; /* Align text vertically in the middle (optional) */
        }
        td:hover {
            max-height: none; /* Allow the height to expand */
            white-space: normal; /* Allow text to wrap */
            overflow: visible; /* Show overflowing content */
            line-height: normal; /* Reset the line height */
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
  @component('components.sidenav',["active"=>"brokersDefinition"])
  @endcomponent
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @component('components.navbar',["active"=>"brokersDefinition"])
        @endcomponent
    <!-- Brokers Form -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Brokers Configuration</h6>
                    </div>
                    @php
                    $hidden=[];
                    // dd($brokers);
                    function isBrokerDataEmpty($brokers, $name) {
                        // Check if the specified broker exists
                        if (!isset($brokers[$name])) {
                            return true; // If the broker doesn't exist, consider it empty
                        }

                        // Loop through each value in the broker data and check if it's empty
                        foreach ($brokers[$name]->toArray() as $key => $value) {
                            // Skip certain keys that don't impact emptiness
                            if (!in_array($key, ["broker_name", "user_id", "id", "created_at", "updated_at"])) {
                                if (!empty($value)) {
                                    return false; // If any value is not empty, return false
                                }
                            }
                        }

                        return true; // All values are empty
                    }
                        $brokerFields=["tradier"=>[["type"=>"text", "name"=>"tradier_token", "placeholder"=>"Enter Token", ]],
                        "robinhood"=>[["type"=>"text", "name"=>"robinhood_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"robinhood_password", "placeholder"=>"Enter Password"],
                        ["type"=>"text", "name"=>"robinhood_totp", "placeholder"=>"Enter TOTP (if 2FA enabled)"]] ,
                        "chase"=>[["type"=>"text", "name"=>"chase_username", "placeholder"=>"Enter Username", ],
                        ["type"=>"password", "name"=>"chase_password", "placeholder"=>"Enter Password",],
                        ["type"=>"text", "name"=>"chase_phone_last_four", "placeholder"=>"Enter Phone Last Four",],
                        ["type"=>"text", "name"=>"chase_debug", "placeholder"=>"Debug (Optional)"]],
                        "fennel"=>[["type"=>"text", "name"=>"fennel_email", "placeholder"=>"Enter Email"]],
                        "fidelity"=>[["type"=>"text", "name"=>"fidelity_username", "placeholder"=>"Enter Username",],
                        ["type"=>"password", "name"=>"fidelity_password", "placeholder"=>"Enter Password"]],
                        "firstrade"=>[["type"=>"text", "name"=>"firstrade_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"firstrade_password", "placeholder"=>"Enter Password"],
                        ["type"=>"text", "name"=>"firstrade_pin", "placeholder"=>"Enter pin"]],
                        "public"=>[["type"=>"text", "name"=>"public_username", "placeholder"=>"Enter Username"],
                        ["type"=>"text", "name"=>"public_password", "placeholder"=>"Enter password"]],
                        "schwab"=>[["type"=>"text", "name"=>"schwab_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"schwab_password", "placeholder"=>"Enter Password"],
                        ["type"=>"text", "name"=>"schwab_totp", "placeholder"=>"Enter totp"]],
                        "tastytrade"=>[["type"=>"text", "name"=>"tastytrade_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"tastytrade_password", "placeholder"=>"Enter Password"]],
                        "vanguard"=>[["type"=>"text", "name"=>"vanguard_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"vanguard_password", "placeholder"=>"Enter Password"],
                        ["type"=>"text", "name"=>"vanguard_phone_last_four", "placeholder"=>"Enter Phone Last Four"]],
                        "webull"=>[["type"=>"text", "name"=>"webull_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"webull_password", "placeholder"=>"Enter Password"],
                        ["type"=>"text", "name"=>"webull_did", "placeholder"=>"Enter did"],
                        ["type"=>"text", "name"=>"webull_trading_pin", "placeholder"=>"Enter Trading Pin"]],
                        "tornado"=>[["type"=>"text", "name"=>"tornado_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"tornado_password", "placeholder"=>"Enter Password"]],
                        "DSPAC"=>[["type"=>"text", "name"=>"DSPAC_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"DSPAC_password", "placeholder"=>"Enter Password"]]]
                    @endphp


                    <div class="card-body">
                        <form action="{{ route('save_brokers') }}" method="POST">
                            @csrf
                            <!-- Tradier -->
                            @foreach ($brokerFields as $brokerName => $fields)
                            @php
                                // Uppercase the first letter of the broker name
                                $displayName = ucfirst($brokerName);
                                $invisible = isBrokerDataEmpty($brokers,$displayName);
                                if($invisible){$hidden[] = $displayName;}
                            @endphp

                            <div class="card broker-card mb-3 p-3" @if($invisible) hidden @endif id="card{{$displayName}}">
                                <!-- Broker name and enable switch -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="form-label mb-0 h4">{{ $displayName }}</h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="{{ $brokerName }}_enabled" name="{{ $brokerName }}_enabled"
                                            {{ isset($brokers[$displayName]) && $brokers[$displayName]->enabled ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="{{ $brokerName }}_enabled">Enable</label>
                                    </div>
                                </div>

                                <!-- Input fields for the broker -->
                                @foreach ($fields as $field)
                                    @php
                                        $dbName = str_replace($brokerName . "_", "", $field['name']);
                                    @endphp

                                    <div class="input-group mb-2">
                                        <input type="{{ $field['type'] }}" class="form-control" name="{{ $field['name'] }}"
                                            placeholder="{{ $field['placeholder'] }}"
                                            value="{{ isset($brokers[$displayName]) && isset($brokers[$displayName]->$dbName) ? $brokers[$displayName]->$dbName : '' }}">
                                    </div>
                                @endforeach

                                <!-- Save button and confirmation badge -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary text-nowrap px-3" style="max-width: 100px;">Save</button>
                                    <span class="badge {{ isset($brokers[$displayName]) ? ($brokers[$displayName]->confirmed ? 'bg-primary' : 'bg-secondary') : 'bg-secondary' }}">
                                        {{ isset($brokers[$displayName]) ? ($brokers[$displayName]->confirmed ? 'Confirmed' : 'Unconfirmed') : 'Unconfirmed' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        </form>
                        <div>
                        <select id="brokerDropdown" class="form-select">
                            <option value="">Select a Broker</option>
                            @foreach ($hidden as $brokerName)
                                <option value="{{ $brokerName }}">{{ $brokerName }}</option>
                            @endforeach
                        </select>
                        <button type="button" id="addBrokerBtn" class="btn btn-primary mt-2">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



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
  <script>
    document.getElementById('addBrokerBtn').addEventListener('click', function() {
        // Get the selected broker from the dropdown
        var selectedBroker = document.getElementById('brokerDropdown').value;

        // Check if a broker is selected
        if (selectedBroker) {
            // Find the corresponding card element (e.g., cardChase, cardFirstrade, etc.)
            var card = document.getElementById('card' + selectedBroker);

            // If the card exists, remove the "hidden" attribute to make it visible
            if (card) {
                card.removeAttribute('hidden');
            }

            // Remove the selected option from the dropdown
            var dropdown = document.getElementById('brokerDropdown');
            var option = dropdown.querySelector('option[value="' + selectedBroker + '"]');
            if (option) {
                option.remove();
            }
            card.scrollIntoView({
                behavior: 'smooth'
            });
            // Clear the dropdown selection
            dropdown.value = '';
        } else {
            alert('Please select a broker first.');
        }
    });
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('')}}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
