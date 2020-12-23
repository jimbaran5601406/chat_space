"use strict";

$(document).ready(function () {
  $(".sidenav").sidenav();
});
$(document).ready(function () {
  $("textarea#message").characterCounter();
});

window.onload = function () {
  var messages = document.querySelectorAll(".card-content p");
  messages.forEach(function (message) {
    if (message.innerText.length > 100) {
      message.innerHTML = message.innerText.substr(0, 100) + "...";
    }
  });
};

$(document).ready(function () {
  $(".tabs").tabs();
});