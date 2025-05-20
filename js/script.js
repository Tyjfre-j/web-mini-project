"use strict";

// modal variables
const modal = document.querySelector("[data-modal]");
const modalCloseBtn = document.querySelector("[data-modal-close]");
const modalCloseOverlay = document.querySelector("[data-modal-overlay]");

// modal function
const modalCloseFunc = function () {
  modal.classList.add("closed");
};

// modal eventListener
modalCloseOverlay.addEventListener("click", modalCloseFunc);
modalCloseBtn.addEventListener("click", modalCloseFunc);

// Smooth scrolling for navigation links without visual effects
document.addEventListener("DOMContentLoaded", function () {
  // Get all links that have a hash in them
  const navLinks = document.querySelectorAll('a[href*="#"]');

  // Add event listener to each link
  navLinks.forEach((link) => {
    if (link.getAttribute("href").indexOf("#") !== -1) {
      link.addEventListener("click", function (e) {
        // Only prevent default if not an empty hash
        if (this.getAttribute("href") !== "#") {
          const hrefParts = this.getAttribute("href").split("#");
          const targetId = hrefParts[1];
          const targetElement = document.getElementById(targetId);

          // Special handling for home links
          if (targetId === "home" && hrefParts[0].includes("index.php")) {
            e.preventDefault();

            // Instead of instantly scrolling to top, just navigate to index.php
            // with a small delay to avoid the instant jump
            if (window.location.pathname.includes("index.php")) {
              // Already on index.php, just scroll a little bit down from the top
              window.scrollTo({
                top: 1,
                behavior: "auto",
              });
            } else {
              // Navigate to index.php without the hash to avoid jumping
              window.location.href = hrefParts[0];
            }
            return;
          }

          if (targetElement) {
            e.preventDefault();

            // Scroll smoothly to the target with offset
            const header = document.querySelector("header");
            const headerHeight = header ? header.offsetHeight : 0;
            const headerOffset = headerHeight + 30; // Add extra padding to ensure title is visible
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition =
              elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
              top: offsetPosition,
              behavior: "smooth",
            });

            // Close mobile menu if open
            const mobileMenu = document.querySelector(
              "[data-mobile-menu].active"
            );
            const overlay = document.querySelector("[data-overlay]");
            if (mobileMenu) {
              mobileMenu.classList.remove("active");
              overlay.classList.remove("active");
            }
          }
        }
      });
    }
  });
});

// notification toast variables
const notificationToast = document.querySelector("[data-toast]");
const toastCloseBtn = document.querySelector("[data-toast-close]");

// notification toast eventListener
toastCloseBtn.addEventListener("click", function () {
  notificationToast.classList.add("closed");
});

// mobile menu variables
const mobileMenuOpenBtn = document.querySelectorAll(
  "[data-mobile-menu-open-btn]"
);
const mobileMenu = document.querySelectorAll("[data-mobile-menu]");
const mobileMenuCloseBtn = document.querySelectorAll(
  "[data-mobile-menu-close-btn]"
);
const overlay = document.querySelector("[data-overlay]");

for (let i = 0; i < mobileMenuOpenBtn.length; i++) {
  // mobile menu function
  const mobileMenuCloseFunc = function () {
    mobileMenu[i].classList.remove("active");
    overlay.classList.remove("active");
  };

  mobileMenuOpenBtn[i].addEventListener("click", function () {
    mobileMenu[i].classList.add("active");
    overlay.classList.add("active");
  });

  mobileMenuCloseBtn[i].addEventListener("click", mobileMenuCloseFunc);
  overlay.addEventListener("click", mobileMenuCloseFunc);
}

// accordion variables
const accordionBtn = document.querySelectorAll("[data-accordion-btn]");
const accordion = document.querySelectorAll("[data-accordion]");

for (let i = 0; i < accordionBtn.length; i++) {
  accordionBtn[i].addEventListener("click", function () {
    const clickedBtn = this.nextElementSibling.classList.contains("active");

    for (let i = 0; i < accordion.length; i++) {
      if (clickedBtn) break;

      if (accordion[i].classList.contains("active")) {
        accordion[i].classList.remove("active");
        accordionBtn[i].classList.remove("active");
      }
    }

    this.nextElementSibling.classList.toggle("active");
    this.classList.toggle("active");
  });
}
