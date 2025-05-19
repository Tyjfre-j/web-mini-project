/**
 * Category Page JavaScript
 * Enhances the user experience on the category listing page
 */

document.addEventListener("DOMContentLoaded", function () {
  // Get references to filter elements
  const filterForm = document.querySelector(".filter-form");
  const sortSelect = document.getElementById("sort_by");
  const categorySelect = document.getElementById("filter_category");
  const minPriceInput = document.getElementById("min_price");
  const maxPriceInput = document.getElementById("max_price");
  const resetButton = document.querySelector(".btn-reset-filter");

  // Price range validation
  if (minPriceInput && maxPriceInput) {
    // Ensure min price doesn't exceed max price
    minPriceInput.addEventListener("change", function () {
      if (parseInt(this.value) > parseInt(maxPriceInput.value)) {
        alert("Minimum price cannot be greater than maximum price");
        this.value = maxPriceInput.value;
      }
    });

    // Ensure max price isn't less than min price
    maxPriceInput.addEventListener("change", function () {
      if (parseInt(this.value) < parseInt(minPriceInput.value)) {
        alert("Maximum price cannot be less than minimum price");
        this.value = minPriceInput.value;
      }
    });
  }

  // Auto-submit form when sort or category changes (optional feature)
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      filterForm.submit();
    });
  }

  if (categorySelect) {
    categorySelect.addEventListener("change", function () {
      filterForm.submit();
    });
  }

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

  // Make rating stars interactive
  const ratingContainers = document.querySelectorAll(".showcase-rating");

  ratingContainers.forEach((container) => {
    const stars = container.querySelectorAll("ion-icon");

    stars.forEach((star, index) => {
      star.addEventListener("mouseenter", () => {
        // Highlight stars up to the hovered one
        for (let i = 0; i <= index; i++) {
          stars[i].style.color = "#FFD700";
          stars[i].style.transform = "scale(1.2)";
        }

        // Dim stars after the hovered one
        for (let i = index + 1; i < stars.length; i++) {
          stars[i].style.color = "#ccc";
          stars[i].style.transform = "scale(1)";
        }
      });
    });

    // Reset on mouse leave
    container.addEventListener("mouseleave", () => {
      stars.forEach((s) => {
        s.style.color = "#FFD700";
        s.style.transform = "scale(1)";
      });
    });
  });
});
