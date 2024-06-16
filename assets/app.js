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
	// BUG: issue with symfony not reloading the page
	const fileUpload = document.querySelector('.picture input[type="file"]');
	console.log(fileUpload);
	fileUpload.addEventListener("change", function () {
		console.log(fileUpload.files[0]);
		const image = document.querySelector(".picture img");
		const reader = new FileReader();
		reader.onload = function (e) {
			image.src = e.target.result;
		};
		reader.readAsDataURL(fileUpload.files[0]);
	});
});
