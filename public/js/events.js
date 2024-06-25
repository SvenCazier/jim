document.addEventListener("DOMContentLoaded", function () {
	const eventsContainer = document.querySelector(".events__container");

	// Extract sections from the events container
	const sections = Array.from(eventsContainer.querySelectorAll(".events"));

	// Array to store articles with their local dates
	const articlesWithLocalDates = [];

	// Iterate over each section to extract articles and their local dates
	sections.forEach((section) => {
		const sectionDate = new Date(section.querySelector(".events__title__text").getAttribute("datetime"));

		// Extract articles and their local dates
		section.querySelectorAll(".event").forEach((article) => {
			const articleTimeEl = article.querySelector(".event__time");
			const articleTime = new Date(articleTimeEl.getAttribute("datetime"));
			// Set article datetime attribute to local format
			articleTimeEl.setAttribute("datetime", articleTime.toString());
			// Set article time to local format
			articleTimeEl.textContent = articleTime.toLocalTimeString();
			const localDate = articleTime.toLocaleDateString();
			articlesWithLocalDates.push({ localDate, articleElement: article });
		});

		// Remove the section from the events container
		section.remove();
	});

	// Array to store sections
	const sectionsArray = [];

	// Iterate over articles with local dates to create/update sections
	articlesWithLocalDates.forEach(({ localDate, articleElement }) => {
		// Check if a section with the same local date exists
		let sectionIndex = sectionsArray.findIndex((sec) => sec.date === localDate);

		// If section doesn't exist, create a new section
		if (sectionIndex === -1) {
			const section = createSection(new Date(localDate));
			sectionsArray.push({ date: localDate, section });
			sectionIndex = sectionsArray.length - 1; // Set section index to the last added section
		}

		// Append the article to the appropriate section
		const section = sectionsArray[sectionIndex].section;
		section.querySelector(".events__articles").appendChild(articleElement);
	});

	// Add the updated sections back to the events container
	sectionsArray.forEach(({ section }) => {
		eventsContainer.appendChild(section);
	});

	// Change titles to local format, also for UTC time
	const eventTitles = document.querySelectorAll(".events__title__text");
	eventTitles.forEach((eventTitle) => {
		const utcTime = new Date(eventTitle.getAttribute("datetime"));
		eventTitle.setAttribute("datetime", utcTime.toDateString());
		eventTitle.textContent = utcTime.toLocalDateString();
	});
});

// Function to create a new section
function createSection(date) {
	const section = document.createElement("section");
	section.classList.add("events");
	section.innerHTML = `
	  <header class="events__title">
		<h4 class="events__title__text" datetime="${date.toISOString()}">${date.toLocaleDateString()}</h4>
	  </header>
	  <div class="events__articles"></div>
	`;
	return section;
}

// Function to format date as YYYY-MM-DD format
Date.prototype.toISODateString = function () {
	const year = this.getFullYear();
	const month = `${this.getMonth() + 1}`.padStart(2, "0");
	const day = `${this.getDate()}`.padStart(2, "0");
	return `${year}-${month}-${day}`;
};

// Function to format date as local long date string
Date.prototype.toLocalDateString = function () {
	const options = { day: "numeric", month: "long", year: "numeric" };
	return this.toLocaleDateString(navigator.language, options);
};

// Function to format time as local time string
Date.prototype.toLocalTimeString = function () {
	const options = { hour: "2-digit", minute: "2-digit" };
	const timezoneOffset = this.getTimezoneOffset();
	return `${this.toLocaleTimeString(navigator.language, options)} UTC${timezoneOffset >= 0 ? "-" : "+"}${Math.abs(Math.floor(timezoneOffset / 60))
		.toString()
		.padStart(2, "0")}:${Math.abs(timezoneOffset % 60)
		.toString()
		.padStart(2, "0")}`;
};
