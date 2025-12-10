// =========================
// SLIDER
// =========================
const slides = document.querySelectorAll('.slide');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

let currentSlide = 0;

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle('active', i === index);
  });
}

if (prevBtn && nextBtn) {
  prevBtn.addEventListener('click', () => {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  });

  nextBtn.addEventListener('click', () => {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  });

  setInterval(() => {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }, 5000);
}

// =========================
// VALIDIMI CONTACT
// =========================
const contactForm = document.getElementById("contactForm");
if(contactForm){
  contactForm.addEventListener("submit", function(e){
    e.preventDefault();
    let name = document.getElementById("contactName").value.trim();
    let email = document.getElementById("contactEmail").value.trim();
    let message = document.getElementById("contactMessage").value.trim();
    let errorBox = document.getElementById("contactError");
    let successBox = document.getElementById("contactSuccess");
    errorBox.textContent = "";
    successBox.textContent = "";
    if(name.length<3){ errorBox.textContent="Emri duhet të ketë të paktën 3 karaktere."; return; }
    if(!email.includes("@") || !email.includes(".")){ errorBox.textContent="Email nuk është valid."; return; }
    if(message.length<10){ errorBox.textContent="Mesazhi duhet të ketë të paktën 10 karaktere."; return; }
    successBox.textContent="Mesazhi u dërgua me sukses!";
    contactForm.reset();
  });
}

// =========================
// VALIDIMI LOGIN
// =========================
const loginForm = document.getElementById("loginForm");
if(loginForm){
  loginForm.addEventListener("submit", e=>{
    e.preventDefault();
    let email = loginForm.querySelector("input[type='email']").value;
    let pass = loginForm.querySelector("input[type='password']").value;
    if(!email.includes("@")){ alert("Email nuk është valid!"); return; }
    if(pass.length<5){ alert("Fjalëkalimi duhet të ketë të paktën 5 karaktere."); return; }
    alert("Kyçja u krye me sukses!"); loginForm.reset();
  });
}

// =========================
// VALIDIMI REGISTER
// =========================
const registerForm = document.getElementById("registerForm");
if(registerForm){
  registerForm.addEventListener("submit", e=>{
    e.preventDefault();
    let name = registerForm.querySelector("input[type='text']").value;
    let email = registerForm.querySelector("input[type='email']").value;
    let pass = registerForm.querySelector("input[type='password']").value;
    if(name.length<3){ alert("Emri duhet të ketë së paku 3 karaktere."); return; }
    if(!email.includes("@")){ alert("Email nuk është valid."); return; }
    if(pass.length<6){ alert("Fjalëkalimi duhet të ketë minimum 6 karaktere."); return; }
    alert("Regjistrimi u krye me sukses!"); registerForm.reset();
  });
}

/* ------------------------------------------------------------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------------------------------------------------------------ */

// Slider galeria About Us
const slidesGallery = document.querySelectorAll('.slide-gallery');
const prevGalleryBtn = document.getElementById('prev-gallery');
const nextGalleryBtn = document.getElementById('next-gallery');

let currentGallerySlide = 0;

function showGallerySlide(index) {
    slidesGallery.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

if(prevGalleryBtn && nextGalleryBtn){
    prevGalleryBtn.addEventListener('click', () => {
        currentGallerySlide = (currentGallerySlide - 1 + slidesGallery.length) % slidesGallery.length;
        showGallerySlide(currentGallerySlide);
    });

    nextGalleryBtn.addEventListener('click', () => {
        currentGallerySlide = (currentGallerySlide + 1) % slidesGallery.length;
        showGallerySlide(currentGallerySlide);
    });

    setInterval(() => {
        currentGallerySlide = (currentGallerySlide + 1) % slidesGallery.length;
        showGallerySlide(currentGallerySlide);
    }, 5000);
}
