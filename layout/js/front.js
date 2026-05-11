$(function () {
  "use strict";

  // Switch Between Login & Signup

  $(".login-page h1 span").click(function () {
    $(this).addClass("selected").siblings().removeClass("selected");

    $(".login-page form").hide();

    $("." + $(this).data("class")).fadeIn(100);
  });

  // Trigger The Selectboxit

  $("select").selectBoxIt({
    autoWidth: false,
  });

  // Hide Placeholder On Form Focus

  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));

      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  // Add Asterisk On Required Field

  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  //convert Password Field To Text Field On Hover
  var passField = $(".password");

  $(".show-pass").hover(
    function () {
      passField.attr("type", "text");
    },
    function () {
      passField.attr("type", "password");
    }
  );

  // Confirmation Message On Button

  $(".confirm").click(function () {
    return confirm("Are You Sure?");
  });

//live add ads
  
  $('.live').keyup(function () {
    $($(this).data('class')).text($(this).val());
  })





  // Category View Option

  $(".cat h3").click(function () {
    $(this).next(".full-view").fadeToggle(200);
  });

  $(".option span").click(function () {
    $(this).addClass("active").siblings("span").removeClass("active");

    if ($(this).data("view") === "full") {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });




});


