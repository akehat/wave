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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js">
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
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
    @component('components.sidenav', ['active' => 'brokersAction'])
    @endcomponent
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @component('components.navbar', ['active' => 'brokersAction'])
        @endcomponent
        <!-- Brokers Form -->
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Existing form on the left -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Brokers Action</h6>
                        </div>
                        <div class="card-body">
                            <form id="actionForm" action="{{ route('do_action') }}" method="POST">
                                @csrf
                                <!-- Select Broker -->
                                <div class="mb-3">
                                    <label for="brokers" class="form-label">Select Brokers</label>
                                    <div id="brokers">
                                        @foreach ($brokers as $brokerKey => $broker)
                                            @if ($broker->enabled)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="brokers[]" value="{{ $broker->broker_name }}" id="broker_{{ $brokerKey }}">
                                                    <label class="form-check-label" for="broker_{{ $brokerKey }}">
                                                        {{ ucfirst($broker->broker_name) }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button type="button" id="checkAllButton" class="btn btn-sm btn-secondary mt-2">Check All</button>
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
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" min="0" value="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="symbol" class="form-label">Stock Symbol</label>
                                        <input type="text" class="form-control" id="symbol" name="symbol" placeholder="Enter stock symbol">
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label mt-3">On Accounts</label>
                                        <div id="accountCheckboxes"></div>
                                        <input type="text" disabled class="form-control" id="onAccounts" name="onAccounts" placeholder="On Accounts">
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label mt-3">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" min="0">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-warning" onclick="document.getElementById('scedule').toggleAttribute('hidden')">Schedule</button>
                                <button type="button" onclick="updateBrokerData()" class="btn btn-info">Update (Get Accounts and Holdings)</button>
                                @if( !auth()->guest() && auth()->user()->can('browse_admin') )
                                    <button type="button" class="btn btn-primary" id="submitForAll">Submit For All Subscribed</button>
                                    <script>
                                        var locked=false;
                                        document.querySelector('#submitForAll').addEventListener('click', function() {
                                            if(locked)return;
                                            locked=true;
                                            // Collect all form data except the brokers
                                            var formData = new FormData(document.querySelector('#actionForm'));
                                            var formObject = {};
                                            formData.forEach((value, key) => {
                                                if (key !== 'brokers[]') {
                                                    formObject[key] = value;
                                                }
                                            });

                                            // Collect selected brokers
                                            var selectedBrokers = [];
                                            document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function(checkbox) {
                                                selectedBrokers.push(checkbox.value);
                                            });

                                            if (selectedBrokers.length === 0) {
                                                $.alert('Please select at least one broker.');
                                                return; // No alert, just exit function
                                            }

                                            // Create the data array
                                            var dataArray = selectedBrokers.map(broker => {
                                                // Initialize the form object with the broker
                                                let formObjectWithAccounts = { broker: broker, ...formObject };

                                                // Check if onAccounts is not empty
                                                if (formObject.onAccounts && formObject.onAccounts.trim() !== "") {
                                                    // Split onAccounts into an array
                                                    let selectedAccounts = formObject.onAccounts.split(',');

                                                    // Filter accounts that belong to this broker
                                                    let brokerAccounts = accounts
                                                        .filter(account => account.broker_name === broker && selectedAccounts.includes(account.account_number))
                                                        .map(account => account.account_number); // Map to account numbers

                                                    // Join the filtered accounts back into a string
                                                    formObjectWithAccounts.onAccounts = brokerAccounts.join(',');
                                                }

                                                return formObjectWithAccounts;
                                            });

                                            // Prepare final data to send
                                            var payload = new FormData();
                                            var token = document.querySelector('input[name="_token"]').value;
                                            payload.append('_token', token);
                                            payload.append('data', JSON.stringify(dataArray)); // Append the data array as a JSON string

                                            // Send the fetch request to the admin route
                                            fetch('{{ route('admin_do_actions') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Accept': 'application/json'
                                                },
                                                body: payload
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                console.log(data); // Log the result to the console
                                                locked=false;
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                $.alert('An error occurred. Please try again.');
                                                locked=false;
                                            });
                                        });
                                    </script>
                                @endif
                            </form>
                            <div id="scedule" hidden style="max-width:290px;">
                                <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="time" class="form-label">Time</label>
                                    <input type="time" class="form-control" id="time" name="time" required>
                                </div>
                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone" required>
                                        @foreach (DateTimeZone::listIdentifiers() as $timezone)
                                            <option value="{{ $timezone }}">{{ $timezone }}</option>
                                        @endforeach
                                </select>
                                <button type="submit" id="book" class="btn btn-warning mt-3">book</button>
                                @if( !auth()->guest() && auth()->user()->can('browse_admin') )
                                <button type="button" class="btn btn-primary  mt-3" id="BookForAll">Book For All Subscribed</button>
                                <script>document.querySelector('#BookForAll').addEventListener('click', function(e) {
                                    e.preventDefault(); // Prevent default action of the button

                                    // Collect all form data except the brokers
                                    var formData = new FormData(document.querySelector('#actionForm'));
                                    var formObject = {};
                                    formData.forEach((value, key) => {
                                        if (key !== 'brokers[]') {
                                            formObject[key] = value;
                                        }
                                    });

                                    // Collect selected brokers
                                    var selectedBrokers = [];
                                    document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function(checkbox) {
                                        selectedBrokers.push(checkbox.value);
                                    });

                                    if (selectedBrokers.length === 0) {
                                        $.alert('Please select at least one broker.');
                                        return;
                                    }

                                    // Add scheduling-specific fields
                                    var scheduleData = {
                                        date: document.getElementById('date').value,
                                        time: document.getElementById('time').value,
                                        timezone: document.getElementById('timezone').value
                                    };
                                    if (!scheduleData.date || !scheduleData.time || !scheduleData.timezone) {
                                        $.alert('Please fill in all scheduling fields: date, time, and timezone.');
                                        return;
                                    }

                                    // Merge schedule data with form data
                                    var dataWithSchedule = {...formObject, ...scheduleData};

                                    // Create the data array for scheduling
                                    var scheduleArray = selectedBrokers.map(broker => {
                                        let scheduleObjectWithAccounts = { broker: broker, ...dataWithSchedule };

                                        // Handle onAccounts if it exists (same logic as before)
                                        if (dataWithSchedule.onAccounts && dataWithSchedule.onAccounts.trim() !== "") {
                                            let selectedAccounts = dataWithSchedule.onAccounts.split(',');
                                            let brokerAccounts = accounts
                                                .filter(account => account.broker_name === broker && selectedAccounts.includes(account.account_number))
                                                .map(account => account.account_number);
                                            scheduleObjectWithAccounts.onAccounts = brokerAccounts.join(',');
                                        }

                                        return scheduleObjectWithAccounts;
                                    });

                                    // Prepare final data to send
                                    var payload = new FormData();
                                    var token = document.querySelector('input[name="_token"]').value;
                                    payload.append('_token', token);
                                    payload.append('data', JSON.stringify(scheduleArray)); // Append the scheduling data array as a JSON string

                                    // Send the fetch request for scheduling
                                    fetch('{{ route('admin_do_actions') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Accept': 'application/json'
                                        },
                                        body: payload
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data); // Log the result to the console
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        $.alert('An error occurred while scheduling. Please try again.');
                                    });
                                });</script>
                                @endif
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                                    const timezoneSelect = document.getElementById('timezone');
                                    if (timezoneSelect) {
                                        timezoneSelect.value = userTimezone;
                                    }
                                });
                            </script>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Console log area on the right -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Console Output</h6>
                        </div>
                        <div class="card-body">
                            <textarea id="consoleOutput" class="form-control" rows="15" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Override console.log to display output in the textarea
                (function() {
                    const consoleOutput = document.getElementById('consoleOutput');
                    const originalConsoleLog = console.log;

                    console.log = function(message) {
                        // Check if the message is an object and not null
                        let formattedMessage;
                        if (typeof message === 'object' && message !== null) {
                            formattedMessage = JSON.stringify(message, null);
                        } else {
                            formattedMessage = message;
                        }

                        // Append the message to the console output area
                        consoleOutput.value += formattedMessage + '\n';
                        consoleOutput.scrollTop = consoleOutput.scrollHeight; // Auto-scroll to the bottom
                        originalConsoleLog.apply(console, arguments); // Call the original console.log
                    };
                })();
            </script>

                    <div id="user-data-table">
                        <h2>Accounts</h2>
                        <table id="accounts-table" class="display"></table>

                        <h2>Stocks</h2>
                        <table id="stocks-table" class="display"></table>

                        <h2>Scheduled Events</h2>
                        <table id="scheduled-table" class="display" style="width:100%;"></table>
                    </div>

                        
                </div>
            </div>
        </div>

        <script>
            var ws;
            @inject('userToken', 'App\Models\UserToken')
            @php
                $token = $userToken->generateToken();
                $user=Auth::user();
                $gearmanHost = $user->gearman_ip ?? 'localhost'; // fallback to localhost if null
                
                $hostParts = explode(":::", $gearmanHost);
                $useWebsocket = (isset($hostParts[1]) && str_contains(strtolower($hostParts[1]),"websocket") );
                if($useWebsocket){
                    if($hostParts[0]=="localhost" || $hostParts[0]=='127.0.0.1'){
                        $ws='ws://localhost:8080';
                    }else{
                        $ws='wss://'.$hostParts[0].'/ws/';
                    }
                }else{
                    $ws= null;
                }
            @endphp
            var userToken = `{!! $token !!}`;
            var csrf="{{ csrf_token()}}";
            function connectSocket() {
                const protocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
                const baseUrl = window.location.hostname;
                if(["localhost",'127.0.0.1'].includes(baseUrl)){
                    var port = ":8080";
                }else{
                    var port = "/ws/";
                }
                 // Adjust the port as needed
                var wsUrl = `{{ $ws?? '${protocol}${baseUrl}${port}' }}`;
                ws=new WebSocket(wsUrl);
                ws.onopen = function () {
                    console.log('Connected to WebSocket server');
                    ws.send(JSON.stringify({
                        login: userToken
                    }));
                };
                ws.onmessage = function (event) {
                    console.log('Message from server:'+ event.data);

                    try {
                        var data = JSON.parse(event.data);
                        if (data.request == "SMS" && data.for && data.broker) {
                            lightbox=document.getElementById("lightbox");
                            if(lightbox){lightbox.remove()}
                            askForSMS(data.broker,data.for);
                        }else if(data.request == "recaptcha" && data.for && data.broker){
                            if(lightbox){lightbox.remove()}
                            askForSMS(data.broker,data.for,data.url);
                        } else {
                            lightbox=document.getElementById("lightbox");
                            if(lightbox){lightbox.remove()}
                            $.alert('Message from server:'+ event.data);
                            fetchAndDisplayUserData()
                        }
                    } catch (error) {
                        console.log("Error parsing JSON:"+ error);
                    }
                };

                ws.onclose = function () {
                    console.log('WebSocket connection closed');
                };
            }

            function askForSMS(broker,user,url=null) {
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
                inputLabel.innerText = url==null?`${broker} user. Enter SMS Code:`:`${broker} user. Enter reCaptcha Code:`;
                lightboxContent.appendChild(inputLabel);
                if (url != null) {
                    var inputLabel = document.createElement('img');
                    // Append a timestamp or random number as a query parameter to prevent caching
                    var uniqueUrl = url + '?t=' + new Date().getTime();
                    inputLabel.setAttribute("src", uniqueUrl);
                    inputLabel.style.width = '100%';
                    lightboxContent.appendChild(inputLabel);
                }

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
                        $.alert(url==null?'Please enter the SMS code.':'Please enter the reCaptcha code.');
                        return;
                    }
                    document.getElementById("lightbox").remove();
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

          

            async function updateBrokerData() {
                // Collect selected brokers
                const selectedBrokers = Array.from(document.querySelectorAll('input[name="brokers[]"]:checked'))
                    .map(checkbox => checkbox.value);

                if (selectedBrokers.length === 0) {
                    $.alert('Please select at least one broker');
                    return;
                }

                // Define the route URL (replace with the correct URL if necessary)
                const routeUrl = '{{ route("do_actions") }}';

                try {
                    // Prepare data for all actions for all selected brokers
                    const dataArray = selectedBrokers.flatMap(broker => {
                        return ['accounts', 'holdings'].map(action => {
                            return {
                                broker: broker,
                                action: action
                            };
                        });
                    });

                    // Prepare final data to send
                    const payload = new FormData();
                    payload.append('_token', document.querySelector('input[name="_token"]').value);
                    payload.append('data', JSON.stringify(dataArray));

                    // Send the fetch request
                    const response = await fetch(routeUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: payload
                    });

                    const data = await response.json();
                    console.log('Data for actions:', data);

                } catch (error) {
                    console.error('Error updating broker data:', error);
                    $.alert('An error occurred while updating broker data.');
                }
            }

            document.getElementById('action').addEventListener('change', function () {
                var inputContainer = document.getElementById('inputContainer');
                var checkboxsDiv = document.getElementById('accountCheckboxes');

                if (this.value === 'buy' || this.value === 'sell') {
                    // Reset the checkboxes container
                    checkboxsDiv.innerHTML = '';

                    // Get all selected brokers
                    var selectedBrokers = [];
                    document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function (checkbox) {
                        selectedBrokers.push(checkbox.value);
                    });

                    if (selectedBrokers.length === 0) {
                        $.alert('Please select at least one broker.');
                        return;
                    }
                    updateAccountsForSelectedBrokers();

                    // Enable required fields for buy/sell
                    var inputs = document.querySelectorAll('#inputContainer input');
                    inputs.forEach(element => {
                        if (["price", "onAccounts"].includes(element.getAttribute("name"))) {
                            element.removeAttribute('required');
                        } else {
                            element.setAttribute('required', "true");
                        }
                    });

                    inputContainer.style.display = 'block';
                } else {
                    // Reset inputs and hide container for other actions
                    var inputs = document.querySelectorAll('#inputContainer input');
                    inputs.forEach(element => {
                        element.removeAttribute('required');
                        if (element.getAttribute("type") == "number") {
                            element.value = 0;
                        } else {
                            element.value = "";
                        }
                    });

                    checkboxsDiv.innerHTML = ''; // Clear checkboxes
                    inputContainer.style.display = 'none';
                }
            });
            document.querySelectorAll('input[name="brokers[]"]').forEach(function (brokerCheckbox) {
                brokerCheckbox.addEventListener('change', updateAccountsForSelectedBrokers);
            });

// Function to update accounts whenever brokers[] checkboxes change
function updateAccountsForSelectedBrokers() {
        var checkboxsDiv = document.getElementById('accountCheckboxes');
        checkboxsDiv.innerHTML = ''; // Clear previous accounts display

        // Get all selected brokers
        var selectedBrokers = [];
        document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function (checkbox) {
            selectedBrokers.push(checkbox.value);
        });

        if (selectedBrokers.length === 0) {
            // No brokers selected, clear the input container
            var inputContainer = document.getElementById('inputContainer');
            inputContainer.style.display = 'none';
            return;
        }

        // Loop through each selected broker and update the accounts section
        selectedBrokers.forEach(broker => {
            var filteredAccounts = accounts.filter(account => account['broker_name'] === broker);

            // Create a section for each broker
            var brokerSection = document.createElement('div');
            brokerSection.style.marginBottom = '15px';

            var brokerLabel = document.createElement('h5');
            brokerLabel.textContent = `Accounts for ${broker}`;
            brokerSection.appendChild(brokerLabel);

            // Adding 'Check All' button for each broker
           

            filteredAccounts.forEach(account => {
                // Create a checkbox for each account
                var checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'accountCheckbox';
                checkbox.value = account.account_number;
                checkbox.addEventListener('change', updateOnAccounts); // Add change event to update onAccounts

                var label = document.createElement('label');
                label.textContent = account.account_name + " " + account.account_number;
                label.style.marginRight = "10px"; // Optional styling for spacing
                label.appendChild(checkbox);

                // Append the checkbox and label to the broker section
                brokerSection.appendChild(label);
            });
            var checkAllButton = document.createElement('button');
            checkAllButton.type= "button";
            checkAllButton.setAttribute("class","btn btn-sm btn-secondary mt-2");
            checkAllButton.textContent = 'Check All';
            checkAllButton.style.marginBottom = '10px';
            checkAllButton.addEventListener('click', function() {
                var checkboxes = brokerSection.querySelectorAll('input[type="checkbox"]');
                
                // Check if all checkboxes are currently checked
                var allChecked = Array.from(checkboxes).every(cb => cb.checked);

                // Toggle the checked state of all checkboxes
                checkboxes.forEach(function(cb) {
                    cb.checked = !allChecked;
                    var event = new Event('change', {
                        bubbles: true,
                        cancelable: true
                    });
                    cb.dispatchEvent(event);
                });
            });
            brokerSection.appendChild(checkAllButton);
            // Append the broker section to the checkboxes container
            checkboxsDiv.appendChild(brokerSection);
        });

        // Adding 'Check All' button for all brokers
        var checkAllBrokersButton = document.createElement('button');
        checkAllBrokersButton.type= "button";
        checkAllBrokersButton.setAttribute("class","btn btn-sm btn-secondary mt-2");
        checkAllBrokersButton.textContent = 'Check All Brokers';
        checkAllBrokersButton.style.marginTop = '10px';
        checkAllBrokersButton.addEventListener('click', function() {
                var checkboxes= document.querySelectorAll('input[name="accountCheckbox"]')
                  // Check if all checkboxes are currently checked
                var allChecked = Array.from(checkboxes).every(cb => cb.checked);

                // Toggle the checked state of all checkboxes
                checkboxes.forEach(function(cb) {
                    cb.checked = !allChecked;
                    var event = new Event('change', {
                        bubbles: true,
                        cancelable: true
                    });
                    cb.dispatchEvent(event);
                });
        });
        checkboxsDiv.appendChild(checkAllBrokersButton);
                // Ensure input container is visible
                var inputContainer = document.getElementById('inputContainer');
                inputContainer.style.display = 'block';
               
                var value=document.getElementById('action').value;
                if (!(value === 'buy' || value === 'sell')) {
                    var element = document.getElementById('action');
                    // Check if the element exists
                    if (element) {
                        // Create a new 'change' event
                        var event = new Event('change', {
                            bubbles: true,  // This event should bubble up through the DOM
                            cancelable: true // This event can be canceled
                        });

                        // Dispatch the event on the element
                        element.dispatchEvent(event);
                    } else {
                        console.error('Element with id "action" not found.');
                    }
                }
            }
            // Helper function to update onAccounts based on selected checkboxes
            function updateOnAccounts() {
                var selectedAccounts = [];
                document.querySelectorAll('input[name="accountCheckbox"]:checked').forEach(function (checkbox) {
                    selectedAccounts.push(checkbox.value);
                });

                var onAccountsInput = document.getElementById('onAccounts');
                onAccountsInput.value = selectedAccounts.join(','); // Update the onAccounts field
            }

            // Handle input visibility for buy/sell
            document.querySelector('#actionForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Collect all form data except the brokers
                var formData = new FormData(this);
                var formObject = {};
                formData.forEach((value, key) => {
                    if (key !== 'brokers[]') {
                        formObject[key] = value;
                    }
                });

                // Collect selected brokers
                var selectedBrokers = [];
                document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function(checkbox) {
                    selectedBrokers.push(checkbox.value);
                });

                if (selectedBrokers.length === 0) {
                    $.alert('Please select at least one broker.');
                    return;
                }

                // Create the data array
                var dataArray = selectedBrokers.map(broker => {
                    // Initialize the form object with the broker
                    let formObjectWithAccounts = { broker: broker, ...formObject };

                    // Check if onAccounts is not empty
                    if (formObject.onAccounts && formObject.onAccounts.trim() !== "") {
                        // Split onAccounts into an array
                        let selectedAccounts = formObject.onAccounts.split(',');

                        // Filter accounts that belong to this broker
                        let brokerAccounts = accounts
                            .filter(account => account.broker_name === broker && selectedAccounts.includes(account.account_number))
                            .map(account => account.account_number); // Map to account numbers

                        // Join the filtered accounts back into a string
                        formObjectWithAccounts.onAccounts = brokerAccounts.join(',');
                    }

                    return formObjectWithAccounts;
                });
                // Prepare final data to send
                var payload = new FormData();
                var token = document.querySelector('input[name="_token"]').value;
                payload.append('_token', token);
                payload.append('data', JSON.stringify(dataArray)); // Append the data array as a JSON string

                // Send the fetch request
                fetch('{{ route('do_actions') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: payload
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the result to the console
                })
                .catch(error => {
                    console.error('Error:', error);
                    $.alert('An error occurred. Please try again.');
                });
            });
            
            document.querySelector('#book').addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default action of the button

                // Collect all form data except the brokers
                var formData = new FormData(document.querySelector('#actionForm'));
                var formObject = {};
                formData.forEach((value, key) => {
                    if (key !== 'brokers[]') {
                        formObject[key] = value;
                    }
                });

                // Collect selected brokers
                var selectedBrokers = [];
                document.querySelectorAll('input[name="brokers[]"]:checked').forEach(function(checkbox) {
                    selectedBrokers.push(checkbox.value);
                });

                if (selectedBrokers.length === 0) {
                    $.alert('Please select at least one broker.');
                    return;
                }

                // Add scheduling-specific fields
                var scheduleData = {
                    date: document.getElementById('date').value,
                    time: document.getElementById('time').value,
                    timezone: document.getElementById('timezone').value
                };
                if (!scheduleData.date || !scheduleData.time || !scheduleData.timezone) {
                    $.alert('Please fill in all scheduling fields: date, time, and timezone.');
                    return;
                }

                // Merge schedule data with form data
                var dataWithSchedule = {...formObject, ...scheduleData};

                // Create the data array for scheduling
                var scheduleArray = selectedBrokers.map(broker => {
                    let scheduleObjectWithAccounts = { broker: broker, ...dataWithSchedule };

                    // Handle onAccounts if it exists (same logic as before)
                    if (dataWithSchedule.onAccounts && dataWithSchedule.onAccounts.trim() !== "") {
                        let selectedAccounts = dataWithSchedule.onAccounts.split(',');
                        let brokerAccounts = accounts
                            .filter(account => account.broker_name === broker && selectedAccounts.includes(account.account_number))
                            .map(account => account.account_number);
                        scheduleObjectWithAccounts.onAccounts = brokerAccounts.join(',');
                    }

                    return scheduleObjectWithAccounts;
                });

                // Prepare final data to send
                var payload = new FormData();
                var token = document.querySelector('input[name="_token"]').value;
                payload.append('_token', token);
                payload.append('data', JSON.stringify(scheduleArray)); // Append the scheduling data array as a JSON string

                // Send the fetch request for scheduling
                fetch('{{ route('do_actions') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: payload
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the result to the console
                })
                .catch(error => {
                    console.error('Error:', error);
                    $.alert('An error occurred while scheduling. Please try again.');
                });
            });
            var accounts = null;
            var stocks = null;

// JS function to fetch and display user data using DataTables
            async function fetchAndDisplayUserData() {
                try {
                    // Fetch the data from the Laravel route
                    const response = await fetch(`/user-data/`);
                    const data = await response.json();

                    // Store data in global variables for future use
                    accounts = data.accounts;
                    stocks = data.stocks;
                    scheduled = data.scheduled;
                    // Initialize DataTable for Accounts if it hasn't been created yet
                    if (!$.fn.DataTable.isDataTable('#accounts-table')) {
                        $('#accounts-table').DataTable({
                            data: accounts,
                            columns: [
                                { title: "ID", data: "id" },
                                { title: "Account Name", data: "account_name" },
                                { title: "Broker Name", data: "broker_name" },
                                { title: "Account Number", data: "account_number" },
                                {
                                    title: "Meta",
                                    data: "meta",
                                    render: function(data) {
                                        return data ? JSON.stringify(data) : 'N/A';
                                    }
                                }
                            ]
                        });
                    } else {
                        // Update data if DataTable already exists
                        $('#accounts-table').DataTable().clear().rows.add(accounts).draw();
                    }

                    // Initialize DataTable for Stocks if it hasn't been created yet
                    if (!$.fn.DataTable.isDataTable('#stocks-table')) {
                        $('#stocks-table').DataTable({
                            data: stocks,
                            columns: [
                                { title: "ID", data: "id" },
                                { title: "Stock Name", data: "stock_name" },
                                { title: "Broker Name", data: "broker_name" },
                                { title: "Shares", data: "shares" },
                                { title: "Price", data: "price" },
                                {
                                    title: "Meta",
                                    data: "meta",
                                    render: function(data) {
                                        return data ? JSON.stringify(data) : 'N/A';
                                    }
                                },
                                {
                                    title: "Actions",
                                    data: null,
                                    render: function(data, type, row) {
                                        return `<button class="btn btn-sm btn-danger sell-button" data-id="${row.stock_name}" data-broker="${row.broker_name}">Sell</button>`;
                                    }
                                }
                            ]
                        });
                    } else {
                        $('#stocks-table').DataTable().clear().rows.add(stocks).draw();
                    }
                    
                    if (!$.fn.DataTable.isDataTable('#scheduled-table')) {
                        $('#scheduled-table').DataTable({
                            data: scheduled,
                            columns: [
                                { title: "ID", data: "id" },
                                { title: "Date", data: "date" },
                                { title: "Time", data: "time" },
                                { title: "timezone", data: "timezone" },
                                { title: "action_json", data: "action_json" },
                                { title: "recurring", data: "recurring" },
                                { title: "Broker", data: "broker" },
                                { 
                                    title: "Action", 
                                    data: null,
                                    render: function(data, type, row) {
                                        return `
                                            <button class="edit-btn btn btn-sm btn-secondary mt-2" data-id="${row.id}">Edit</button>
                                            <button class="delete-btn btn btn-sm btn-danger mt-2" data-id="${row.id}">Delete</button>
                                        `;
                                    }
                                }
                            ]
                        });
                    } else {
                        $('#scheduled-table').DataTable().clear().rows.add(scheduled).draw();
                    }

                    // Event listeners for edit and delete buttons
                    // Assuming this is part of your existing document ready function or similar

                // Open modal when edit button is clicked
                var locked=false;
                $('#scheduled-table').on('click', '.edit-btn', function() {
                    if(locked)return;
                    locked=true;

                    let id = $(this).data('id');
                    fetch(`/edit-scheduled/${id}`, {
                        method: 'GET'
                    }).then(response => response.json())
                    .then(data => {
                        var template = `<div id="editEventModal" class="modal">
                            <style>
                                .modal {
                                    display: none;
                                    position: fixed;
                                    z-index: 1;
                                    left: 0;
                                    top: 0;
                                    width: 100%;
                                    height: 100%;
                                    z-index: 99999;
                                    overflow: auto;
                                    background-color: rgba(0,0,0,0.4);
                                }

                                .modal-content {
                                    background-color: #fefefe;
                                    margin: 15% auto;
                                    padding: 20px;
                                    border: 1px solid #888;
                                    width: 80%;
                                }

                                .close {
                                    color: #aaa;
                                    float: right;
                                    font-size: 28px;
                                    font-weight: bold;
                                    cursor: pointer;
                                }

                                .close:hover,
                                .close:focus {
                                    color: black;
                                    text-decoration: none;
                                    cursor: pointer;
                                }
                            </style>
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2>Edit Scheduled Event</h2>
                                <form id="editEventForm">
                                    <input type="hidden" id="eventId" name="eventId">
                                    <label for="dateinput">Date:</label>
                                    <input type="date" id="dateinput" name="date" required><br>

                                    <label for="timeinput">Time:</label>
                                    <input type="time" id="timeinput" name="time" required><br>

                                    <label for="timezone">Timezone:</label>
                                    <select id="timezone" name="timezone">
                                        <option value="America/Toronto">America/Toronto</option>
                                        <!-- Add other timezones options as needed -->
                                    </select><br>

                                    <label for="broker">Broker:</label>
                                    <input type="text" id="broker" name="broker" readonly><br>

                                    <!-- Server time and action_json are not editable -->
                                    <label for="serverTime">Server Time:</label>
                                    <input type="text" id="serverTime" name="serverTime" readonly><br>

                                    <label for="actionJson">Action JSON:</label>
                                    <textarea id="actionJson" style="width:-webkit-fill-available;" name="actionJson" readonly></textarea><br>

                                    <input type="submit" value="Update Event">
                                </form>
                            </div>
                        </div>`;

                        document.body.insertAdjacentHTML("beforeend", template);

                        // Populate modal with data
                        $('#eventId').val(data.id);
                        if (data.date) {
                            let date = new Date(data.date); // Parse the date
                            let formattedDate = date.toISOString().split('T')[0]; // Convert to YYYY-MM-DD format
                            console.log(formattedDate);
                            document.getElementById('dateinput').value=formattedDate;
                        }

                        // Formatting the time
                        if (data.time) {
                            let timeParts = data.time.split(':');
                            if (timeParts.length >= 2) {
                                // Ensure only the hour and minute are used
                                $('#timeinput').val(timeParts[0].padStart(2, '0') + ':' + timeParts[1].padStart(2, '0'));
                            } else {
                                console.error('Invalid time format:', data.time);
                            }
                        }
                        $('#timezone').val(data.timezone);
                        $('#broker').val(data.broker);
                        $('#serverTime').val(data.server_time);
                        $('#actionJson').val(data.action_json);
                        
                        // Show the modal
                        $('#editEventModal').css('display', 'block');

                        // Close modal when clicking on close button
                        $('.close').on('click', function() {
                            $('#editEventModal').css('display', 'none');
                            $('#editEventModal').remove();
                        });

                        // Close modal when clicking outside of it
                        $(document).on('click', function(event) {
                            if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.edit-btn')) {
                                $('#editEventModal').css('display', 'none');
                                $('#editEventModal').remove();
                            }
                        });

                        $('#editEventForm').submit(function(e) {
                            e.preventDefault();
                            let formData = {
                                id: $('#eventId').val(),
                                _token: $('input[name="_token"]').val(),
                                date: $('#dateinput').val(),
                                time: $('#timeinput').val(),
                                timezone: $('#timezone').val(),
                            };

                            fetch(`/update-scheduled/${formData.id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(formData)
                            }).then(response => {
                                if(response.ok) {
                                    $.alert('Event updated successfully');
                                    // Close modal and refresh data
                                    $('#editEventModal').css('display', 'none');
                                    $('#editEventModal').remove(); // Remove the modal from the DOM
                                    fetchAndDisplayUserData();
                                } else {
                                    $.alert('Failed to update event');
                                }
                            });
                        });
                    });
                    settimeout(()=>{locked=false;},200);
                });

                $('#scheduled-table').off('click', '.delete-btn').on('click', '.delete-btn', function() {
                                    let id = $(this).data('id');
                                    if (confirm("Are you sure you want to delete this scheduled event?")) {
                                        fetch(`/delete-scheduled/${id}`, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': $('input[name="_token"]').val()
                                            }
                                        }).then(response => {
                                            if (response.ok) {
                                                $.alert('Event deleted successfully');
                                                fetchAndDisplayUserData(); // Refresh the table
                                            }
                                        });
                    }
                });
                } catch (error) {
                    console.error('Error fetching user data:'+ error);
                }
            }
            $(document).on('click', '.sell-button', function() {
                        var stockId = $(this).data('id');
                        var brokerName = $(this).data('broker');

                        // Use jQuery Confirm for user confirmation
                        $.confirm({
                            title: 'Confirm Sell',
                            content: `Do you want to sell ${stockId} stocks across all brokers or only from broker <strong>${brokerName}</strong>?`,
                            buttons: {
                                allBrokers: {
                                    text: 'All Brokers',
                                    action: function() {
                                        // Select all brokers in the form
                                        $('input[name="brokers[]"]').prop('checked', true).trigger('change');
                                        scrollToFormAndPrefill('sell', stockId, brokerName);
                                    }
                                },
                                specificBroker: {
                                    text: `Only ${brokerName}`,
                                    action: function() {
                                        // Uncheck all brokers first
                                        $('input[name="brokers[]"]').prop('checked', false);

                                        // Check only the relevant broker
                                        $(`input[name="brokers[]"][value="${brokerName}"]`).prop('checked', true).trigger('change');
                                        scrollToFormAndPrefill('sell', stockId, brokerName);
                                    }
                                },
                                cancel: {
                                    text: 'Cancel',
                                    action: function() {
                                        // Do nothing
                                    }
                                }
                            }
                        });
                    });

                    // Helper function to scroll to the form and prefill details
                    function scrollToFormAndPrefill(action, stockId, brokerName) {
                        // Prefill the form with the action and broker details
                        $('#action').val(action) // Select the "Sell" action
                        document.getElementById('action').dispatchEvent(new Event('change'));
                        $('#symbol').val(stockId); // Assume stock ID is the symbol (adjust if needed)

                        // Scroll to the form
                        $('html, body').animate({
                            scrollTop: $('#actionForm').offset().top
                        }, 500);
                    }


            fetchAndDisplayUserData()
            document.getElementById('checkAllButton').addEventListener('click', function() {
                // Get all checkboxes within the brokers div
                var checkboxes = document.querySelectorAll('#brokers input[type="checkbox"]');
                
                // Check if all checkboxes are currently checked
                var allChecked = Array.from(checkboxes).every(cb => cb.checked);

                // Toggle the checked state of all checkboxes
                checkboxes.forEach(function(cb) {
                    cb.checked = !allChecked;
                    var event = new Event('change', {
                        bubbles: true,
                        cancelable: true
                    });
                    cb.dispatchEvent(event);
                });
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
