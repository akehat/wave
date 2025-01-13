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
        input.tgl-radio-tab-child {
            position: absolute;
            left: -99999em;
            opacity: 1;
            z-index: 1;
        }

input.tgl-radio-tab-child+label {
  cursor: pointer;
  float: left;
  border: 1px solid #aaa;
  margin-right: -1px;
  padding: .5em 1em;
  position: relative;
}

input.tgl-radio-tab-child+label:hover {
  background-color: #d4d4ff;
}

[type=radio]:checked+label {
  background-color: #d4d4ff;
  z-index: 1;
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
                        ["type"=>"text", "name"=>"firstrade_pin", "placeholder"=>"Enter pin/phone/email"]],
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
                        ["type"=>"text", "name"=>"webull_pin", "placeholder"=>"Enter Trading Pin"]],
                        "tornado"=>[["type"=>"text", "name"=>"tornado_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"tornado_password", "placeholder"=>"Enter Password"]],
                        "DSPAC"=>[["type"=>"text", "name"=>"DSPAC_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"DSPAC_password", "placeholder"=>"Enter Password"]],
                        "BBAE"=>[["type"=>"text", "name"=>"BBAE_username", "placeholder"=>"Enter Username"],
                        ["type"=>"password", "name"=>"BBAE_password", "placeholder"=>"Enter Password"]]]
                    @endphp


                    <div class="card-body">

                            <!-- Tradier -->
                            @foreach ($brokerFields as $brokerName => $fields)

                            @php
                                // Uppercase the first letter of the broker name
                                $displayName = ucfirst($brokerName);
                                $invisible = isBrokerDataEmpty($brokers,$displayName);
                                if($invisible){$hidden[] = $displayName;}
                            @endphp
                            <form action="{{ route('save_brokers') }}"  method="POST">
                                @csrf
                            <input value="{{ $displayName }}" name="broker" type="hidden">
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
                                    @if ($field['name'] === 'webull_username')
                                        @php
                                            $active = isset($brokers[$displayName]) && isset($brokers[$displayName]->$dbName) && preg_match('/^\+\d+-/', $brokers[$displayName]->$dbName,$matches);
                                        @endphp
                                            <div class="form-check">
                                                <div class="tgl-radio-tabs">
                                                    <input id="webullPhone" type="radio" class="form-check-input tgl-radio-tab-child" name="abcorigin"><label for="webullPhone" class="radio-inline">Phone</label>
                                                    <input id="webullEmail" type="radio" class="form-check-input tgl-radio-tab-child" name="abcorigin"><label for="webullEmail" class="radio-inline">Email</label>
                                                </div>
                                            </div>
                                            <div class="input-group mb-2">
                                            <select {{ $active?("'"):"hidden" }} id="webullPhoneSelect" style="margin-right:10px;"><option value="+1-">United States(+1)</option><option value="+93-">Afghanistan(+93)</option><option value="+355-">Albania(+355)</option><option value="+213-">Algeria(+213)</option><option value="+684-">American Samoa(+684)</option><option value="+376-">Andorra(+376)</option><option value="+244-">Angola(+244)</option><option value="+1264-">Anguilla(+1264)</option><option value="+1268-">Antigua and Barbuda(+1268)</option><option value="+54-">Argentina(+54)</option><option value="+374-">Armenia(+374)</option><option value="+297-">Aruba(+297)</option><option value="+247-">Ascension(+247)</option><option value="+61-">Australia(+61)</option><option value="+43-">Austria(+43)</option><option value="+994-">Azerbaijan(+994)</option><option value="+1242-">Bahamas(+1242)</option><option value="+973-">Bahrain(+973)</option><option value="+880-">Bangladesh(+880)</option><option value="+1246-">Barbados(+1246)</option><option value="+375-">Belarus(+375)</option><option value="+32-">Belgium(+32)</option><option value="+501-">Belize(+501)</option><option value="+229-">Benin(+229)</option><option value="+1441-">Bermuda Is.(+1441)</option><option value="+591-">Bolivia(+591)</option><option value="+267-">Botswana(+267)</option><option value="+55-">Brazil(+55)</option><option value="+673-">Brunei(+673)</option><option value="+359-">Bulgaria(+359)</option><option value="+226-">Burkina(+226)</option><option value="+95-">Burma(+95)</option><option value="+257-">Burundi(+257)</option><option value="+855-">Cambodia(+855)</option><option value="+237-">Cameroon(+237)</option><option value="+1-">Canada(+1)</option><option value="+1345-">Cayman Is.(+1345)</option><option value="+236-">Central African Republic(+236)</option><option value="+235-">Chad(+235)</option><option value="+56-">Chile(+56)</option><option value="+57-">Colombia(+57)</option><option value="+242-">Congo(+242)</option><option value="+682-">Cook Is.(+682)</option><option value="+506-">Costa Rica(+506)</option><option value="+53-">Cuba(+53)</option><option value="+357-">Cyprus(+357)</option><option value="+420-">Czech Republic(+420)</option><option value="+45-">Denmark(+45)</option><option value="+253-">Djibouti(+253)</option><option value="+1-">Dominica Rep.(+1)</option><option value="+593-">Ecuador(+593)</option><option value="+20-">Egypt(+20)</option><option value="+503-">EI Salvador(+503)</option><option value="+372-">Estonia(+372)</option><option value="+251-">Ethiopia(+251)</option><option value="+679-">Fiji(+679)</option><option value="+358-">Finland(+358)</option><option value="+33-">France(+33)</option><option value="+594-">French Guiana(+594)</option><option value="+689-">French Polynesia(+689)</option><option value="+241-">Gabon(+241)</option><option value="+220-">Gambia(+220)</option><option value="+995-">Georgia(+995)</option><option value="+49-">Germany(+49)</option><option value="+233-">Ghana(+233)</option><option value="+350-">Gibraltar(+350)</option><option value="+30-">Greece(+30)</option><option value="+1473-">Grenada(+1473)</option><option value="+1671-">Guam(+1671)</option><option value="+502-">Guatemala(+502)</option><option value="+224-">Guinea(+224)</option><option value="+592-">Guyana(+592)</option><option value="+509-">Haiti(+509)</option><option value="+504-">Honduras(+504)</option><option value="+852-">Hong Kong(+852)</option><option value="+36-">Hungary(+36)</option><option value="+354-">Iceland(+354)</option><option value="+91-">India(+91)</option><option value="+62-">Indonesia(+62)</option><option value="+98-">Iran(+98)</option><option value="+964-">Iraq(+964)</option><option value="+353-">Ireland(+353)</option><option value="+972-">Israel(+972)</option><option value="+39-">Italy(+39)</option><option value="+225-">Ivory Coast(+225)</option><option value="+1876-">Jamaica(+1876)</option><option value="+81-">Japan(+81)</option><option value="+962-">Jordan(+962)</option><option value="+7-">Kazakstan(+7)</option><option value="+254-">Kenya(+254)</option><option value="+82-">Korea(+82)</option><option value="+965-">Kuwait(+965)</option><option value="+996-">Kyrgyzstan(+996)</option><option value="+856-">Laos(+856)</option><option value="+371-">Latvia(+371)</option><option value="+961-">Lebanon(+961)</option><option value="+266-">Lesotho(+266)</option><option value="+231-">Liberia(+231)</option><option value="+218-">Libya(+218)</option><option value="+423-">Liechtenstein(+423)</option><option value="+370-">Lithuania(+370)</option><option value="+352-">Luxembourg(+352)</option><option value="+853-">Macao(+853)</option><option value="+261-">Madagascar(+261)</option><option value="+265-">Malawi(+265)</option><option value="+60-">Malaysia(+60)</option><option value="+960-">Maldives(+960)</option><option value="+223-">Mali(+223)</option><option value="+356-">Malta(+356)</option><option value="+596-">Martinique(+596)</option><option value="+230-">Mauritius(+230)</option><option value="+52-">Mexico(+52)</option><option value="+373-">Moldova(+373)</option><option value="+976-">Mongolia(+976)</option><option value="+1664-">Montserrat Is(+1664)</option><option value="+212-">Morocco(+212)</option><option value="+258-">Mozambique(+258)</option><option value="+264-">Namibia(+264)</option><option value="+674-">Nauru(+674)</option><option value="+977-">Nepal(+977)</option><option value="+31-">Netherlands(+31)</option><option value="+599-">Netherlands Antilles(+599)</option><option value="+64-">New Zealand(+64)</option><option value="+505-">Nicaragua(+505)</option><option value="+227-">Niger(+227)</option><option value="+234-">Nigeria(+234)</option><option value="+850-">North Korea(+850)</option><option value="+1670-">Northern Marianas(+1670)</option><option value="+47-">Norway(+47)</option><option value="+968-">Oman(+968)</option><option value="+92-">Pakistan(+92)</option><option value="+507-">Panama(+507)</option><option value="+675-">Papua New Cuinea(+675)</option><option value="+595-">Paraguay(+595)</option><option value="+51-">Peru(+51)</option><option value="+63-">Philippines(+63)</option><option value="+48-">Poland(+48)</option><option value="+351-">Portugal(+351)</option><option value="+1787-">Puerto Rico(+1787)</option><option value="+1939-">Puerto Rico2(+1939)</option><option value="+974-">Qatar(+974)</option><option value="+262-">Reunion(+262)</option><option value="+40-">Romania(+40)</option><option value="+7-">Russia(+7)</option><option value="+1758-">Saint Lucia(+1758)</option><option value="+378-">San Marino(+378)</option><option value="+239-">Sao Tome and Principe(+239)</option><option value="+966-">Saudi Arabia(+966)</option><option value="+221-">Senegal(+221)</option><option value="+381-">Serbia(+381)</option><option value="+248-">Seychelles(+248)</option><option value="+232-">Sierra Leone(+232)</option><option value="+65-">Singapore(+65)</option><option value="+421-">Slovakia(+421)</option><option value="+386-">Slovenia(+386)</option><option value="+677-">Solomon Islands(+677)</option><option value="+252-">Somalia(+252)</option><option value="+27-">South Africa(+27)</option><option value="+34-">Spain(+34)</option><option value="+94-">Sri Lanka(+94)</option><option value="+1784-">St.Vincent(+1784)</option><option value="+249-">Sudan(+249)</option><option value="+597-">Suriname(+597)</option><option value="+268-">Swaziland(+268)</option><option value="+46-">Sweden(+46)</option><option value="+41-">Switzerland(+41)</option><option value="+963-">Syria(+963)</option><option value="+886-">Taiwan(+886)</option><option value="+992-">Tajikistan(+992)</option><option value="+255-">Tanzania(+255)</option><option value="+66-">Thailand(+66)</option><option value="+228-">Togo(+228)</option><option value="+676-">Tonga(+676)</option><option value="+1868-">Trinidad and Tobago(+1868)</option><option value="+216-">Tunisia(+216)</option><option value="+90-">Turkey(+90)</option><option value="+993-">Turkmenistan(+993)</option><option value="+971-">UAE(+971)</option><option value="+256-">Uganda(+256)</option><option value="+380-">Ukraine(+380)</option><option value="+44-">United Kingdom(+44)</option><option value="+598-">Uruguay(+598)</option><option value="+998-">Uzbekistan(+998)</option><option value="+58-">Venezuela(+58)</option><option value="+84-">Vietnam(+84)</option><option value="+685-">Western Samoa(+685)</option><option value="+967-">Yemen(+967)</option><option value="+243-">Zaire(+243)</option><option value="+260-">Zambia(+260)</option><option value="+263-">Zimbabwe(+263)</option></ul><
                                            <input type="{{ $field['type'] }}" class="form-control" id="webull_name_input" name="{{ $field['name'] }}"
                                                placeholder="{{ $field['placeholder'] }}"
                                                value="{{ isset($brokers[$displayName]) && isset($brokers[$displayName]->$dbName) ? $brokers[$displayName]->$dbName : '' }}">
                                        </div>
                                        <script>
                                            
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var code="{{ $active? $matches[0] : "false" }}";
                                                if(code!="false"){
                                                    document.getElementById("webullPhoneSelect").value=code;
                                                }
                                                const phoneRadio = document.getElementById('webullPhone');
                                                const emailRadio = document.getElementById('webullEmail');
                                                var listener=function(){
                                                        code=document.getElementById("webullPhoneSelect").value;
                                                        var currentValue=this.value;
                                                        currentValue=currentValue.replace(/^\+\d+-/g,"").replace(/[^0-9]*/g,"");
                                                        currentValue = code + currentValue;
                                                        this.value=currentValue;
                                                    };
                                                phoneRadio.addEventListener("click",function(){
                                                    document.getElementById("webullPhoneSelect").removeAttribute("hidden");
                                                    var webullInput=document.getElementById("webull_name_input")
                                                    webullInput.addEventListener("input",listener)
                                                    webullInput.dispatchEvent(new Event("input"));
                                                })
                                                emailRadio.addEventListener("click",function(){
                                                    document.getElementById("webullPhoneSelect").setAttribute("hidden","true");
                                                    var webullInput=document.getElementById("webull_name_input")
                                                    currentValue=webullInput.value;
                                                    webullInput.value=currentValue.replace(/^\+\d+-/g,"")
                                                    webullInput.removeEventListener("input",listener)
                                                })
                                        });
                                        </script>
                                    @else
                                        <div class="input-group mb-2">
                                            <input type="{{ $field['type'] }}" class="form-control" name="{{ $field['name'] }}"
                                                placeholder="{{ $field['placeholder'] }}"
                                                value="{{ isset($brokers[$displayName]) && isset($brokers[$displayName]->$dbName) ? $brokers[$displayName]->$dbName : '' }}">
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Save button and confirmation badge -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary text-nowrap px-3" style="max-width: 100px;">Save</button>
                                    <span class="badge {{ isset($brokers[$displayName]) ? ($brokers[$displayName]->confirmed ? 'bg-primary' : 'bg-secondary') : 'bg-secondary' }}">
                                        {{ isset($brokers[$displayName]) ? ($brokers[$displayName]->confirmed ? 'Confirmed' : 'Unconfirmed') : 'Unconfirmed' }}
                                    </span>
                                </div>
                            </div>
                        </form>

                        @endforeach
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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inject broker name into form before submission
        const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`${brokerName} saved successfully.`);
                    // Optionally, update the UI to reflect changes
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));

        });
    });

        // Handle AJAX for enable switch
        const switches = document.querySelectorAll('.form-check-input');
        switches.forEach(switchInput => {
            switchInput.addEventListener('change', function () {
                const brokerCard = this.closest('.broker-card');
                if (brokerCard) {
                    const brokerName = brokerCard.querySelector('h5').innerText;

                    // Prepare AJAX request
                    const formData = new FormData();
                    formData.append('broker', brokerName);
                    formData.append('enabled', this.checked ? 1 : 0);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('toggle_broker_status') }}', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(`${brokerName} updated successfully.`);
                        } else {
                            console.error(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('')}}/storage/assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
