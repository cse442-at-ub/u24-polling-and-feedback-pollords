document.addEventListener('DOMContentLoaded', (event) => {
    //console.log("DOM fully loaded and parsed");

    if (!localStorage.getItem("userEmail")) {
        //console.log("No userEmail in localStorage, redirecting to login page");
        window.location.href = "index.html"; // Redirect to login page
    } else {
        //console.log("userEmail found in localStorage, checking feedback authorization");
        checkFeedbackAuth();
    }

    function displayFeedback(isOpen) {
        if (isOpen == 0) {
            console.log("Feedback is not open, displaying unavailable message");
            const dashboard = document.getElementById("feedback-container");
            const container = document.createElement('div');
            container.className = 'container';
            container.innerHTML = `
                <p>Feedback for this course is currently unavailable.</p>`
            dashboard.appendChild(container);
            //document.getElementById("feedback-container").style.display = "none";
            //document.getElementById("unavailable-container").style.display = "flex";
        } else if (isOpen == 1) {
            const dashboard = document.getElementById("feedback-container");
            const container = document.createElement('div');
            container.className = 'container';
            container.innerHTML = `
                <p>Class: CSE 442 Software Engineering Concepts</p>
                <p>Please enter your feedback for how comfortable you are with the pace of the class. Answer using a number from 1 to 5, with:</p>
                <p>Feedback Level</p>
                <div class="dropdown-container">
                    <select id="feedback-level">
                        <option value="1 - I'm Lost">1</option>
                        <option value="2 - I'm slightly struggling">2</option>
                        <option value="3 - Just right">3</option>
                        <option value="4 - I'm very comfortable">4</option>
                        <option value="5 - This is easy ">5</option>
                    </select>
                </div>
                <button id="submit-btn">Submit</button> `
            dashboard.appendChild(container);
            console.log("Feedback is open, displaying feedback form");
            //document.getElementById("feedback-container").style.display = "block";
            //document.getElementById("unavailable-container").style.display = "none";
            const submitButton = document.getElementById('submit-btn');
            if (submitButton) {
                //console.log("Adding event listener to submit button");
                submitButton.addEventListener('click', handleFormSubmission);
            }
        }
    }

    function checkFeedbackAuth() {
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        const formData = new FormData();
        formData.append("courseID", courseId);

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
            let check = data.instructor;
            if (check == 1) {
                //console.log("User is an instructor, redirecting to mainStud.html");
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
        //const feedbackLevel = document.getElementById('feedback-level').value;
        alert(`Feedback submitted: ${feedbackLevel}`);
        // Add form submission logic 
    }

    const submitButton = document.getElementById('submit-btn');
    if (submitButton) {
        //console.log("Adding event listener to submit button");
        submitButton.addEventListener('click', handleFormSubmission);
    }
});