$(document).ready(function () {
	$(".sidenav").sidenav();
});

$(document).ready(function () {
	$("textarea#message").characterCounter();
});

window.onload = function () {
	let messages = document.querySelectorAll(".card-content p");
	messages.forEach((message) => {
		if (message.innerText.length > 100) {
			message.innerHTML = message.innerText.substr(0, 100) + "...";
		}
	});
};
