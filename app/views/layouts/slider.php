    <!-- -----------------------SLIDER---------------------------------------------- -->
<section class="sliders">
    <div class="aspect-ratio-169 sliders-img">
        <img src="/public/image/silde1.jpg" alt="Slide 1" class="slider-image active">
        <img src="/public/image/slide2.jpg" alt="Slide 2" class="slider-image ">
        <img src="/public/image/slide3.jpg" alt="Slide 3" class="slider-image ">
        <img src="/public/image/slide4.jpg" alt="Slide 4" class="slider-image ">
        <img src="/public/image/slide5.jpg" alt="Slide 5" class="slider-image ">
    </div>
    <div class="dot-container">
        <div class="dot active" data-slide="0"></div>
        <div class="dot" data-slide="1"></div>
        <div class="dot" data-slide="2"></div>
        <div class="dot" data-slide="3"></div>
        <div class="dot" data-slide="4"></div>
    </div>
    
    <!-- Navigation arrows -->
    <div class="slider-nav">
        <button class="slider-btn prev-btn" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-btn next-btn" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</section>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slider-image');
const dots = document.querySelectorAll('.dot');
const totalSlides = slides.length;

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Show current slide
    slides[index].classList.add('active');
    dots[index].classList.add('active');
}

function changeSlide(direction) {
    currentSlide += direction;
    
    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }
    
    showSlide(currentSlide);
}

// Dot navigation
dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        currentSlide = index;
        showSlide(currentSlide);
    });
});

// Auto slide
let autoSlideInterval = setInterval(() => {
    changeSlide(1);
}, 5000);

// Pause auto slide on hover
const sliderContainer = document.querySelector('.sliders');
sliderContainer.addEventListener('mouseenter', () => {
    clearInterval(autoSlideInterval);
});

sliderContainer.addEventListener('mouseleave', () => {
    autoSlideInterval = setInterval(() => {
        changeSlide(1);
    }, 5000);
});
</script>
<!-- -----------------------------------slider----------------------- -->