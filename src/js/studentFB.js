async function feedbackPOST(crsID, resp) {
    const feedbackData = new FormData();
    feedbackData.append("courseID", crsID);
    feedbackData.append("response", resp);
    
    fetch("php/addFeedback.php", {
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
        // do something, store feedback to localStorage, maybe redirect
    } else {
        // access and print error ID if in document or just return new error
    }
}
