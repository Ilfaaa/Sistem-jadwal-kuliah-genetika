<!-- HEADER -->
@include('layouts.partials.header')

@include('layouts.partials.sidebar')
@include('layouts.partials.navbar')

<div class="content-wrapper content-theme">
    <!-- Floating Background Ornaments -->
    <div class="bg-ornaments-wrapper">
        <div class="bg-ornament shape-triangle shape-1"></div>
        <div class="bg-ornament shape-square shape-2"></div>
        <div class="bg-ornament shape-pentagon shape-3"></div>
        <div class="bg-ornament shape-circle shape-4"></div>
        <div class="bg-ornament shape-triangle shape-5"></div>
        <div class="bg-ornament shape-square shape-6"></div>
        <div class="bg-ornament shape-cross shape-7"></div>
        <div class="bg-ornament shape-pentagon shape-8"></div>
    </div>
    @yield('content')
</div>

{{-- PENTING: agar @push('scripts') di halaman-halaman (create/edit dll) KE-RENDER --}}
@stack('scripts')

@include('layouts.partials.footer')