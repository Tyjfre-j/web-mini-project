/**
 * Shared product card effects
 * This file contains shared functionality for product cards across the site
 */

// Apply effects to product cards
function initProductCardEffects() {
  // Animate product cards on page load
  const productCards = document.querySelectorAll(".product-link");

  // Fade in products with staggered delay
  productCards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(15px)";
    card.style.transition = "opacity 0.4s ease, transform 0.4s ease";

    setTimeout(() => {
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, 50 + index * 30);
  });

  // Product image hover effect
  const productImages = document.querySelectorAll(".product-img");
  productImages.forEach((img) => {
    img.parentElement.addEventListener("mouseenter", () => {
      img.style.transform = "scale(1.05)";
    });

    img.parentElement.addEventListener("mouseleave", () => {
      img.style.transform = "scale(1)";
    });
  });

  // Add hover effects to product cards
  productCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-10px)";
      this.style.transition = "transform 0.3s ease-in-out";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
      this.style.transition = "transform 0.3s ease-in-out";
    });
  });
}
