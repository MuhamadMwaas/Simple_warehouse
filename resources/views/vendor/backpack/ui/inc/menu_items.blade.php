{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-group" :link="backpack_url('user')" />
<x-backpack::menu-item title="Groups" icon="las la-sitemap" :link="backpack_url('groups')" />
<x-backpack::menu-item title="Items" icon="las la-tasks" :link="backpack_url('item')" />