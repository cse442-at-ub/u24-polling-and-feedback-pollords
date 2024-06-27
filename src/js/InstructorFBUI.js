document.addEventListener('DOMContentLoaded', (event) => {
    //console.log("DOM fully loaded and parsed");

    // Redirect to login page if userEmail is not in localStorage
    if (!localStorage.getItem("userEmail")) {
        //console.log("No userEmail in localStorage, redirecting to login page");
        window.location.href = "index.html"; // Redirect to login page
    } else {
        //console.log("userEmail found in localStorage, checking feedback authorization");
        checkFeedbackAuth();
    }

    function displayFeedback(isOpen, numberOfResponses = 0, averageRating = 0) {
        const feedbackContainer = document.getElementById("feedback-container");

        if (feedbackContainer) {
            if (isOpen == 0) {
                //console.log("Feedback is closed, displaying closed feedback form");
                feedbackContainer.innerHTML = `
                    <div class="container">
                        <p>Class: CSE 442 Software Engineering Concepts</p>
                        <p>Open feedback to allow students to rate the pace of the class. Answers use an average from 1 to 5, with:</p>
                        <ul>
                            <li>1 - "I'm Lost"</li>
                            <li>3 - "Just right"</li>
                            <li>5 - "This is easy"</li>
                        </ul>
                        <button id="open-feedback-btn">Open Feedback</button>
                    </div>`;

                const openButton = document.getElementById('open-feedback-btn');
                if (openButton) {
                    openButton.addEventListener('click', handleOpenFeedback);
                }
            } else if (isOpen == 1) {
                //console.log("Feedback is open, displaying open feedback form");
                feedbackContainer.innerHTML = `
                    <div class="container">
                        <p>Class: CSE 442 Software Engineering Concepts</p>
                        <p>Open feedback to allow students to rate the pace of the class. Answers use an average from 1 to 5, with:</p>
                        <ul>
                            <li>1 - "I'm Lost"</li>
                            <li>3 - "Just right"</li>
                            <li>5 - "This is easy"</li>
                        </ul>
                        <p>Number of Responses: ${numberOfResponses}</p>
                        <p>Average Rating: ${averageRating}</p>
                        <button id="close-feedback-btn">Close Feedback</button>
                    </div>`;

                const closeButton = document.getElementById('close-feedback-btn');
                if (closeButton) {
                    closeButton.addEventListener('click', handleCloseFeedback);
                }
            }
        } else {
            //console.error("The element 'feedback-container' does not exist in the DOM.");
        }
    }

    function checkFeedbackAuth() {
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        const formData = new FormData();
        formData.append("courseID", courseId);
        formData.append("userEmail", localStorage.getItem("userEmail"));

        //console.log("Checking feedback authorization for course ID:", courseId);

        fetch("php/checkAuthFeedback.php", {
            method: "post",
            body: formData,
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            //console.log("Received response from checkAuthFeedback:", data);
            let isInstructor = data.instructor;
            if (!isInstructor) {
                //console.log("User is not an instructor, redirecting to mainStud.html");
                location.href = 'mainStud.html';
            }
            displayFeedback(data.feedbackOpen, data.numberOfResponses, data.averageRating);
        })
        .catch((error) => {
            //console.error("Error sending data to the backend:", error);
        });
    }

    function handleOpenFeedback(event) {
        //console.log("Opening feedback");
        // Add logic to handle opening feedback
        alert("Feedback opened");
    }

    function handleCloseFeedback(event) {
        //console.log("Closing feedback");
        // Add logic to handle closing feedback
        alert("Feedback closed");
    }
});