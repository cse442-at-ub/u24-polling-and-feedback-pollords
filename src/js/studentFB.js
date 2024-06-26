async function feedbackPOST(args) {
    const feedbackData = new FormData();
    feedbackData.append("arg1", arg1); // variables, get from args
    
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
