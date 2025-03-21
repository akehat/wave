
@php
  $active=$active??"";
@endphp
<nav class="navbar navbar-main navbar-expand-lg px-2 py-2 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">

        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-2 py-2 me-sm-6 me-5">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{$active}}</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">{{$active}}</h6>

      </nav>
      <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">

            {{-- <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div> --}}

        </div>
        <ul class="navbar-nav  justify-content-end">
          {{-- <li class="nav-item d-flex align-items-center">
            <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Online Builder</a>
          </li>
          <li class="mt-2">
            <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          </li> --}}
           <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </a>
          </li>
          <li class="nav-item px-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0">
              <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
            </a>
          </li>
          <li class="nav-item dropdown pe-2 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell cursor-pointer"></i>
            </a>

             <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
              <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                      <img src="{{url('')}}/storage/assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">New message</span> from Laur
                      </h6>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        13 minutes ago
                      </p>
                    </div>
                  </div>
                </a>
              </li>
             <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                      <img src="{{url('')}}/storage/assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">New album</span> by Travis Scott
                      </h6>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        1 day
                      </p>
                    </div>
                  </div>
                </a>
              </li>
               <li>
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                      <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>credit-card</title> <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero"> <g transform="translate(1716.000000, 291.000000)"> <g transform="translate(453.000000, 454.000000)"> <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path> <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path> </g> </g> </g> </g> </svg>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        Payment successfully completed
                      </h6>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        2 days
                      </p>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item d-flex align-items-center @if(Route::is('general')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('general') }}" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-cog me-sm-1 @if(Route::is('general')) text-white h6 @endif" tooltip="General"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('general')) text-white h6 @endif">General</span>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center @if(Route::is('site')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('site') }}" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-globe me-sm-1 @if(Route::is('site')) text-white h6 @endif" tooltip="Site"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('site')) text-white h6 @endif">Site</span>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center @if(Route::is('menu')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('menu') }}" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-list me-sm-1 @if(Route::is('menu')) text-white h6 @endif" tooltip="Menu"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('menu')) text-white h6 @endif">Menu</span>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center @if(Route::is('contact')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('contact') }}" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-envelope me-sm-1 @if(Route::is('contact')) text-white h6 @endif" tooltip="Contact"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('contact')) text-white h6 @endif">Contact</span>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center @if(Route::is('howto')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('howto') }}" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-question-circle me-sm-1 @if(Route::is('howto')) text-white h6 @endif" tooltip="How To"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('howto')) text-white h6 @endif">How To</span>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center @if(Route::is('logout')) btn btn-primary p-0 pt-3 @endif">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-body font-weight-bold px-2 py-2 h2 mx-1">
                <i class="fa fa-user me-sm-1 @if(Route::is('logout')) text-white h6 @endif" tooltip="Log-Out"></i>
                <span class="d-sm-inline d-none h5 @if(Route::is('logout')) text-white h6 @endif">Log-Out</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
        </li>


        </ul>
      </div>
    </div>
  </nav>
  <script>
 document.addEventListener("DOMContentLoaded", function () {
  var id=1
  // Function to create tooltip
  function createTooltip(element) {
    const tooltipText = element.getAttribute("tooltip");
    if (!tooltipText) return;

    // Create tooltip element
    const tooltip = document.createElement("div");
    tooltip.textContent = tooltipText;
    tooltip.style.position = "absolute";
    tooltip.style.backgroundColor = "#333";
    tooltip.style.color = "#fff";
    tooltip.style.padding = "5px 10px";
    tooltip.style.borderRadius = "4px";
    tooltip.style.fontSize = "12px";
    tooltip.style.pointerEvents = "none";
    tooltip.style.whiteSpace = "nowrap";
    tooltip.style.zIndex = "1000";
    tooltip.setAttribute("id","tooltip"+id)
    // Append tooltip to body
    document.body.appendChild(tooltip);

    // Position tooltip near the element
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + window.pageXOffset + "px";
    tooltip.style.top = rect.top + window.pageYOffset - tooltip.offsetHeight - 5 + "px";

    // Store tooltip in the element's dataset for easy access
    element.dataset.tooltipElement = id++;
  }

  // Function to remove tooltip
  function removeTooltip(element) {
    var tooltip = element.dataset.tooltipElement;
    tooltip = document.getElementById("tooltip"+tooltip);
    if (tooltip) {
      tooltip.remove();
      delete element.dataset.tooltipElement;
    }
  }

  // Attach events to elements with tooltip attribute
  document.querySelectorAll("[tooltip]").forEach((element) => {
    element.addEventListener("mouseenter", () => createTooltip(element));
    element.addEventListener("mouseout", () => removeTooltip(element));
  });
});

  </script>
