/**
 * Index page JavaScript functionality
 *
 * Contains:
 * 1. Banner carousel functionality
 * 2. Header scroll effects
 * 3. Product card effects (imported from shared file)
 */

// Wait for DOM content to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Banner carousel functionality
  initBannerCarousel();

  // Header scroll effects
  initHeaderScrollEffects();

  // Initialize product card effects (from shared file)
  if (typeof initProductCardEffects === "function") {
    initProductCardEffects();
  }
});

/**
 * Initialize the banner carousel/slider
 */
function initBannerCarousel() {
  const sliderItems = document.querySelectorAll(".slider-item");
  const totalBanners = sliderItems.length;
  let currentBanner = 1;

  // Function to show a specific banner
  function showBanner(bannerNumber) {
    // Remove active class from all banners
    sliderItems.forEach((item) => {
      item.classList.remove("active");
    });

    // Add active class to current banner
    const selectedBanner = document.querySelector(
      `[data-banner="${bannerNumber}"]`
    );
    if (selectedBanner) {
      selectedBanner.classList.add("active");
    }
  }

  // Function to move to next banner
  function nextBanner() {
    currentBanner = currentBanner === totalBanners ? 1 : currentBanner + 1;
    showBanner(currentBanner);
  }

  // Show first banner initially
  showBanner(1);

  // Start automatic rotation - change banner every 5 seconds
  setInterval(nextBanner, 5000);
}

/**
 * Initialize the header scroll effects
 */
function initHeaderScrollEffects() {
  const header = document.querySelector("header");
  const scrollThreshold = 100;

  function handleScroll() {
    if (window.scrollY > scrollThreshold) {
      header.classList.add("scroll-active");
    } else {
      header.classList.remove("scroll-active");
    }
  }

  // Add initial class based on page load position
  handleScroll();

  // Listen for scroll events
  window.addEventListener("scroll", handleScroll);
}
