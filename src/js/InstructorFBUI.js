document.addEventListener('DOMContentLoaded', (event) => {
    if (!localStorage.getItem("userEmail")) {
        window.location.href = "index.html"; // Redirect to login page
    } else {
        checkFeedbackAuth();
    }

    function displayFeedback(isOpen, numberOfResponses = 0, medianRating = 0) {
        const feedbackContainer = document.getElementById("feedback-container");
        const feedbackStatus = document.getElementById("feedbackStatus");

        if (feedbackContainer) {
            if (isOpen == 0) {
                feedbackStatus.textContent = "Closed Feedback";
                feedbackContainer.innerHTML = `
                    <div class="container">
                        <p>Class: CSE 442 Software Engineering Concepts</p>
                        <p>Open feedback to allow students to rate the pace of the class. Answers range from 1 to 5, with:</p>
                        <ul>
                            <li>1 - "I'm Lost"</li>
                            <li>2 - "I'm slightly struggling"</li>
                            <li>3 - "Just right"</li>
                            <li>4 - "I'm very comfortable"</li>
                            <li>5 - "This is easy"</li>
                        </ul>
                        <button id="open-feedback-btn">Open Feedback</button>
                    </div>`;

                const openButton = document.getElementById('open-feedback-btn');
                if (openButton) {
                    openButton.addEventListener('click', handleOpenFeedback);
                }
            } else if (isOpen == 1) {
                feedbackStatus.textContent = "Opened Feedback";
                feedbackContainer.innerHTML = `
                    <div class="container">
                        <p>Class: CSE 442 Software Engineering Concepts</p>
                        <p>Open feedback to allow students to rate the pace of the class. Answers range from 1 to 5, with:</p>
                        <ul>
                            <li>1 - "I'm Lost"</li>
                            <li>2 - "I'm slightly struggling"</li>
                            <li>3 - "Just right"</li>
                            <li>4 - "I'm very comfortable"</li>
                            <li>5 - "This is easy"</li>
                        </ul>
                        <p>Number of Responses: ${numberOfResponses}</p>
                        <p>Median Rating: ${medianRating}</p>
                        <button id="close-feedback-btn">Close Feedback</button>
                    </div>`;

                const closeButton = document.getElementById('close-feedback-btn');
                if (closeButton) {
                    closeButton.addEventListener('click', handleCloseFeedback);
                }
            }
        }
    }

    function checkFeedbackAuth() {
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        const formData = new FormData();
        formData.append("courseID", courseId);
        formData.append("userEmail", localStorage.getItem("userEmail"));

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
            let isInstructor = data.instructor;
            if (!isInstructor) {
                location.href = 'mainStud.html';
            }
            displayFeedback(data.feedbackOpen, data.numberOfResponses, data.medianRating);
        })
        .catch((error) => {
            console.error("Error sending data to the backend:", error);
        });
    }

    function handleOpenFeedback(event) {
        alert("Feedback opened");
        // Add logic to handle opening feedback
    }

    function handleCloseFeedback(event) {
        alert("Feedback closed");
        // Add logic to handle closing feedback
    }
});