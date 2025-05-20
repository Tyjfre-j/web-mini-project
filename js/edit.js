// Profile page form toggling
document.addEventListener('DOMContentLoaded', function() {
  // Profile edit form toggle
  var profileEditLink = document.querySelector('#edit_link1');
  if(profileEditLink) {
    profileEditLink.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(".profile_edit").classList.toggle("show");
    });
  }
  
  // Address edit form toggle
  var addressEditLink = document.querySelector('#edit_link2');
  if(addressEditLink) {
    addressEditLink.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(".address_edit").classList.toggle("show");
    });
  }
  
  // Contact edit form toggle
  var contactEditLink = document.querySelector('#edit_link3');
  if(contactEditLink) {
    contactEditLink.addEventListener('click', function(e) {
      e.preventDefault();
      document.querySelector(".contact_edit").classList.toggle("show");
    });
  }
});