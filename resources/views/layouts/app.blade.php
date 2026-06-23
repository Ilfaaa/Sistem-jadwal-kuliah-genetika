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
        
        const placed = [];
        const count = 28; // Optimized count of beautiful random ornaments
        const minDistance = 10; // 10% distance threshold prevents any overlaps
        
        for (let i = 0; i < count; i++) {
            let top, left;
            let attempts = 0;
            let valid = false;
            
            // Try to find a non-overlapping spot
            while (!valid && attempts < 300) {
                top = Math.random() * 88 + 5; // 5% to 93%
                left = Math.random() * 88 + 5; // 5% to 93%
                attempts++;
                
                valid = true;
                for (const pos of placed) {
                    const dx = pos.left - left;
                    const dy = pos.top - top;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < minDistance) {
                        valid = false;
                        break;
                    }
                }
            }
            
            if (!valid) continue; // Skip if no valid spot found
            
            placed.push({ top, left });
            
            const container = document.createElement('div');
            container.className = 'bg-ornament-container';
            
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