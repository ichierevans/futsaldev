// Enhanced Horizontal Scroll Handling
document.addEventListener('DOMContentLoaded', () => {
    function initializeHorizontalScroll(containerSelector) {
        const containers = document.querySelectorAll(containerSelector);
        
        containers.forEach(container => {
            // Scroll interaction variables
            let isDown = false;
            let startX;
            let scrollLeft;
            let velocityX = 0;
            let lastX = 0;
            let lastTime = Date.now();
            let momentumID;

            // Prevent default drag behavior on images
            const images = container.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('dragstart', (e) => e.preventDefault());
                img.style.pointerEvents = 'none';
            });

            // Scroll Indicator
            const createScrollIndicator = () => {
                const scrollIndicator = document.createElement('div');
                scrollIndicator.classList.add('scroll-indicator-container');
                const scrollThumb = document.createElement('div');
                scrollThumb.classList.add('scroll-indicator');
                scrollIndicator.appendChild(scrollThumb);
                container.parentNode.insertBefore(scrollIndicator, container.nextSibling);
                return scrollThumb;
            };

            const scrollThumb = createScrollIndicator();

            // Update scroll indicator
            const updateScrollIndicator = () => {
                const scrollPercentage = 
                    (container.scrollLeft / (container.scrollWidth - container.clientWidth)) * 100;
                scrollThumb.style.width = `${scrollPercentage}%`;
            };

            // Momentum scroll function
            const applyMomentumScroll = () => {
                if (Math.abs(velocityX) > 0.5) {
                    container.scrollLeft += velocityX;
                    velocityX *= 0.95; // Friction
                    updateScrollIndicator();
                    momentumID = requestAnimationFrame(applyMomentumScroll);
                } else {
                    cancelAnimationFrame(momentumID);
                }
            };

            // Mouse events
            container.addEventListener('mousedown', (e) => {
                e.preventDefault();
                isDown = true;
                container.classList.add('active');
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                
                // Reset momentum
                velocityX = 0;
                lastX = startX;
                lastTime = Date.now();
                
                cancelAnimationFrame(momentumID);
            });

            container.addEventListener('mouseleave', () => {
                isDown = false;
                container.classList.remove('active');
                container.style.cursor = 'grab';
                
                // Start momentum scroll
                momentumID = requestAnimationFrame(applyMomentumScroll);
            });

            container.addEventListener('mouseup', () => {
                isDown = false;
                container.classList.remove('active');
                container.style.cursor = 'grab';
                
                // Start momentum scroll
                momentumID = requestAnimationFrame(applyMomentumScroll);
            });

            container.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                
                const x = e.pageX - container.offsetLeft;
                const walk = (x - startX) * 2;
                container.scrollLeft = scrollLeft - walk;
                
                // Calculate velocity
                const currentTime = Date.now();
                const timeDelta = currentTime - lastTime;
                const distanceDelta = x - lastX;
                
                velocityX = distanceDelta / timeDelta;
                
                lastX = x;
                lastTime = currentTime;
                
                updateScrollIndicator();
            });

            // Touch events for mobile
            container.addEventListener('touchstart', (e) => {
                const touch = e.touches[0];
                startX = touch.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
                
                // Reset momentum
                velocityX = 0;
                lastX = startX;
                lastTime = Date.now();
            });

            container.addEventListener('touchmove', (e) => {
                const touch = e.touches[0];
                const x = touch.pageX - container.offsetLeft;
                const walk = (x - startX) * 2;
                container.scrollLeft = scrollLeft - walk;
                
                // Calculate velocity
                const currentTime = Date.now();
                const timeDelta = currentTime - lastTime;
                const distanceDelta = x - lastX;
                
                velocityX = distanceDelta / timeDelta;
                
                lastX = x;
                lastTime = currentTime;
                
                updateScrollIndicator();
            });

            container.addEventListener('touchend', () => {
                // Start momentum scroll
                momentumID = requestAnimationFrame(applyMomentumScroll);
            });

            // Initial scroll indicator setup
            updateScrollIndicator();
            container.addEventListener('scroll', updateScrollIndicator);
        });
    }

    // Initialize horizontal scroll for different containers
    initializeHorizontalScroll('.field-container');
    initializeHorizontalScroll('.horizontal-scroll-container');
}); 