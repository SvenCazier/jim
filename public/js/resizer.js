let stupidFlagBecauseJavaScriptIsRetarded = false;

const iFrameOverlays = document.getElementsByClassName("iframe-overlay");
const resizeHandles = document.getElementsByClassName("resize-handle");
const values = {
	parentNode: "",
	origResizerHeight: 0,
	origMouseY: 0,
};

for (const resizeHandle of resizeHandles) {
	resizeHandle.addEventListener("mousedown", (e) => {
		e.preventDefault();
		disableIFrames();
		stupidFlagBecauseJavaScriptIsRetarded = true;
		values.parentNode = e.target.parentNode;
		values.origResizerHeight = e.target.parentNode.offsetHeight;
		values.origMouseY = e.pageY;
		window.addEventListener("mousemove", handleMouseMove);
		window.addEventListener("mouseup", handleMouseUp);
	});
}

const handleMouseMove = (e) => {
	if (stupidFlagBecauseJavaScriptIsRetarded) {
		values.parentNode.style.height = `${values.origResizerHeight + (e.pageY - values.origMouseY)}px`;
	}
};

const handleMouseUp = () => {
	stupidFlagBecauseJavaScriptIsRetarded = false;
	window.removeEventListener("mousemove", handleMouseMove);
	window.removeEventListener("mouseup", handleMouseUp);
	enableIFrames();
};

const disableIFrames = () => {
	for (const iFrameOverlay of iFrameOverlays) {
		iFrameOverlay.style.pointerEvents = "none";
	}
};

const enableIFrames = () => {
	for (const iFrameOverlay of iFrameOverlays) {
		iFrameOverlay.style.pointerEvents = "auto";
	}
};
