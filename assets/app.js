import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

document.addEventListener("DOMContentLoaded", function () {
	// set image src on file upload change
	document
		.querySelectorAll('.picture input[type="file"]')
		.forEach(function (fileUpload) {
			fileUpload.addEventListener("change", function () {
				const image = document.querySelector(".picture img");
				const reader = new FileReader();
				reader.onload = function (e) {
					image.src = e.target.result;
				};
				reader.readAsDataURL(fileUpload.files[0]);
			});
		});
});
