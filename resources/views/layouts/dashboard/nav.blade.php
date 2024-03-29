<!-- ### $Topbar ### -->
<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li>
                <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                    <i class="ti-menu"></i>
                </a>
            </li>
        </ul>
        <ul class="nav-right">
            <li class="dropdown">
                <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                    <div class="peer">
                        <span class="fsz-sm">{{ __('Welcome') }}, {{ Auth::user()->name }}</span>
                    </div>
                    <div class="peer">
                        <i class="fsz-xs ti-arrow-circle-down mL-10"></i>
                    </div>
                </a>
                <ul class="dropdown-menu fsz-sm">
                    {{-- <li>
                        <a href="{{ route('profile') }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-settings mR-10"></i>
                            <span>Settings</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('profile') }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-user mR-10"></i>
                            <span>Account</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="email.html" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-email mR-10"></i>
                            <span>Messages</span>
                        </a>
                    </li> --}}
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ route('logout') }}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti-power-off mR-10"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
