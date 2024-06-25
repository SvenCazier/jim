const rssCost = {
	infantry: {
		1: { meat: 36, wood: 27, coal: 7, iron: 2 },
		2: { meat: 58, wood: 44, coal: 10, iron: 3 },
		3: { meat: 92, wood: 69, coal: 17, iron: 4 },
		4: { meat: 120, wood: 90, coal: 21, iron: 5 },
		5: { meat: 156, wood: 117, coal: 27, iron: 6 },
		6: { meat: 186, wood: 140, coal: 33, iron: 7 },
		7: { meat: 279, wood: 210, coal: 49, iron: 11 },
		8: { meat: 558, wood: 419, coal: 98, iron: 21 },
		9: { meat: 1394, wood: 1046, coal: 244, iron: 51 },
		10: { meat: 2788, wood: 2091, coal: 488, iron: 102 },
		11: { meat: 6970, wood: 5228, coal: 1220, iron: 253 },
	},
	marksman: {
		1: { meat: 23, wood: 34, coal: 6, iron: 2 },
		2: { meat: 36, wood: 54, coal: 9, iron: 4 },
		3: { meat: 58, wood: 86, coal: 15, iron: 5 },
		4: { meat: 75, wood: 111, coal: 19, iron: 6 },
		5: { meat: 97, wood: 144, coal: 24, iron: 8 },
		6: { meat: 117, wood: 173, coal: 29, iron: 10 },
		7: { meat: 175, wood: 258, coal: 44, iron: 14 },
		8: { meat: 349, wood: 516, coal: 87, iron: 28 },
		9: { meat: 872, wood: 1290, coal: 217, iron: 70 },
		10: { meat: 1743, wood: 2579, coal: 433, iron: 140 },
		11: { meat: 4357, wood: 6448, coal: 1081, iron: 349 },
	},
	lancer: {
		1: { meat: 32, wood: 30, coal: 7, iron: 2 },
		2: { meat: 51, wood: 48, coal: 10, iron: 3 },
		3: { meat: 81, wood: 76, coal: 16, iron: 4 },
		4: { meat: 105, wood: 99, coal: 21, iron: 5 },
		5: { meat: 136, wood: 129, coal: 27, iron: 7 },
		6: { meat: 163, wood: 154, coal: 32, iron: 8 },
		7: { meat: 244, wood: 231, coal: 48, iron: 11 },
		8: { meat: 488, wood: 461, coal: 95, iron: 22 },
		9: { meat: 1220, wood: 1151, coal: 237, iron: 55 },
		10: { meat: 2440, wood: 2301, coal: 474, iron: 109 },
		11: { meat: 6099, wood: 5751, coal: 1185, iron: 271 },
	},
};

const power = {
	0: { 1: 3, 2: 4, 3: 5, 4: 9, 5: 13, 6: 20, 7: 28, 8: 38, 9: 50, 10: 66 },
	1: { 1: 3, 2: 4, 3: 6, 4: 10, 5: 14, 6: 21, 7: 30, 8: 41, 9: 54, 10: 71 },
	2: { 1: 3, 2: 4, 3: 6, 4: 10, 5: 15, 6: 22, 7: 32, 8: 44, 9: 58, 10: 76 },
	3: { 1: 3, 2: 4, 3: 6, 4: 11, 5: 16, 6: 24, 7: 34, 8: 47, 9: 62, 10: 83 },
	4: { 1: 3, 2: 4, 3: 8, 4: 12, 5: 17, 6: 26, 7: 37, 8: 51, 9: 67, 10: 88 },
	5: { 1: 4, 2: 5, 3: 9, 4: 13, 5: 18, 6: 28, 7: 40, 8: 54, 9: 72, 10: 94, 11: 114 },
	6: { 1: 4, 2: 5, 3: 9, 4: 14, 5: 19, 6: 30, 7: 43, 8: 57, 9: 77, 10: 99, 11: 120 },
	7: { 1: 4, 2: 5, 3: 10, 4: 15, 5: 20, 6: 32, 7: 46, 8: 60, 9: 82, 10: 104, 11: 126 },
	8: { 1: 5, 2: 6, 3: 11, 4: 16, 5: 21, 6: 34, 7: 49, 8: 63, 9: 87, 10: 110, 11: 135 },
};

const baseTrainingTime = [12, 17, 24, 32, 44, 60, 83, 113, 131, 152, 180];

const buffs = {
	capacityBuff: { trainingCapacityMultiplier: 3 },
	mobilizeBuff: { trainingSpeedIncrease: 30 },
};

const appointments = {
	vicePresident: { trainingSpeedIncrease: 10 },
	ministerOfEducation: { trainingSpeedIncrease: 50, trainingCapacityModifier: 200 },
	theGreedy: { trainingSpeedIncrease: -10 },
	theMindless: { trainingSpeedIncrease: -25, trainingCapacityModifier: -100 },
};

const tableValues = {
	total: {
		time: { days: 0, hours: 0, minutes: 0, seconds: 0 },
		rss: { meat: 0, wood: 0, coal: 0, iron: 0 },
		power: 0,
	},
	session: {
		time: { days: 0, hours: 0, minutes: 0, seconds: 0 },
		rss: { meat: 0, wood: 0, coal: 0, iron: 0 },
		count: 0,
		power: 0,
	},
	sum: [],
};

document.getElementById("troopTrainingForm").addEventListener("change", () => {
	calculate();
	updateTableValues();
});

document.getElementById("fcLevel").addEventListener("change", (event) => {
	const troopLevelSelect = document.getElementById("troopLevel");
	const promotionLevelSelect = document.getElementById("promotionFromLevel");
	const troopToDisable = troopLevelSelect.querySelector(`option[value="11"]`);
	const promotionToDisable = promotionLevelSelect.querySelector(`option[value="11"]`);
	if (event.target.value < 5) {
		troopToDisable.disabled = true;
		promotionToDisable.disabled = true;
	} else {
		troopToDisable.disabled = false;
		promotionToDisable.disabled = false;
	}
});

document.getElementById("addToSum").addEventListener("click", () => {
	const troopType = document.getElementById("troopType").value;
	const troopCount = Number(document.getElementById("troopCount").value);
	const troopLevel = document.getElementById("troopLevel").value;
	const promotionFromLevel = document.getElementById("promotionFromLevel").value;
	if (troopCount) {
		// add total to sum
		tableValues.sum.push(structuredClone(tableValues.total));
		tableValues.sum[tableValues.sum.length - 1].description = `${troopCount} x ${promotionFromLevel ? `T${promotionFromLevel} ->` : ""} T${troopLevel} ${troopType}`;
		updateTableValues();
	}
});

const calculate = () => {
	const fcLevel = document.getElementById("fcLevel").value;
	const troopType = document.getElementById("troopType").value;
	const troopCount = Number(document.getElementById("troopCount").value);
	const troopLevel = document.getElementById("troopLevel").value;
	const trainingSpeed = Number(document.getElementById("trainingSpeed").value);
	const trainingCapacity = Number(document.getElementById("trainingCapacity").value);
	const promotionFromLevel = document.getElementById("promotionFromLevel").value;
	const promotionCapacity = Number(document.getElementById("promotionCapacity").value);
	const capacityBuff = document.getElementById("capacityBuff").checked;
	const mobilizeBuff = document.getElementById("mobilizeBuff").checked;
	const appointment = document.getElementById("appointment").value;

	// set multipliers and modifiers
	const capacityMultiplier = capacityBuff ? buffs.capacityBuff.trainingCapacityMultiplier : 1;
	const trainingSpeedIncease = (mobilizeBuff ? buffs.mobilizeBuff.trainingSpeedIncrease : 0) + (appointments[appointment]?.trainingSpeedIncrease ?? 0);
	const trainingSpeedMultiplier = 1 / (1 + (trainingSpeed + trainingSpeedIncease) / 100);
	const trainingCapacityModifier = appointments[appointment]?.trainingCapacityModifier ?? 0;

	const isValidPromotion = promotionFromLevel && promotionCapacity && promotionFromLevel < troopLevel ? true : false;

	// set capacity to be used
	const capacityToBeUsed = ((isValidPromotion ? promotionCapacity : trainingCapacity) + trainingCapacityModifier) * capacityMultiplier;
	// set training time to be used
	const trainingTimeToBeUsed = (baseTrainingTime[troopLevel - 1] - (isValidPromotion ? baseTrainingTime[promotionFromLevel - 1] : 0)) * trainingSpeedMultiplier;

	// calculate power gain
	const powerGainPerTroop = Math.max((power[fcLevel][troopLevel] ?? 0) - (isValidPromotion ? power[fcLevel][promotionFromLevel] ?? 0 : 0), 0);
	tableValues.total.power = powerGainPerTroop * troopCount;

	// calculate rss cost
	const meatCost = (rssCost[troopType][troopLevel]?.meat ?? 0) - (isValidPromotion ? rssCost[troopType][promotionFromLevel]?.meat : 0);
	const woodCost = (rssCost[troopType][troopLevel]?.wood ?? 0) - (isValidPromotion ? rssCost[troopType][promotionFromLevel]?.wood : 0);
	const coalCost = (rssCost[troopType][troopLevel]?.coal ?? 0) - (isValidPromotion ? rssCost[troopType][promotionFromLevel]?.coal : 0);
	const ironCost = (rssCost[troopType][troopLevel]?.iron ?? 0) - (isValidPromotion ? rssCost[troopType][promotionFromLevel]?.iron : 0);

	// calculate total rss
	tableValues.total.rss.meat = meatCost * troopCount;
	tableValues.total.rss.wood = woodCost * troopCount;
	tableValues.total.rss.coal = coalCost * troopCount;
	tableValues.total.rss.iron = ironCost * troopCount;

	// calculate total training time in seconds
	const totalTrainingTime = trainingTimeToBeUsed * troopCount;

	// calculate and assign the total time in days, hours, minutes and seconds
	Object.assign(tableValues.total.time, calculateTime(totalTrainingTime));

	// calculate amount of sessions
	const sessionCount = troopCount / capacityToBeUsed;
	tableValues.session.count = isFinite(sessionCount) ? Math.max(sessionCount, 1).toFixed(2) : 0;

	// calculate and assign the session at max capacity rss and time in days, hours, minutes and seconds
	if (isFinite(sessionCount) && sessionCount >= 1) {
		tableValues.session.rss = { meat: meatCost * capacityToBeUsed, wood: woodCost * capacityToBeUsed, coal: coalCost * capacityToBeUsed, iron: ironCost * capacityToBeUsed };

		// calculate max capacity training time in seconds
		const maxCapacityTrainingTime = trainingTimeToBeUsed * capacityToBeUsed;
		Object.assign(tableValues.session.time, calculateTime(maxCapacityTrainingTime));

		tableValues.session.power = powerGainPerTroop * capacityToBeUsed;
	} else {
		tableValues.session.rss = { meat: "N/A", wood: "N/A", coal: "N/A", iron: "N/A" };
		tableValues.session.time = { days: "N/A", hours: "N/A", minutes: "N/A", seconds: "N/A" };
		tableValues.session.power = "N/A";
	}
};

const calculateTime = (timeInSeconds) => {
	const days = Math.floor(timeInSeconds / 86400);
	const remainingSecondsAfterDays = timeInSeconds - days * 86400;

	const hours = Math.floor(remainingSecondsAfterDays / 3600);
	const remainingSecondsAfterHours = remainingSecondsAfterDays - hours * 3600;

	const minutes = Math.floor(remainingSecondsAfterHours / 60);

	const seconds = Math.floor(remainingSecondsAfterHours - minutes * 60);

	return { days: days, hours: hours, minutes: minutes, seconds: seconds };
};

const updateTableValues = () => {
	// total table time
	document.getElementById("totalDays").textContent = tableValues.total.time.days;
	document.getElementById("totalHours").textContent = tableValues.total.time.hours;
	document.getElementById("totalMinutes").textContent = tableValues.total.time.minutes;
	document.getElementById("totalSeconds").textContent = tableValues.total.time.seconds;

	// total table rss
	document.getElementById("totalMeat").textContent = formatRss(tableValues.total.rss.meat);
	document.getElementById("totalWood").textContent = formatRss(tableValues.total.rss.wood);
	document.getElementById("totalCoal").textContent = formatRss(tableValues.total.rss.coal);
	document.getElementById("totalIron").textContent = formatRss(tableValues.total.rss.iron);

	// total table power
	document.getElementById("totalPower").textContent = tableValues.total.power;

	// session count
	document.getElementById("sessionCount").textContent = tableValues.session.count;

	// session table time
	document.getElementById("sessionDays").textContent = tableValues.session.time.days;
	document.getElementById("sessionHours").textContent = tableValues.session.time.hours;
	document.getElementById("sessionMinutes").textContent = tableValues.session.time.minutes;
	document.getElementById("sessionSeconds").textContent = tableValues.session.time.seconds;

	// session table rss
	document.getElementById("sessionMeat").textContent = formatRss(tableValues.session.rss.meat);
	document.getElementById("sessionWood").textContent = formatRss(tableValues.session.rss.wood);
	document.getElementById("sessionCoal").textContent = formatRss(tableValues.session.rss.coal);
	document.getElementById("sessionIron").textContent = formatRss(tableValues.session.rss.iron);

	// session table power
	document.getElementById("sessionPower").textContent = tableValues.session.power;

	const basket = document.getElementById("basket");
	basket.innerHTML = "";

	if (tableValues.sum.length) {
		tableValues.sum.forEach((item, index) => {
			const stringCell = document.createElement("td");
			stringCell.setAttribute("colspan", "3");
			stringCell.textContent = item.description;
			const deleteButton = document.createElement("button");
			deleteButton.id = `b${index}`;
			deleteButton.classList.add("button", "button--outline", "cta", "delete");
			deleteButton.textContent = "X";
			deleteButton.addEventListener("click", deleteFromBasket);
			const deleteCell = document.createElement("td");
			deleteCell.append(deleteButton);
			const row = document.createElement("tr");
			row.append(stringCell);
			row.append(deleteCell);
			basket.append(row);
		});
	}

	const sumValues = tableValues.sum.reduce(
		(acc, item) => {
			acc.time.days += item.time.days;
			acc.time.hours += item.time.hours;
			acc.time.minutes += item.time.minutes;
			acc.time.seconds += item.time.seconds;
			acc.rss.meat += item.rss.meat;
			acc.rss.wood += item.rss.wood;
			acc.rss.coal += item.rss.coal;
			acc.rss.iron += item.rss.iron;
			acc.power += item.power;
			return acc;
		},
		{
			time: { days: 0, hours: 0, minutes: 0, seconds: 0 },
			rss: { meat: 0, wood: 0, coal: 0, iron: 0 },
			power: 0,
		}
	);

	sumValues.time.minutes += Math.floor(sumValues.time.seconds / 60);
	sumValues.time.seconds %= 60;

	sumValues.time.hours += Math.floor(sumValues.time.minutes / 60);
	sumValues.time.minutes %= 60;

	sumValues.time.days += Math.floor(sumValues.time.hours / 24);
	sumValues.time.hours %= 24;

	// sum table time
	document.getElementById("sumDays").textContent = sumValues.time.days;
	document.getElementById("sumHours").textContent = sumValues.time.hours;
	document.getElementById("sumMinutes").textContent = sumValues.time.minutes;
	document.getElementById("sumSeconds").textContent = sumValues.time.seconds;

	// sum table rss
	document.getElementById("sumMeat").textContent = formatRss(sumValues.rss.meat);
	document.getElementById("sumWood").textContent = formatRss(sumValues.rss.wood);
	document.getElementById("sumCoal").textContent = formatRss(sumValues.rss.coal);
	document.getElementById("sumIron").textContent = formatRss(sumValues.rss.iron);

	// sum table power
	document.getElementById("sumPower").textContent = sumValues.power;
};

const deleteFromBasket = (event) => {
	tableValues.sum.splice(Number(event.target.id.slice(1)), 1);
	updateTableValues();
};

const formatRss = (value) => {
	return value.toLocaleString();
};
