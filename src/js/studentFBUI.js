document.addEventListener('DOMContentLoaded', (event) => {
    if (!localStorage.getItem("userEmail")) {
        window.location.href = "index.html"; // Redirect to login page
    } else {
        checkFeedbackAuth();
    }

    function displayFeedback(isOpen) {
        const feedbackStatus = document.getElementById("feedbackStatus");
        const feedbackContainer = document.getElementById("feedback-container");

        if (isOpen == 0) {
            feedbackStatus.textContent = "Feedback Closed";
            feedbackContainer.innerHTML = `
                <div class="container">
                    <p>Feedback for this course is currently unavailable.</p>
                </div>`;
        } else if (isOpen == 1) {
            feedbackStatus.textContent = "Feedback Open";
            feedbackContainer.innerHTML = `
                <div class="container">
                    <p>Class: CSE 442 Software Engineering Concepts</p>
                    <p></p>
                    <p>Please enter your feedback for how comfortable you are with the pace of the class. Answer using a number from 1 to 5, with:</p>
                    <p></p>
                    <p>Feedback Level</p>
                    <div class="dropdown-container">
                        <select id="feedback-level">
                            <option value="1 - I'm Lost">1 - I'm Lost</option>
                            <option value="2 - I'm slightly struggling">2 - I'm slightly struggling</option>
                            <option value="3 - Just right">3 - Just right</option>
                            <option value="4 - I'm very comfortable">4 - I'm very comfortable</option>
                            <option value="5 - This is easy">5 - This is easy</option>
                        </select>
                    </div>
                    <button id="submit-btn">Submit</button>
                </div>`;
            
            const submitButton = document.getElementById('submit-btn');
            if (submitButton) {
                submitButton.addEventListener('click', handleFormSubmission);
            }
        }
    }

    function checkFeedbackAuth() {
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        const formData = new FormData();
        formData.append("courseID", courseId);

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
            let check = data.instructor;
            if (check == 1) {
                location.href = 'mainStud.html';
            }
            displayFeedback(data.feedbackOpen);
        })
        .catch((error) => {
            console.error("Error sending data to the backend:", error);
        });
    }

    function handleFormSubmission(event) {
        event.preventDefault();
        const feedbackLevel = document.getElementById('feedback-level').value;
        alert(`Feedback submitted: ${feedbackLevel}`);
        // Add form submission logic 
    }
});