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

                    @endphp
                    <div class="card-body">
                        <form action="{{ route('save_brokers') }}" method="POST">
                            @csrf
                            <!-- Tradier -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Tradier</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="tradier_enabled" name="tradier_enabled" {{ isset($brokers['Tradier']) && $brokers['Tradier']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tradier_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="tradier_token" placeholder="Enter Token" value="{{ isset($brokers['Tradier']) ? $brokers['Tradier']->token : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Tradier']) ? ($brokers['Tradier']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Robinhood -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Robinhood</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="robinhood_enabled" name="robinhood_enabled" {{ isset($brokers['Robinhood']) && $brokers['Robinhood']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="robinhood_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="robinhood_username" placeholder="Enter Username" value="{{ isset($brokers['Robinhood']) ? $brokers['Robinhood']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="robinhood_password" placeholder="Enter Password" value="{{ isset($brokers['Robinhood']) ? $brokers['Robinhood']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="robinhood_totp" placeholder="Enter TOTP (if 2FA enabled)" value="{{ isset($brokers['Robinhood']) ? $brokers['Robinhood']->totp : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Robinhood']) ? ($brokers['Robinhood']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Chase -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Chase</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="chase_enabled" name="chase_enabled" {{ isset($brokers['Chase']) && $brokers['Chase']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chase_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="chase_username" placeholder="Enter Username" value="{{ isset($brokers['Chase']) ? $brokers['Chase']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="chase_password" placeholder="Enter Password" value="{{ isset($brokers['Chase']) ? $brokers['Chase']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="chase_phone_last_four" placeholder="Enter Phone Last Four" value="{{ isset($brokers['Chase']) ? $brokers['Chase']->phone_last_four : '' }}">
                                <input type="text" class="form-control ms-3" name="chase_debug" placeholder="Debug (Optional)" value="{{ isset($brokers['Chase']) ? $brokers['Chase']->debug : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Chase']) ? ($brokers['Chase']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Fennel -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Fennel</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="fennel_enabled" name="fennel_enabled" {{ isset($brokers['Fennel']) && $brokers['Fennel']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fennel_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="fennel_email" placeholder="Enter Email" value="{{ isset($brokers['Fennel']) ? $brokers['Fennel']->email : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Fennel']) ? ($brokers['Fennel']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Fidelity -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Fidelity</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="fidelity_enabled" name="fidelity_enabled" {{ isset($brokers['Fidelity']) && $brokers['Fidelity']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fidelity_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="fidelity_username" placeholder="Enter Username" value="{{ isset($brokers['Fidelity']) ? $brokers['Fidelity']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="fidelity_password" placeholder="Enter Password" value="{{ isset($brokers['Fidelity']) ? $brokers['Fidelity']->password : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Fidelity']) ? ($brokers['Fidelity']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Firstrade -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Firstrade</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="firstrade_enabled" name="firstrade_enabled" {{ isset($brokers['Firstrade']) && $brokers['Firstrade']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="firstrade_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="firstrade_username" placeholder="Enter Username" value="{{ isset($brokers['Firstrade']) ? $brokers['Firstrade']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="firstrade_password" placeholder="Enter Password" value="{{ isset($brokers['Firstrade']) ? $brokers['Firstrade']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="firstrade_pin" placeholder="Enter PIN" value="{{ isset($brokers['Firstrade']) ? $brokers['Firstrade']->pin : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Firstrade']) ? ($brokers['Firstrade']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Public -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Public</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="public_enabled" name="public_enabled" {{ isset($brokers['Public']) && $brokers['Public']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="public_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="public_username" placeholder="Enter Username" value="{{ isset($brokers['Public']) ? $brokers['Public']->username : '' }}">
                                <input type="text" class="form-control ms-3" name="public_password" placeholder="Enter password" value="{{ isset($brokers['Public']) ? $brokers['Public']->password : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Public']) ? ($brokers['Public']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Schwab -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Schwab</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="schwab_enabled" name="schwab_enabled" {{ isset($brokers['Schwab']) && $brokers['Schwab']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="schwab_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="schwab_username" placeholder="Enter Username" value="{{ isset($brokers['Schwab']) ? $brokers['Schwab']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="schwab_password" placeholder="Enter Password" value="{{ isset($brokers['Schwab']) ? $brokers['Schwab']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="schwab_totp" placeholder="Enter totp" value="{{ isset($brokers['Schwab']) ? $brokers['Schwab']->totp : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Schwab']) ? ($brokers['Schwab']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Tradier -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Tastytrade</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="tastytrade_enabled" name="tastytrade_enabled" {{ isset($brokers['Tastytrade']) && $brokers['Tastytrade']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tastytrade_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="tastytrade_username" placeholder="Enter Username" value="{{ isset($brokers['Tastytrade']) ? $brokers['Tastytrade']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="tastytrade_password" placeholder="Enter Password" value="{{ isset($brokers['Tastytrade']) ? $brokers['Tastytrade']->password : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Tastytrade']) ? ($brokers['Tastytrade']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Vanguard -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Vanguard</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="vanguard_enabled" name="vanguard_enabled" {{ isset($brokers['Vanguard']) && $brokers['Vanguard']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vanguard_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="vanguard_username" placeholder="Enter Username" value="{{ isset($brokers['Vanguard']) ? $brokers['Vanguard']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="vanguard_password" placeholder="Enter Password" value="{{ isset($brokers['Vanguard']) ? $brokers['Vanguard']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="vanguard_phone_last_four" placeholder="Enter Phone Last Four" value="{{ isset($brokers['Vanguard']) ? $brokers['Vanguard']->phone_last_four : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Vanguard']) ? ($brokers['Vanguard']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                            <!-- Webull -->
                            <div class="d-flex mb-3">
                                <label class="form-label">Webull</label>
                                <div class="form-check form-switch ms-3">
                                    <input class="form-check-input" type="checkbox" id="webull_enabled" name="webull_enabled" {{ isset($brokers['Webull']) && $brokers['Webull']->enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="webull_enabled">Enable</label>
                                </div>
                                <input type="text" class="form-control ms-3" name="webull_username" placeholder="Enter Username" value="{{ isset($brokers['Webull']) ? $brokers['Webull']->username : '' }}">
                                <input type="password" class="form-control ms-3" name="webull_password" placeholder="Enter Password" value="{{ isset($brokers['Webull']) ? $brokers['Webull']->password : '' }}">
                                <input type="text" class="form-control ms-3" name="webull_did" placeholder="Enter did" value="{{ isset($brokers['Webull']) ? $brokers['Webull']->did : '' }}">
                                <input type="text" class="form-control ms-3" name="webull_trading_pin" placeholder="Enter Trading Pin" value="{{ isset($brokers['Webull']) ? $brokers['Webull']->trading_pin : '' }}">
                                <button type="submit" class="btn btn-primary ms-3 text-nowrap p-2 w-50" style="max-width:100px;">Save</button>
                                <span class="badge bg-secondary ms-3">{{ isset($brokers['Webull']) ? ($brokers['Webull']->confirmed?"confirmed":"unconfirmed") : 'unconfirmed' }}</span>
                            </div>

                        </form>
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
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('')}}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
