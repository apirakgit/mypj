<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home','en') }}">
            <img src="{{ asset('images') }}/logo_evolt.svg" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div> -->
                  {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <!-- <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div> -->
                    <a href="{{ route('logout','en') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home','en') }}">
                            <img src="{{ asset('images') }}/logo_evolt.svg">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <!-- <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form> -->
            <!-- Navigation -->
            <script type="text/javascript">
              /*$(function(){

                var parts = window.location.href.split('/');
                var lastSegment = parts.pop() || parts.pop();  // handle potential trailing slash

                ( lastSegment == 'transaction-list' ) ? $(".transaction").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'admin' ) ? $(".transaction").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'customers' ) ? $(".customers").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'vinno' ) ? $(".vinno").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'vehicle' ) ? $(".vehicle").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'promotion' ) ? $(".promotion").css("background-color", "antiquewhite") : '';
                ( lastSegment == 'users-list' ) ? $(".users-list").css("background-color", "antiquewhite") : '';

              });*/
            </script>
            <ul class="navbar-nav ">
                <li class="nav-item transaction">
                    <a class="nav-link transaction-list active" href="{{ route('transaction','en') }}">
                      <i class="ni ni-bullet-list-67 text-default"></i>
                      <span class="nav-link-text">Transaction List</span>
                    </a>
                </li>
                <li class="nav-item customers">
                    <a class="nav-link customers" href="{{ url('en/customers') }}">
                      <i class="ni ni-chart-bar-32 text-default"></i>
                      <span class="nav-link-text">Customer Master Management</span>
                    </a>
                </li>
                <li class="nav-item vinno">
                    <a class="nav-link vinno" href="{{ url('en/vinno') }}">
                      <i class="ni ni-controller text-default"></i>
                      <span class="nav-link-text">VIN No Management</span>
                    </a>
                </li>
                <li class="nav-item vehicle">
                    <a class="nav-link vehicle" href="{{ url('en/vehicle') }}">
                      <i class="ni ni-folder-17 text-default"></i>
                      <span class="nav-link-text">Vehicle Brand Management</span>
                    </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                  <i class="ni ni-favourite-28 text-default"></i>
                  <span class="nav-link-text">Promotion Management</span>
                  </a>
                  <div class="collapse coll_promotion" id="navbar-examples">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item promotion">
                          <a class="nav-link promotion" href="{{ url('en/promotion') }}">
                            <span class="nav-link-text">Promotion brand</span>
                          </a>
                      </li>
                      <li class="nav-item promotion">
                          <a class="nav-link promotion_campaign promotion_code promotion_code_list" href="{{ url('en/promotion_campaign') }}">
                            <span class="nav-link-text">Promotion campaign</span>
                          </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link partner" href="{{ url('en/partner') }}">
                      <i class="ni ni-favourite-28 text-default"></i>
                      <span class="nav-link-text">Partner API Management</span>
                    </a>
                </li>

                @if( auth()->user()->role == 'manager')
                <li class="nav-item users-list">
                    <a class="nav-link users-list" href="{{ route('users','en') }}">
                      <i class="ni ni-single-02 text-default"></i>
                      <span class="nav-link-text">User Management</span>
                    </a>
                </li>
                @endif
            </ul>
            <!-- Divider -->
            <!-- <hr class="my-3"> -->
            <!-- Heading -->
        </div>
    </div>
</nav>
