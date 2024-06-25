const openCreateModal = document.getElementById("openCreateModal");
const openModifyModal = document.getElementById("openModifyModal");
const closeModal = document.getElementById("closeModal");
const modal = document.getElementById("createEventModal");
const eventForm = document.getElementById("eventForm");
const timeZoneOffset = document.getElementById("timeZoneOffset");

openCreateModal.addEventListener("click", () => {
	const date = new Date();
	timeZoneOffset.value = date.getTimezoneOffset();
	modal.showModal();
});

openModifyModal.addEventListener("click", () => {
	const date = new Date();
	timeZoneOffset.value = date.getTimezoneOffset();
	modal.showModal();
});

closeModal.addEventListener("click", () => {
	modal.close();
	eventForm.reset();
});

const cDatalists = document.querySelectorAll(".custom-datalist");

// Loop through each input element
cDatalists.forEach((cDatalist) => {
	// Find the wrapper element containing the input and datalist
	const wrapper = cDatalist.parentElement;
	const datalist = wrapper.querySelector("datalist");
	const options = datalist.querySelectorAll("option");

	// Add event listener for input focus
	cDatalist.addEventListener("focus", () => {
		datalist.style.display = "block";
	});

	// Add event listener for input changes
	cDatalist.addEventListener("input", function () {
		const text = cDatalist.value.toUpperCase();
		Array.from(options).forEach((option) => {
			const optionValue = option.value.toUpperCase();
			if (optionValue.startsWith(text)) {
				option.style.display = "block";
			} else {
				option.style.display = "none";
			}
		});
	});

	// Add event listener for keyboard input
	cDatalist.addEventListener("keydown", (e) => {
		switch (e.keyCode) {
			case 40: // Down arrow
				e.preventDefault();
				moveSelection(1);
				break;
			case 38: // Up arrow
				e.preventDefault();
				moveSelection(-1);
				break;
			case 13: // Enter key
				e.preventDefault();
				if (currentFocus > -1) {
					options[currentFocus].selected = true;
					cDatalist.value = options[currentFocus].value;
					datalist.style.display = "none";
				}
				break;
		}
	});

	let currentFocus = -1;

	// Function to move the selection up and down
	function moveSelection(step) {
		currentFocus += step;
		if (currentFocus >= options.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = options.length - 1;
		highlightOption();

		// Scroll the datalist container
		const activeOption = options[currentFocus];
		const optionRect = activeOption.getBoundingClientRect();
		const datalistRect = datalist.getBoundingClientRect();

		if (optionRect.bottom > datalistRect.bottom) {
			datalist.scrollTop += optionRect.bottom - datalistRect.bottom;
		} else if (optionRect.top < datalistRect.top) {
			datalist.scrollTop -= datalistRect.top - optionRect.top;
		}
	}

	// Function to highlight the selected option
	function highlightOption() {
		options.forEach((option) => {
			option.classList.remove("active");
		});
		if (currentFocus > -1) {
			options[currentFocus].classList.add("active");
			cDatalist.value = options[currentFocus].value;
		}
	}
});
