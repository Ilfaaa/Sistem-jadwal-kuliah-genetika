<!-- HEADER -->
@include('layouts.partials.header')

@include('layouts.partials.sidebar')
@include('layouts.partials.navbar')

<div class="content-wrapper content-theme">
    <!-- Floating PlayStation-style Background Ornaments -->
    <div class="bg-ornaments-wrapper">
        <!-- Row 1 -->
        <div class="bg-ornament ps-triangle pos-1"></div>
        <div class="bg-ornament ps-square pos-2"></div>
        <div class="bg-ornament ps-circle pos-3"></div>
        <div class="bg-ornament ps-cross pos-4"></div>
        
        <!-- Row 2 -->
        <div class="bg-ornament ps-triangle pos-5"></div>
        <div class="bg-ornament ps-square pos-6"></div>
        <div class="bg-ornament ps-circle pos-7"></div>
        <div class="bg-ornament ps-cross pos-8"></div>
        
        <!-- Row 3 -->
        <div class="bg-ornament ps-triangle pos-9"></div>
        <div class="bg-ornament ps-square pos-10"></div>
        <div class="bg-ornament ps-circle pos-11"></div>
        <div class="bg-ornament ps-cross pos-12"></div>
        
        <!-- Row 4 -->
        <div class="bg-ornament ps-triangle pos-13"></div>
        <div class="bg-ornament ps-square pos-14"></div>
        <div class="bg-ornament ps-circle pos-15"></div>
        <div class="bg-ornament ps-cross pos-16"></div>
        
        <!-- Row 5 -->
        <div class="bg-ornament ps-triangle pos-17"></div>
        <div class="bg-ornament ps-square pos-18"></div>
        <div class="bg-ornament ps-circle pos-19"></div>
        <div class="bg-ornament ps-cross pos-20"></div>
    </div>
    @yield('content')
</div>

{{-- PENTING: agar @push('scripts') di halaman-halaman (create/edit dll) KE-RENDER --}}
@stack('scripts')

@include('layouts.partials.footer')