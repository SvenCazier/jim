document.getElementById("menu-button").addEventListener("click", function (e) {
	const navbar = document.getElementById("navbar");
	this.classList.toggle("open");
	navbar.classList.toggle("open");
});
