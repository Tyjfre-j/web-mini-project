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

  // Prevent auto-submission when select fields change
  if (sortSelect) {
    sortSelect.addEventListener("change", function (e) {
      e.preventDefault(); // Prevent form submission
    });
  }

  if (categorySelect) {
    categorySelect.addEventListener("change", function (e) {
      e.preventDefault(); // Prevent form submission
    });
  }

  // Prevent form from submitting when pressing Enter in price inputs
  if (minPriceInput) {
    minPriceInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
      }
    });
  }

  if (maxPriceInput) {
    maxPriceInput.addEventListener("keydown", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
      }
    });
  }

  // Initialize product card effects from shared file
  if (typeof initProductCardEffects === "function") {
    initProductCardEffects();
  }

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

  // Focus visual feedback for filter inputs
  const filterSelects = document.querySelectorAll(".filter-select");

  filterSelects.forEach((select) => {
    select.addEventListener("focus", function () {
      this.parentElement.style.boxShadow = "0 0 0 2px rgba(13, 138, 145, 0.2)";
    });

    select.addEventListener("blur", function () {
      this.parentElement.style.boxShadow = "none";
    });
  });

  // Add loading state to filter button on submit
  const filterButton = document.querySelector(".btn-filter");

  if (filterForm && filterButton) {
    filterForm.addEventListener("submit", function () {
      // Ensure default values if fields are empty
      if (!minPriceInput.value) {
        minPriceInput.value = 0;
      }

      if (!maxPriceInput.value) {
        maxPriceInput.value = 10000;
      }

      filterButton.innerHTML = 'Applying... <span class="spinner"></span>';
      filterButton.disabled = true;
      filterButton.style.opacity = "0.7";

      // Add spinner style dynamically
      const style = document.createElement("style");
      style.innerHTML = `
        .spinner {
          display: inline-block;
          width: 15px;
          height: 15px;
          border: 2px solid rgba(255,255,255,0.3);
          border-radius: 50%;
          border-top-color: #fff;
          animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
          to { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    });
  }
});
