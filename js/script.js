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
        // Prevent the default navigation
        e.preventDefault();

        const hrefParts = this.getAttribute("href").split("#");
        const targetId = hrefParts[1];

        // If we're not on index.php but trying to navigate to a section there
        if (
          hrefParts[0].includes("index.php") &&
          !window.location.pathname.includes("index.php") &&
          !window.location.pathname.endsWith("/")
        ) {
          // Navigate to index.php with the hash
          window.location.href = this.getAttribute("href");
          return;
        }

        // Special handling for home - scroll to top
        if (targetId === "home") {
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });

          // Close mobile menu if open
          closeActiveMobileMenu();
          return;
        }

        // Use a more specific selector for product sections
        if (targetId.endsWith("-section")) {
          let sectionElement = document.getElementById(targetId);
          if (sectionElement) {
            // Get header height for offset
            const header = document.querySelector("header");
            const headerHeight = header ? header.offsetHeight : 0;
            const offsetPadding = 20; // Extra padding to ensure good visibility
            
            // Get the position of the section
            const sectionRect = sectionElement.getBoundingClientRect();
            const sectionTop = sectionRect.top + window.pageYOffset;
            
            // Scroll directly to the section with offset for header
            console.log("Scrolling to section:", targetId, "at position:", sectionTop - headerHeight - offsetPadding);
            
            setTimeout(() => {
              window.scrollTo({
                top: sectionTop - headerHeight - offsetPadding,
                behavior: "smooth",
              });
            }, 50);
            
            closeActiveMobileMenu();
          } else {
            window.location.href = this.getAttribute("href");
          }
        } else {
          // For any other elements (non-section IDs)
          const element = document.getElementById(targetId);

          if (element) {
            // Get header height
            const header = document.querySelector("header");
            const headerHeight = header ? header.offsetHeight : 0;

            // Calculate position
            const elementRect = element.getBoundingClientRect();
            const elementTopPosition = elementRect.top + window.pageYOffset;

            // Scroll to position
            window.scrollTo({
              top: elementTopPosition - headerHeight,
              behavior: "smooth",
            });

            // Close mobile menu if open
            closeActiveMobileMenu();
          } else {
            // Fallback if element not found
            window.location.href = this.getAttribute("href");
          }
        }
      });
    }
  });

  // Helper function to close mobile menu
  function closeActiveMobileMenu() {
    const mobileMenu = document.querySelector("[data-mobile-menu].active");
    const overlay = document.querySelector("[data-overlay]");
    if (mobileMenu) {
      mobileMenu.classList.remove("active");
      overlay.classList.remove("active");
    }
  }
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
