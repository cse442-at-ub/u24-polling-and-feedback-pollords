async function feedbackPOST(crsID, resp) {
    const feedbackData = new FormData();
    feedbackData.append("courseID", crsID);
    feedbackData.append("response", resp);
    
    fetch("php/studentFeedback.php", {
        method: "post",
        body: feedbackData,
    }).then((response) => {
        if (!response.ok) {
	    throw new Error("Network response was not ok");
        }
	let temp = response.json(); // neglected semicolon in prior functions
	return temp;
    }).then((data) => {
	    feedbackResponder(data);
    })
	.catch((error) => {
	    console.error("Error sending data to the backend:", error);
	});
}

function feedbackResponder(data) {
    if (data.success) {
        console.log(data.message);
        // window.location.href = `mainStud.html`; // should probably be doing this?
        // do something, store feedback to localStorage, maybe redirect
    } else {
        console.log(data.message);
        // throw new Error("Feedback submission failed");
        // access and print error ID if in document or just return new error
    }
}
