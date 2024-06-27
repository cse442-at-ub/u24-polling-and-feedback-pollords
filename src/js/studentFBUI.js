document.addEventListener('DOMContentLoaded', (event) => {
    // Redirect to login page if userEmail is not in localStorage
    if (!localStorage.getItem("userEmail")) {
        window.location.href = "index.html"; // Redirect to login page
    } else {
        checkFeedbackAuth();
    }

    function displayFeedback(isOpen) {
        if (isOpen == 0) {
            document.getElementById("feedback-container").style.display = "none";
            document.getElementById("unavailable-container").style.display = "flex";
        } else if (isOpen == 1) {
            document.getElementById("feedback-container").style.display = "block";
            document.getElementById("unavailable-container").style.display = "none";
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
                location.href = 'main.html';
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
        // Add your form submission logic here (e.g., sending data to the server)
    }

    const submitButton = document.getElementById('submit-btn');
    if (submitButton) {
        submitButton.addEventListener('click', handleFormSubmission);
    }
});