/**
 * Index page JavaScript functionality - Optimized version
 *
 * Contains:
 * 1. Banner carousel functionality
 * 2. Header scroll effects
 * 3. Product card effects (imported from shared file)
 */

// Wait for DOM content to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Only run these functions if we're on a page that needs them
  const bannerElement = document.querySelector(".banner");
  const sliderItems = document.querySelectorAll(".slider-item");
  
  if (bannerElement && sliderItems.length > 0) {
    // Set first slide to visible immediately to prevent FOUC
    const firstSlide = document.querySelector("[data-banner='1']");
    if (firstSlide) {
      firstSlide.style.display = "block";
    }
    
    // Set dynamic header offset and init carousel
    setHeaderOffset();
    initBannerCarousel();
  }

  // Header scroll effects
  initHeaderScrollEffects();

  // Initialize product card effects (from shared file) if available
  if (typeof initProductCardEffects === "function") {
    initProductCardEffects();
  }

  // Update header offset on window resize (throttled)
  let resizeTimer;
  window.addEventListener("resize", function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(setHeaderOffset, 250);
  });
});

/**
 * Set dynamic header offset as CSS variable for consistent scrolling
 */
function setHeaderOffset() {
  const header = document.querySelector("header");
  if (header) {
    const headerHeight = header.offsetHeight;
    const offsetPadding = 30; // Extra padding 
    document.documentElement.style.setProperty(
      "--header-offset",
      `${headerHeight + offsetPadding}px`
    );
  }
}

/**
 * Initialize the banner carousel/slider with improved performance
 */
function initBannerCarousel() {
  const sliderItems = document.querySelectorAll(".slider-item");
  const totalBanners = sliderItems.length;
  
  // Exit if no banners
  if (totalBanners === 0) return;
  
  let currentBanner = 1;
  let carouselInterval = null;

  // Function to show a specific banner
  function showBanner(bannerNumber) {
    // Hide all banners
    sliderItems.forEach((item) => {
      item.style.display = "none";
    });

    // Show current banner
    const selectedBanner = document.querySelector(
      `[data-banner="${bannerNumber}"]`
    );
    if (selectedBanner) {
      selectedBanner.style.display = "block";
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
  // Only start if the page is visible
  if (document.visibilityState === "visible") {
    carouselInterval = setInterval(nextBanner, 5000);
  }
  
  // Pause carousel when page is not visible to save resources
  document.addEventListener("visibilitychange", function() {
    if (document.visibilityState === "visible") {
      // Page is visible again, restart the carousel
      if (!carouselInterval) {
        carouselInterval = setInterval(nextBanner, 5000);
      }
    } else {
      // Page is hidden, clear the interval
      clearInterval(carouselInterval);
      carouselInterval = null;
    }
  });
}

/**
 * Initialize the header scroll effects with improved performance
 */
function initHeaderScrollEffects() {
  const header = document.querySelector("header");
  if (!header) return;
  
  const addThreshold = 120; // Threshold to add the scroll-active class
  const removeThreshold = 80; // Lower threshold to remove the class (hysteresis)
  
  let isScrollActive = window.scrollY > addThreshold;
  let lastKnownScrollY = window.scrollY;
  let ticking = false;

  // Apply initial state
  if (isScrollActive) {
    header.classList.add("scroll-active");
  }

  // Using requestAnimationFrame for better performance
  function onScroll() {
    lastKnownScrollY = window.scrollY;
    requestTick();
  }

  function requestTick() {
    if (!ticking) {
      requestAnimationFrame(updateHeaderState);
      ticking = true;
    }
  }

  function updateHeaderState() {
    // Apply scroll class based on scroll position
    if (lastKnownScrollY > addThreshold && !isScrollActive) {
      header.classList.add("scroll-active");
      isScrollActive = true;
    } else if (lastKnownScrollY < removeThreshold && isScrollActive) {
      header.classList.remove("scroll-active");
      isScrollActive = false;
    }
    
    ticking = false;
  }

  // Listen for scroll events with passive option for better performance
  window.addEventListener("scroll", onScroll, { passive: true });
}
