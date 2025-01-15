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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
<style>
  /* General body styles */
body {
  font-family: 'Arial', sans-serif;
  background-color: #f8f9fa;
  color: #333;
  margin: 0;
  padding: 0;
}

/* Outer container styles */
.container {
  margin-top: 20px;
  padding: 15px;
}

/* Card styles */
.card {
  border: 2px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  background-color: #ffffff;
}

.card:hover {
  transform: scale(1.02);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

/* Card header styles */
.card-header {
  background-color: #007bff;
  color: #fff;
  padding: 15px;
  border-bottom: 2px solid #0056b3;
  border-radius: 10px 10px 0 0;
}

.card-header h5 {
  margin: 0;
  font-size: 18px;
  font-weight: bold;
  color: white;
}

/* Card body styles */
.card-body {
  padding: 20px;
}

.card-body ul {
  list-style-type: none;
  padding: 0;
}

.card-body ul li {
  margin-bottom: 15px;
  padding: 10px;
  background-color: #f1f1f1;
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Profile section */
.card-body p {
  margin: 10px 0;
  font-size: 15px;
  line-height: 1.6;
}

.card-body p strong {
  color: #007bff;
}

/* List styling for Payments, Chats, and Credit Cards */
.card-body ul li strong {
  display: inline-block;
  width: 120px;
  font-weight: bold;
  color: #555;
}

.card-body ul li:last-child {
  margin-bottom: 0;
}

/* Customizing Chat section */
.card-body ul li:hover {
  background-color: #e9ecef;
}

/* Buttons and interactive elements */
button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 15px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}

/* Responsiveness */
@media (max-width: 768px) {
  .container {
    padding: 10px;
  }

  .card-header h5 {
    font-size: 16px;
  }

  .card-body p,
  .card-body ul li {
    font-size: 14px;
  }
}

/* Borders around all cards as a group */
.row {
  margin-bottom: 20px;
  border: 3px solid #ccc;
  border-radius: 15px;
  padding: 20px;
  background-color: #fff;
}

/* Row hover effect */
.row:hover {
  border-color: #007bff;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* Navbar and Sidenav Adjustments (if applicable) */
.g-sidenav-show .sidenav {
  background-color: #0056b3;
}

.g-sidenav-show .sidenav a {
  color: #fff;
  text-decoration: none;
}

.g-sidenav-show .sidenav a:hover {
  text-decoration: underline;
}

/* Footer alignment */
footer {
  text-align: center;
  margin-top: 20px;
  padding: 10px;
  background-color: #f1f1f1;
  font-size: 14px;
  color: #555;
}
.form-control {
  border:2px solid black;
  padding-left:5px;
}
  </style>
<body class="g-sidenav-show bg-gray-200">
  @component('components.sidenav',["active"=>"profile"])
  @endcomponent
  <div class="main-content position-relative max-height-vh-100 h-100">
    @component('components.navbar',["active"=>"profile"])
    @endcomponent
    <div class="container">
      <h1>User Profile</h1>

      <div class="row">
        <!-- User Profile Information -->
        <div class="col-12 col-xl-4 mb-4">
        <div class="card h-100">
  <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
    <h5>Profile</h5>
    <button class="btn btn-sm btn-outline-light" id="editProfileBtn">
      <i class="fas fa-pencil-alt" style="font-size:1rem;"></i>
    </button>
  </div>
  <div class="card-body">
    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
      @csrf
      @method('PUT')

      <div class="form-group mb-3">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $profile->name ?? $user->username ?? '' }}">
      </div>
      <div class="form-group mb-3">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ $profile->email ?? $user->email ?? '' }}">
      </div>
      <div class="form-group mb-3">
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" class="form-control" value="{{ $profile->phone ?? '' }}">
      </div>
      <div class="form-group mb-3">
        <label for="picture">Profile Picture:</label>
        <input type="file" id="picture" name="picture" class="form-control" accept="image/*" onchange="previewImage(event)">
        <img id="imagePreview" class="img-thumbnail mt-2" style="display: none; max-height: 150px;" alt="Preview">
      </div>
      <div class="form-group mb-3">
        <label for="auto_buy_feature">Auto Buy Feature:</label>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="auto_buy_feature" name="auto_buy_feature" value="1" {{ $profile->auto_buy_feature ? 'checked' : '' }}>
          <label class="form-check-label" for="auto_buy_feature">{{ $profile->auto_buy_feature ? 'Enabled' : 'Disabled' }}</label>
        </div>
      </div>
      <div class="form-group mb-3">
        <label for="auto_sell_toggle">Auto Sell Toggle:</label>
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="auto_sell_toggle" name="auto_sell_toggle" value="1" {{ $profile->auto_sell_toggle ? 'checked' : '' }}>
          <label class="form-check-label" for="auto_sell_toggle">{{ $profile->auto_sell_toggle ? 'Enabled' : 'Disabled' }}</label>
        </div>
      </div>
      <button type="button" id="cancelEditBtn" class="btn btn-secondary me-2">Cancel</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>

    <div id="profileDetails">
      <img class="w-8 h-8 rounded-full" src="{{ $profile->picture ?? $user->avatar() . '?' . time() }}" alt="{{ $user->name }}'s Avatar">
      <p><strong>Name:</strong> {{ $profile->name ?? $user->username }}</p>
      <p><strong>Email:</strong> {{ $profile->email ?? $user->email }}</p>
      <p><strong>Phone:</strong> {{ $profile->phone ?? 'Not Provided' }}</p>
      <p><strong>Auto Buy Feature:</strong> {{ $profile->auto_buy_feature ? 'Enabled' : 'Disabled' }}</p>
      <p><strong>Auto Sell Toggle:</strong> {{ $profile->auto_sell_toggle ? 'Enabled' : 'Disabled' }}</p>
    </div>
  </div>
</div>
</div>

<script>
    function previewImage(event) {
      const preview = document.getElementById('imagePreview');
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none';
      }
    }
  document.addEventListener("DOMContentLoaded", function () {
  
    const editProfileBtn = document.getElementById("editProfileBtn");
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    const profileForm = document.getElementById("profileForm");
    const profileDetails = document.getElementById("profileDetails");
    
    const autoBuyFeatureToggle = document.getElementById("auto_buy_feature");
    const autoSellToggle = document.getElementById("auto_sell_toggle");

    // Function to update toggle label based on checkbox state
    function updateToggleLabel(toggle, label) {
        if (toggle.checked) {
            label.textContent = "Enabled"; // Update label to enabled
        } else {
            label.textContent = "Disabled"; // Update label to disabled
        }
    }

    // Event listener for toggles
    autoBuyFeatureToggle.addEventListener("change", function () {
        const label = autoBuyFeatureToggle.nextElementSibling; // Get the label next to the toggle
        updateToggleLabel(autoBuyFeatureToggle, label);
    });

    autoSellToggle.addEventListener("change", function () {
        const label = autoSellToggle.nextElementSibling; // Get the label next to the toggle
        updateToggleLabel(autoSellToggle, label);
    });
    var enabled=false;
    editProfileBtn.addEventListener("click", function () {
        if(!enabled){
          profileDetails.style.display = "none"; // Hide profile details
          profileForm.style.display = "block"; // Show profile form
          enabled=true;
        }else{
          profileDetails.style.display = "block"; // Hide profile details
          profileForm.style.display = "none"; // Show profile form
          enabled=false;
        }        
    });

    cancelEditBtn.addEventListener("click", function () {
        profileForm.style.display = "none"; // Hide profile form
        profileDetails.style.display = "block"; // Show profile details
    });
});
</script>


        <!-- Payments Information -->
        <div class="col-12 col-xl-4 mb-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h5>Payments</h5>
            </div>
            <div class="card-body">
              @if($payments->isEmpty())
                  <p>No payments found.</p>
              @else
                  <ul>
                      @foreach($payments as $payment)
                          <li>
                              <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
                              <strong>Date:</strong> {{ $payment->created_at->format('M d, Y') }}
                          </li>
                      @endforeach
                  </ul>
              @endif
            </div>
          </div>
        </div>

        <!-- Chat Information -->
        <!-- Chat Information -->
<div class="col-12 col-xl-4 mb-4">
  <div class="card h-100">
    <div class="card-header pb-0 p-3">
      <h5>Chats</h5>
      <button id="startChattingBtn" class="btn btn-sm btn-primary">Start Chatting</button>
    </div>
    <div class="card-body">
      @if($chats->isEmpty())
        <p>No chats found.</p>
      @else
        <ul>
          @foreach($chats as $chat)
            <li>
              <strong>From:</strong> {{ $chat->user_id }} <br>
              <strong>To:</strong> {{ $chat->to_user_id }} <br>
              <strong>Message:</strong> {{ $chat->message }}
            </li>
          @endforeach
        </ul>
      @endif

      <!-- Chat Form -->
      <form id="chatForm" action="#" method="POST" style="display: none;">
        @csrf
        <div class="form-group mb-3">
          <label for="recipient">Recipient:</label>
          <input type="text" id="recipient" name="recipient" class="form-control" placeholder="Enter recipient username or ID">
          <ul id="userSuggestions" class="list-group mt-1" style="display: none;"></ul>
        </div>
        <div class="form-group mb-3">
          <label for="message">Message:</label>
          <textarea id="message" name="message" class="form-control" rows="3" placeholder="Type your message here"></textarea>
        </div>
        <button type="button" id="cancelChatBtn" class="btn btn-secondary me-2">Cancel</button>
        <button type="submit" class="btn btn-primary">Send</button>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const startChattingBtn = document.getElementById("startChattingBtn");
  const chatForm = document.getElementById("chatForm");
  const cancelChatBtn = document.getElementById("cancelChatBtn");
  const userSuggestions = document.getElementById("userSuggestions");
  const recipientInput = document.getElementById("recipient");

  let isChatFormVisible = false;

  // Toggle Chat Form visibility
  startChattingBtn.addEventListener("click", function () {
    if (!isChatFormVisible) {
      chatForm.style.display = "block";
      startChattingBtn.textContent = "Hide Chatting Form";
      isChatFormVisible = true;
    } else {
      chatForm.style.display = "none";
      startChattingBtn.textContent = "Start Chatting";
      isChatFormVisible = false;
    }
  });

  // Cancel button functionality
  cancelChatBtn.addEventListener("click", function () {
    chatForm.style.display = "none";
    startChattingBtn.textContent = "Start Chatting";
    isChatFormVisible = false;
  });

  // AJAX user lookup on recipient input
  recipientInput.addEventListener("input", function () {
    const query = recipientInput.value;

    if (query.length < 2) {
      userSuggestions.style.display = "none";
      return;
    }

    fetch(`{{url("/user-lookup")}}?query=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        userSuggestions.innerHTML = ""; // Clear existing suggestions

        if (data.length > 0) {
          data.forEach(user => {
            const suggestion = document.createElement("li");
            suggestion.className = "list-group-item list-group-item-action";
            suggestion.textContent = `${user.name}`;
            suggestion.addEventListener("click", function () {
              recipientInput.value = user.name;
              userSuggestions.style.display = "none";
            });
            userSuggestions.appendChild(suggestion);
          });
          userSuggestions.style.display = "block";
        } else {
          userSuggestions.style.display = "none";
        }
      })
      .catch(error => {
        console.error("Error during user lookup:", error);
      });
  });

  // Hide suggestions when clicking outside
  document.addEventListener("click", function (event) {
    if (!recipientInput.contains(event.target) && !userSuggestions.contains(event.target)) {
      userSuggestions.style.display = "none";
    }
  });
});
</script>

      </div>

      <div class="row">
  <!-- Credit Card Information -->
  <div class="col-12 col-xl-4 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
        <h5>Credit Card Information</h5>
        <button class="btn btn-sm btn-outline-light" id="editCardInfoBtn">
          <i class="fas fa-pencil-alt" style="font-size: 1rem;"></i>
        </button>
      </div>
      <div class="card-body">
        <!-- Credit Card Form -->
        <form id="creditCardForm" action="{{ route('cards.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
          @csrf
          <div class="form-group mb-3">
            <label for="cardholder_name">Cardholder Name:</label>
            <input type="text" id="cardholder_name" name="cardholder_name" class="form-control" placeholder="Enter cardholder name">
          </div>
          <div class="form-group mb-3">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" class="form-control" placeholder="Enter card number">
          </div>
          <div class="form-group mb-3">
            <label for="expiry_month">Expiry Month:</label>
            <input type="number" id="expiry_month" name="expiry_month" class="form-control" placeholder="MM">
          </div>
          <div class="form-group mb-3">
            <label for="expiry_year">Expiry Year:</label>
            <input type="number" id="expiry_year" name="expiry_year" class="form-control" placeholder="YYYY">
          </div>
          <div class="form-group mb-3">
            <label for="card_type">Card Type:</label>
            <select id="card_type" name="card_type" class="form-control">
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
              <option value="Discover">Discover</option>
            </select>
          </div>
          <button type="button" id="cancelCardEditBtn" class="btn btn-secondary me-2">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <!-- Credit Card Details -->
        <div id="creditCardDetails">
          @if($cards->isEmpty())
            <p>No credit card information found.</p>
          @else
            <ul>
              @foreach($cards as $card)
                <li>
                  <strong>Cardholder Name:</strong> {{ $card->cardholder_name }}<br>
                  <strong>Card Number:</strong> **** **** **** {{ substr($card->card_number, -4) }}<br>
                  <strong>Expiry Date:</strong> {{ $card->expiry_month }}/{{ $card->expiry_year }}<br>
                  <strong>Type:</strong> {{ $card->card_type }}
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const editCardInfoBtn = document.getElementById("editCardInfoBtn");
    const cancelCardEditBtn = document.getElementById("cancelCardEditBtn");
    const creditCardForm = document.getElementById("creditCardForm");
    const creditCardDetails = document.getElementById("creditCardDetails");
    let enabled = false;

    editCardInfoBtn.addEventListener("click", function () {
      if (!enabled) {
        creditCardDetails.style.display = "none"; // Hide credit card details
        creditCardForm.style.display = "block"; // Show credit card form
        enabled = true;
      } else {
        creditCardDetails.style.display = "block"; // Show credit card details
        creditCardForm.style.display = "none"; // Hide credit card form
        enabled = false;
      }
    });

    cancelCardEditBtn.addEventListener("click", function () {
      creditCardForm.style.display = "none"; // Hide credit card form
      creditCardDetails.style.display = "block"; // Show credit card details
    });
  });
</script>

    </div>
  </div>
    </div>
   
  </div>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
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