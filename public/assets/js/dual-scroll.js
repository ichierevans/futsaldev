document.addEventListener('DOMContentLoaded', () => {
    const dualScrollContainers = document.querySelectorAll('.dual-scroll-container');

    dualScrollContainers.forEach(container => {
        const verticalContainer = container.querySelector('.vertical-scroll-container');
        const horizontalContainer = container.querySelector('.horizontal-scroll-container');
        const verticalItems = verticalContainer.querySelectorAll('.vertical-item');
        const horizontalCards = horizontalContainer.querySelectorAll('.scroll-card');

        // Vertical scroll interaction
        verticalItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                // Remove active class from all vertical items
                verticalItems.forEach(v => v.classList.remove('active'));
                
                // Add active class to clicked item
                item.classList.add('active');

                // Scroll horizontal container to corresponding card
                if (horizontalCards[index]) {
                    horizontalCards[index].scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest', 
                        inline: 'center' 
                    });
                }
            });
        });

        // Horizontal scroll interaction
        let isDragging = false;
        let startX;
        let scrollLeft;

        horizontalContainer.addEventListener('mousedown', (e) => {
            isDragging = true;
            horizontalContainer.style.cursor = 'grabbing';
            startX = e.pageX - horizontalContainer.offsetLeft;
            scrollLeft = horizontalContainer.scrollLeft;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - horizontalContainer.offsetLeft;
            const walk = (x - startX) * 2;
            horizontalContainer.scrollLeft = scrollLeft - walk;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            horizontalContainer.style.cursor = 'grab';
        });

        // Touch events for mobile
        horizontalContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            scrollLeft = horizontalContainer.scrollLeft;
        }, { passive: true });

        horizontalContainer.addEventListener('touchmove', (e) => {
            const x = e.touches[0].clientX;
            const walk = (x - startX) * 1.5;
            horizontalContainer.scrollLeft = scrollLeft - walk;
        }, { passive: true });

        // Scroll sync for horizontal container
        horizontalContainer.addEventListener('scroll', () => {
            // Calculate which card is most in view
            const containerWidth = horizontalContainer.clientWidth;
            const scrollPosition = horizontalContainer.scrollLeft;
            
            horizontalCards.forEach((card, index) => {
                const cardLeft = card.offsetLeft;
                const cardWidth = card.clientWidth;
                
                if (
                    scrollPosition >= cardLeft - containerWidth / 2 && 
                    scrollPosition < cardLeft + cardWidth - containerWidth / 2
                ) {
                    // Update corresponding vertical item
                    verticalItems.forEach(v => v.classList.remove('active'));
                    verticalItems[index]?.classList.add('active');
                }
            });
        });

        // Initial setup
        if (verticalItems.length > 0) {
            verticalItems[0].classList.add('active');
        }

        // Ensure horizontal container is initially set to grab cursor
        horizontalContainer.style.cursor = 'grab';
    });
}); 