<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">

  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="{{url("portal")}}" >
      {{-- <img src="{{url('')}}/storage/assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo"> --}}
      <span class="ms-1 font-weight-bold text-white">{{Auth::user()->name}}'s Dashboard</span>
    </a>
  </div>


  <hr class="horizontal light mt-0 mb-2">

  <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">

@php
  $active=$active??"";
@endphp
<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="dashboard")]) href="{{url("")}}/pages/dashboard">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">dashboard</i>
      </div>

    <span class="nav-link-text ms-1">Dashboard</span>
  </a>
</li>


<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="holdings")]) href="{{url("")}}/pages/holdings">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">table_view</i>
      </div>

    <span class="nav-link-text ms-1">Holdings</span>
  </a>
</li>

<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="brokersDefinition")]) href="{{url("")}}/pages/brokersDefinition">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">table_view</i>
      </div>

    <span class="nav-link-text ms-1">Broker Usage Definition</span>
  </a>
</li>

<li class="nav-item">
    <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="brokersAction")]) href="{{url("")}}/pages/brokersAction">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">table_view</i>
        </div>
      <span class="nav-link-text ms-1">Broker Action</span>
    </a>
  </li>


<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="billing")]) href="{{url("")}}/pages/billing">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">receipt_long</i>
      </div>

    <span class="nav-link-text ms-1">Billing</span>
  </a>
</li>


<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="notifications")]) href="{{url("")}}/pages/notifications">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">notifications</i>
      </div>

    <span class="nav-link-text ms-1">Notifications</span>
  </a>
</li>


    <li class="nav-item mt-3">
      <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
    </li>

<li class="nav-item">
  <a @class(['nav-link','text-white','active bg-gradient-primary'=>($active=="profile")]) href="{{url("")}}/pages/profile">

      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">person</i>
      </div>

    <span class="nav-link-text ms-1">Profile</span>
  </a>
</li>




    </ul>
  {{-- </div>

  <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    <div class="mx-3">
      <a class="btn btn-outline-primary mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
      <a class="btn bg-gradient-primary w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
    </div>
  </div> --}}

</aside>

