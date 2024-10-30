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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
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
        .id-column {
           max-width: 40px; /* or use max-width: 5ch; for character-based width */
           white-space: nowrap;
           overflow: hidden;
           text-overflow: ellipsis;
       }
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
  @component('components.sidenav',["active"=>"dashboard"])
  @endcomponent
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @component('components.navbar',["active"=>"dashboard"])
  @endcomponent
  <div class="card py-4">
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-center">
            <!-- Profit Calculator Frame -->




            <!-- Trading Chart Frame -->
            <div class="text-center m-3" style="width: 100%;">
                <iframe
                    frameborder="0"
                    referrerpolicy="no-referrer"
                    scrolling="no"
                    height="480"
                    width="1000"
                    allowtransparency="true"
                    marginwidth="0"
                    marginheight="0"
                    src="https://ssltvc.investing.com/?pair_ID=8849&height=480&width=1000&interval=300&plotStyle=area&domain_ID=68&lang_ID=68&timezone_ID=6">
                </iframe>
            </div>
            <div class="text-center m-3" style="width: 400px;">
                <iframe
                    frameborder="0"
                    referrerpolicy="no-referrer"
                    scrolling="no"
                    height="480"
                    width="100%"
                    allowtransparency="true"
                    marginwidth="0"
                    marginheight="0"
                    src="https://ssltools.investing.com/profit-calculator/index.php?force_lang=68&acc=12&pair=1">
            </iframe>
            </div>
            <div class="text-center m-3" style="width: 400px;">
            <iframe frameborder="0"  referrerpolicy="no-referrer" scrolling="no" height="274" width="425" allowtransparency="true" marginwidth="0" marginheight="0" src="https://ssltools.investing.com/technical_summary.php?pairs=&curr-name-color=%230059B0&fields=5m,1h,1d&force_lang=68"></iframe><br /><div style="width:425px;"><span style="float:left"><span style="font-size: 11px;color: #333333;text-decoration: none;">This Technical Analysis is powered by <a href="https://ca.investing.com/" rel="nofollow" target="_blank" style="font-size: 11px;color: #06529D; font-weight: bold;" class="underline_link">Investing.com Canada</a></span></span></div>
            </div>
        </div>

    </div>
    <center><h5>Your Stocks Data</h5></center>
    <div class="table-responsive p-0">
        <table id='stocks-table'></table>
      </div>
    <center><h5>Your Accounts Data</h5></center>
      <div class="table-responsive p-0">
        <table id='accounts-table'></table>
      </div>
</div>
<script>
async function fetchAndDisplayUserData() {
    try {
        // Fetch the data from the Laravel route
        const response = await fetch(`/user-data/`);
        const data = await response.json();

        // Store data in global variables for future use
        accounts = data.accounts;
        stocks = data.stocks;

        // Initialize DataTable for Accounts if it hasn't been created yet
        if (!$.fn.DataTable.isDataTable('#accounts-table')) {
            $('#accounts-table').DataTable({
                data: accounts,
                columns: [
                    { title: "ID", data: "id"},
                    { title: "Account Name", data: "account_name" },
                    { title: "Broker Name", data: "broker_name" },
                    { title: "AcKrawk Island Postcards 10 Packcount Number", data: "account_number" },
                    {
                        title: "Meta",
                        data: "meta",
                        render: function(data) {
                            return data ? JSON.stringify(data) : 'N/A';
                        }
                    }
                ],
                "columnDefs": [
                    {
                        "width": "60px",
                        "targets": 0
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
                    }
                ],
                "columnDefs": [
                    {
                        "width": "60px",
                        "targets": 0
                    }
                ]

            });
        } else {
            // Update data if DataTable already exists
            $('#stocks-table').DataTable().clear().rows.add(stocks).draw();
        }
    } catch (error) {
        console.error('Error fetching user data:'+ error);
    }
}
fetchAndDisplayUserData()
</script>
  <!--   Core JS Files   -->
  <script src="{{url('')}}/storage/assets/js/core/popper.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/core/bootstrap.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="{{url('')}}/storage/assets/js/plugins/chartjs.min.js"></script>
  <script>
   // JavaScript AJAX call to fetch data from the `sendData` endpoint
async function fetchDataAndRenderCharts() {
  try {
    const response = await fetch('/your-endpoint', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        broker: 'example-broker',
        type: 'account'
      })
    });

    const result = await response.json();

    if (result.message) {
      console.log(result.message);

      // Assuming result.data contains an array of data points for the chart
      updateChart(result.data);
    } else {
      console.error(result.error);
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
}

// Function to update the chart with new data
function updateChart(data) {
  const ctx = document.getElementById("chart-bars").getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: data.labels,  // Labels from your fetched data
      datasets: [{
        label: "Sales",
        data: data.values,  // Data points from your fetched data
        backgroundColor: "rgba(255, 255, 255, .8)",
      }]
    },
    options: { /* chart options */ }
  });
}

fetchDataAndRenderCharts();

  </script>
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
