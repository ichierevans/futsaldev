document.addEventListener('DOMContentLoaded', function () {
    const fieldsWrapper = document.querySelector('.fields-wrapper');
    const prevBtn = document.querySelector('.field-prev');
    const nextBtn = document.querySelector('.field-next');

    const scrollAmount = 320;

    // Fungsi scroll halus custom
    function smoothScrollBy(element, distance, duration = 500) {
        const start = element.scrollLeft;
        const startTime = performance.now();

        function animate(time) {
            const elapsed = time - startTime;
            const progress = Math.min(elapsed / duration, 1); // max 1
            const ease = 0.5 * (1 - Math.cos(Math.PI * progress)); // ease-in-out

            element.scrollLeft = start + distance * ease;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    // Tombol klik kiri-kanan
    nextBtn.addEventListener('click', function () {
        smoothScrollBy(fieldsWrapper, scrollAmount, 600);
    });

    prevBtn.addEventListener('click', function () {
        smoothScrollBy(fieldsWrapper, -scrollAmount, 600);
    });

    // Mouse wheel horizontal scroll
    let isScrolling = false;
    fieldsWrapper.addEventListener('wheel', function (e) {
        if (e.deltaY !== 0) {
            e.preventDefault();

            if (!isScrolling) {
                isScrolling = true;
                smoothScrollBy(fieldsWrapper, e.deltaY * 2, 700); // bisa atur kecepatan dan multiplier
                setTimeout(() => isScrolling = false, 300); // biar nggak terlalu sering trigger
            }
        }
    }, { passive: false });
});
