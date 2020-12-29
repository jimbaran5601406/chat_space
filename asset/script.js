$(document).ready(function () {
	$(".sidenav").sidenav();
	$("textarea#message").characterCounter();
	$(".modal").modal();
	$(".tabs").tabs();
	$(".dropdown-trigger").dropdown();
});

$(document).ready(function () {
	let messages = document.querySelectorAll(".card-content p");
	messages.forEach((message) => {
		if (message.innerText.length > 100) {
			message.innerHTML = message.innerText.substr(0, 100) + "...";
		}
	});
});

$(document).ready(function () {
	const btnLikeImages = $(".btn-like img");
	for (const btnLikeImage of btnLikeImages) {
		const btnLikeImageClass = btnLikeImage.className;
		const is_liked = btnLikeImageClass.includes("like") ? 1 : 0;

		if (is_liked) {
			btnLikeImage.setAttribute("src", "/asset/images/like-true.svg");
		} else {
			btnLikeImage.setAttribute("src", "/asset/images/like-false.svg");
		}
	}
});

$(document).ready(function () {
	$(".btn-like").on("click", function () {
		const heartImg = this.getElementsByClassName("heart")[0];
		const heartImgId = heartImg.className.slice(0);
		const heartImgBool = heartImg.className.includes("like") ? 0 : 1;
		const data = {
			id: heartImgId,
			is_liked: heartImgBool
		};
		axios.post("/api/axios.php", data).then(() => {
			heartImg.classList.toggle("like");
			const is_liked = heartImg.className.includes("like") ? 1 : 0;
			if (is_liked) {
				heartImg.setAttribute("src", "/asset/images/like-true.svg");
			} else {
				heartImg.setAttribute("src", "/asset/images/like-false.svg");
			}
		});
	});
});
