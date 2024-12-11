<!--header section start -->
<div class="header_section">
    <div class="container-fluid">
       <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="logo"><a href="home"><img src="{{ asset('html') }}/images/m.png"></a></div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
             <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/cart') }}" class="nav-item nav-link {{ request()->is('cart') ? 'active' : '' }}">Cart</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/shop') }}" class="nav-item nav-link {{ request()->is('shop') ? 'active' : '' }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/contact') }}" class="nav-item nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
                </li>

             </ul>
             <!-- Header di view blade Anda -->
             <div class="navbar-nav ml-auto py-0">
                @guest('customer')
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('showRegister') }}" class="nav-item nav-link">Register</a>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::guard('customer')->user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('profile-form').submit();">
                                Profile
                            </a>
                            <a class="dropdown-item" href="{{ route('orderHistory') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('order-history-form').submit();">
                                Order History
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="order-history-form" action="{{ route('orderHistory') }}" method="GET" class="d-none">
                                @csrf
                            </form>
                            <form id="profile-form" action="{{ route('profile') }}" method="GET" class="d-none">
                                @csrf
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
          </div>
       </nav>
    </div>
 </div>
 <!--header section end -->

