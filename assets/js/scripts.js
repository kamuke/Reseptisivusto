$(document).ready(function () {

  // Drop down search and icon
  const dropDownSearch = $(".drop-down-search");
  const dropDownSearchIcon = $(".drop-down-search-icon");

  // On click show/hide drop down search
  dropDownSearchIcon.on("click", function () {
    dropDownSearch.toggleClass("hide");
    dropDownSearchIcon.toggle();
  });

  // On scroll hide drop down search if it has hide
  $(window).scroll(function () {
    if (!dropDownSearch.hasClass("hide")) {
      dropDownSearch.addClass("hide");
      dropDownSearchIcon.toggle();
    }
  });

  // Email validation
  const submitEmailButton = $("#submit-email");
  const emailForm = $(".email-form");
  const alertText = $(".validation-text");

  // On click validate email
  submitEmailButton.on("click", function () {
    validateEmail();
    return false;
  });

  // If emailForm changes, remove error class
  emailForm.on("change", function () {
    emailForm.removeClass("error");
  });

  // Email validation
  function validateEmail() {
    const mail = $("#email").val();
    const regx = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let validation;

    if (regx.test(mail)) {
      validation = "Kiitos uutiskirjeen tilaamisesta.";
      emailForm.trigger("reset");
      alertText.text(validation);
      return true;
    } else {
      validation = "Anna toimiva sähköposti.";
      alertText.text(validation);
      emailForm.addClass("error");
      return false;
    }
  }
});