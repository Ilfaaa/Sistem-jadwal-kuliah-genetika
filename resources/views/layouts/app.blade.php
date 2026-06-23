<!-- HEADER -->
@include('layouts.partials.header')

@include('layouts.partials.sidebar')
@include('layouts.partials.navbar')

<div class="content-wrapper content-theme">
    <!-- Floating PlayStation-style Background Ornaments -->
    <div class="bg-ornaments-wrapper"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.bg-ornaments-wrapper');
        if (!wrapper) return;
        
        wrapper.innerHTML = '';
        
        // Shapes: Triangle, Square, Circle. Triangle is weighted higher (50% probability) to replace the lines/crosses.
        const shapes = ['ps-triangle', 'ps-triangle', 'ps-square', 'ps-circle'];
        const animations = ['psFloat1', 'psFloat2', 'psFloat3', 'psFloat4'];
        const count = 32; // Plenty of beautiful random ornaments
        
        for (let i = 0; i < count; i++) {
            const container = document.createElement('div');
            container.className = 'bg-ornament-container';
            
            // Generate truly random positions (using left/top percentages)
            const top = Math.random() * 92 + 3; // 3% to 95%
            const left = Math.random() * 92 + 3; // 3% to 95%
            const rotate = Math.random() * 360;
            const scale = 0.75 + Math.random() * 0.45; // size variation: 0.75x to 1.2x
            
            container.style.top = `${top}%`;
            container.style.left = `${left}%`;
            container.style.transform = `rotate(${rotate}deg) scale(${scale})`;
            
            const shape = shapes[Math.floor(Math.random() * shapes.length)];
            const animation = animations[Math.floor(Math.random() * animations.length)];
            const duration = 14 + Math.random() * 14; // 14s to 28s speed
            const delay = -Math.random() * 20; // out-of-sync starting phase
            
            const ornament = document.createElement('div');
            ornament.className = `bg-ornament ${shape}`;
            ornament.style.animation = `${animation} ${duration}s ease-in-out infinite alternate`;
            ornament.style.animationDelay = `${delay}s`;
            
            container.appendChild(ornament);
            wrapper.appendChild(container);
        }
    });
    </script>
    @yield('content')
</div>

{{-- PENTING: agar @push('scripts') di halaman-halaman (create/edit dll) KE-RENDER --}}
@stack('scripts')

@include('layouts.partials.footer')