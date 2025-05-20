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
if (modalCloseOverlay) modalCloseOverlay.addEventListener("click", modalCloseFunc);
if (modalCloseBtn) modalCloseBtn.addEventListener("click", modalCloseFunc);

// Optimized navigation handling
document.addEventListener("DOMContentLoaded", function () {
  // Get all links that have a hash in them
  const navLinks = document.querySelectorAll('a[href*="#"]');

  // Add event listener to each link
  navLinks.forEach((link) => {
    if (link.getAttribute("href").indexOf("#") !== -1) {
      link.addEventListener("click", function (e) {
        const hrefParts = this.getAttribute("href").split("#");
        const targetId = hrefParts[1];
        
        // If we're not on index.php but trying to navigate to a section there
        if (
          hrefParts[0].includes("index.php") &&
          !window.location.pathname.includes("index.php") &&
          !window.location.pathname.endsWith("/")
        ) {
          // Let the browser handle the navigation
          return;
        }
        
        // Prevent default only for same-page navigation
        e.preventDefault();

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

        // Handle section scrolling
        scrollToElement(targetId);
      });
    }
  });
  
  // Consolidated scrolling function
  function scrollToElement(targetId) {
    let element;
    
    // Look for section first
    if (targetId.endsWith("-section")) {
      element = document.getElementById(targetId);
    } else {
      // For any other elements
      element = document.getElementById(targetId);
    }
    
    if (element) {
      // Get header height
      const header = document.querySelector("header");
      const headerHeight = header ? header.offsetHeight : 0;
      const offsetPadding = 20; // Extra padding
      
      // Calculate position
      const elementRect = element.getBoundingClientRect();
      const elementTopPosition = elementRect.top + window.scrollY;
      
      // Scroll to position
      window.scrollTo({
        top: elementTopPosition - headerHeight - offsetPadding,
        behavior: "smooth",
      });
      
      // Close mobile menu if open
      closeActiveMobileMenu();
    }
  }

  // Helper function to close mobile menu
  function closeActiveMobileMenu() {
    const mobileMenu = document.querySelector("[data-mobile-menu].active");
    const overlay = document.querySelector("[data-overlay]");
    if (mobileMenu && overlay) {
      mobileMenu.classList.remove("active");
      overlay.classList.remove("active");
    }
  }
});

// notification toast variables
const notificationToast = document.querySelector("[data-toast]");
const toastCloseBtn = document.querySelector("[data-toast-close]");

// notification toast eventListener
if (toastCloseBtn) {
  toastCloseBtn.addEventListener("click", function () {
    notificationToast.classList.add("closed");
  });
}

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
