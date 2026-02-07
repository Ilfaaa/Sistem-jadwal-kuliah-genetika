<!-- HEADER -->

@include('layouts.partials.header');
@include('layouts.partials.sidebar');
@include('layouts.partials.navbar');

<main>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: #FDE8CD; color: #433520;">
    @yield('content')
</main>

@include('layouts.partials.footer');