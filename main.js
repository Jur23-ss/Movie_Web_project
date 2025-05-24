let header= document.querySelector("header");

window.addEventListener("scroll", function() {
  var header = document.querySelector("header");
  header.classList.toggle("shadow", window.scrollY > 0);
});

const menuIcon = document.querySelector('#menu-icon');
const navbar = document.querySelector('.navbar');
const closeIcon = document.querySelector('.ri-close-line');
const menuBars = document.querySelector('.ri-menu-line');

menuIcon.onclick = () => {
    // Toggle menu/close icons
    menuBars.classList.toggle('hidden');
    closeIcon.classList.toggle('hidden');
    
    // Toggle navbar visibility
    navbar.classList.toggle('active');
    
    // Prevent body scroll when menu is open
    document.body.classList.toggle('no-scroll');
};

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    if (!navbar.contains(e.target) && !menuIcon.contains(e.target)) {
        navbar.classList.remove('active');
        menuBars.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        document.body.classList.remove('no-scroll');
    }
});

// Close menu on resize
window.addEventListener('resize', () => {
    if (window.innerWidth > 774) {
        navbar.classList.remove('active');
        menuBars.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        document.body.classList.remove('no-scroll');
    }
});




var swiper = new Swiper(".home", {
    spaceBetween: 30,
    centeredSlides: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
   
  });


  //Swiper
  var swiper = new Swiper(".coming-container", {
    spaceBetween: 30,
    loop: true,
    autoplay: {
      delay: 55000,
      disableOnInteraction: false,
    },
   centeredSlides: true,
   breakpoints: {
    0: {
      slidesPerView: 2,
    },
    568: {
      slidesPerView: 3,
    },
    768: {
      slidesPerView: 4,
    },
    968: {
      slidesPerView: 5,
    },
  },
  
  });
