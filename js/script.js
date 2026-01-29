// Mobile nav toggle
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById("navToggle");
  const menu = document.getElementById("navMenu");
  if (toggle && menu) {
    toggle.addEventListener("click", () => {
      menu.classList.toggle("open");
    });
  }
});

// HOME SLIDER
(function () {
  const slides = document.querySelectorAll(".slide");
  const prevBtn = document.getElementById("prev");
  const nextBtn = document.getElementById("next");

  if (!slides.length || !prevBtn || !nextBtn) return;

  let current = 0;

  function show(i) {
    slides.forEach((s, idx) => s.classList.toggle("active", idx === i));
  }

  prevBtn.addEventListener("click", () => {
    current = (current - 1 + slides.length) % slides.length;
    show(current);
  });

  nextBtn.addEventListener("click", () => {
    current = (current + 1) % slides.length;
    show(current);
  });

  setInterval(() => {
    current = (current + 1) % slides.length;
    show(current);
  }, 5000);
})();

// ABOUT GALLERY SLIDER
(function () {
  const slides = document.querySelectorAll(".slide-gallery");
  const prevBtn = document.getElementById("prev-gallery");
  const nextBtn = document.getElementById("next-gallery");

  if (!slides.length || !prevBtn || !nextBtn) return;

  let current = 0;

  function show(i) {
    slides.forEach((s, idx) => s.classList.toggle("active", idx === i));
  }

  prevBtn.addEventListener("click", () => {
    current = (current - 1 + slides.length) % slides.length;
    show(current);
  });

  nextBtn.addEventListener("click", () => {
    current = (current + 1) % slides.length;
    show(current);
  });

  setInterval(() => {
    current = (current + 1) % slides.length;
    show(current);
  }, 5000);
})();

// SEARCH + CATEGORY FILTER (index)
(function () {
  const search = document.getElementById("searchBook");
  const tabs = document.querySelectorAll(".tab-btn");
  const books = document.querySelectorAll(".book");

  if (!books.length) return;

  if (search) {
    search.addEventListener("input", function () {
      const q = this.value.toLowerCase();
      books.forEach((b) => {
        b.style.display = b.textContent.toLowerCase().includes(q) ? "block" : "none";
      });
    });
  }

  if (tabs.length) {
    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const cat = tab.dataset.category;
        books.forEach((b) => {
          b.style.display = b.dataset.category === cat ? "block" : "none";
        });
      });
    });
  }
})();

