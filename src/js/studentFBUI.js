//import 'studentFB.js';

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
                            <option data-display="1 - I'm Lost" value="1">1 - I'm Lost</option>
                            <option data-display="2 - I'm slightly struggling" value="2">2 - I'm slightly struggling</option>
                            <option data-display="3 - Just right" value="3">3 - Just right</option>
                            <option data-display="4 - I'm very comfortable" value="4">4 - I'm very comfortable</option>
                            <option data-display="5 - This is easy" value="5">5 - This is easy</option>
                        </select>
                    </div>
                    <button id="submit-btn">Submit</button>
                    <div id="errorText" style="display: none"></div>
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
        //alert(`Feedback submitted: ${feedbackLevel}`);
        // Add form submission logic
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        feedbackPOST(courseId,feedbackLevel);

    }
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
            //console.log(response.text())
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
            let temp = document.getElementById('errorText')
            temp.innerHTML = data.message;
            temp.style.color = "green";
            temp.style.display = "block";
        } else {
            console.log(data.message);
            let temp = document.getElementById('errorText')
            temp.innerHTML = data.message;
            temp.style.color = "red";
            temp.style.display = "block";
            // throw new Error("Feedback submission failed");
            // access and print error ID if in document or just return new error
        }
    }

});