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
  // Set dynamic header offset variable for scrolling
  setHeaderOffset();

  // Banner carousel functionality
  initBannerCarousel();

  // Header scroll effects
  initHeaderScrollEffects();

  // Initialize product card effects (from shared file)
  if (typeof initProductCardEffects === "function") {
    initProductCardEffects();
  }

  // Update header offset on window resize
  window.addEventListener("resize", setHeaderOffset);
});

/**
 * Set dynamic header offset as CSS variable for consistent scrolling
 */
function setHeaderOffset() {
  const header = document.querySelector("header");
  if (header) {
    const headerHeight = header.offsetHeight;
    const offsetPadding = 30; // Extra padding to ensure titles are visible
    document.documentElement.style.setProperty(
      "--header-offset",
      `${headerHeight + offsetPadding}px`
    );
  }
}

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
 * Initialize the header scroll effects with throttling to prevent glitches
 */
function initHeaderScrollEffects() {
  const header = document.querySelector("header");
  const addThreshold = 120; // Threshold to add the scroll-active class
  const removeThreshold = 80; // Lower threshold to remove the class (hysteresis)
  let lastScrollPosition = window.scrollY;
  let ticking = false;
  let scrollTimer = null;
  let isScrollActive = window.scrollY > addThreshold;

  // Apply initial state
  if (isScrollActive) {
    header.classList.add("scroll-active");
  } else {
    header.classList.remove("scroll-active");
  }

  function handleScroll() {
    lastScrollPosition = window.scrollY;

    if (!ticking) {
      // Use requestAnimationFrame to throttle the scroll event
      window.requestAnimationFrame(() => {
        // Use different thresholds for adding vs removing the class (hysteresis)
        if (lastScrollPosition > addThreshold && !isScrollActive) {
          header.classList.add("scroll-active");
          isScrollActive = true;
        } else if (lastScrollPosition < removeThreshold && isScrollActive) {
          header.classList.remove("scroll-active");
          isScrollActive = false;
        }
        ticking = false;
      });

      ticking = true;
    }

    // Clear the previous timeout
    if (scrollTimer) {
      clearTimeout(scrollTimer);
    }

    // Set a timeout to ensure the scroll state is correct when scrolling stops
    scrollTimer = setTimeout(() => {
      if (lastScrollPosition > addThreshold) {
        if (!isScrollActive) {
          header.classList.add("scroll-active");
          isScrollActive = true;
        }
      } else if (lastScrollPosition < removeThreshold) {
        if (isScrollActive) {
          header.classList.remove("scroll-active");
          isScrollActive = false;
        }
      }
    }, 150); // Increased timeout for more stability
  }

  // Listen for scroll events with passive option for better performance
  window.addEventListener("scroll", handleScroll, { passive: true });
}
