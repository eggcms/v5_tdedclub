<div class="sidebar" data-color="orange" data-background-color="black" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{url('/home')}}" class="simple-text logo-normal">
      {{ __('Dashboard') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-spider text-danger"></i>
            <p>{{ __('ส่วนควบคุมระบบ') }}</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>
          <p>{{ __('ระบบการจัดการ') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse show" id="laravelExample">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'blogs' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/blogs') }}">
                    <span class="sidebar-mini"> <i class="fas fa-rss text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('ข่าวกีฬา') }} </span>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'analyze' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/analyze') }}">
                    <span class="sidebar-mini"> <i class="fas fa-volleyball-ball text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('บทวิเคราะห์') }} </span>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'youtube' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/youtube') }}">
                    <span class="sidebar-mini"> <i class="fab fa-youtube text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('คลิปยูทูป') }} </span>
                </a>
            </li>
            {{-- <li class="nav-item{{ $activePage == 'zean' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/zean') }}">
                    <span class="sidebar-mini"> <i class="fab fa-font-awesome text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('เซียนฟันธง') }} </span>
                </a>
            </li> --}}
            <li class="nav-item{{ $activePage == 'tstep' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/tstep') }}">
                    <span class="sidebar-mini"> <i class="fab fa-angellist text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('ทีเด็ดสเต็ป') }} </span>
                </a>
            </li>    
            {{-- <li class="nav-item{{ $activePage == 'ztstep' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/ztstep') }}">
                    <span class="sidebar-mini"> <i class="far fa-address-book text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('เซียนทีเด็ดสเต็ป') }} </span>
                </a>
            </li>  --}}
            <li class="nav-item{{ $activePage == 'setup' ? ' active' : '' }}">
                <a class="nav-link" href="{{ url('/setup') }}">
                    <span class="sidebar-mini"> <i class="fas fa-cogs text-danger"></i> </span>
                    <span class="sidebar-normal">{{ __('ตั้งค่า') }} </span>
                </a>
            </li>
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="fas fa-user text-danger"></i>
                    <span class="sidebar-normal"> {{ __('จัดการสมาชิก') }} </span>
                </a>
            </li>
            {{-- <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('profile.edit') }}">
                <i class="fas fa-user-cog text-danger"></i>
                <span class="sidebar-normal">{{ __('โปรไฟล์สมาชิก') }} </span>
              </a>
            </li> --}}

          </ul>
        </div>
      </li>
      {{-- <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('table') }}">
          <i class="material-icons">content_paste</i>
            <p>{{ __('Table List') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('typography') }}">
          <i class="material-icons">library_books</i>
            <p>{{ __('Typography') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('icons') }}">
          <i class="material-icons">bubble_chart</i>
          <p>{{ __('Icons') }}</p>
        </a>
      </li> --}}
      {{-- <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('map') }}">
          <i class="material-icons">location_ons</i>
            <p>{{ __('Maps') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'language' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('language') }}">
          <i class="material-icons">language</i>
          <p>{{ __('RTL Support') }}</p>
        </a>
      </li> --}}
      {{-- <li class="nav-item active-pro{{ $activePage == 'upgrade' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('upgrade') }}">
          <i class="material-icons">unarchive</i>
          <p>{{ __('Upgrade to PRO') }}</p>
        </a>
      </li> --}}
    </ul>
  </div>
</div>