document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('courseForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way
        const courseId = document.getElementById('courseId').value;
        sendCourseId(courseId);
    });
});

async function sendCourseId(courseId) {
    // Check for invalid course ID formats
    if (isNaN(courseId) || courseId < 0) {
        console.error("Invalid Course Format");
        document.getElementById('response').innerText = "Invalid Course Format";
        return;
    }

    try {
        const response = await fetch('/u24-polling-and-feedback-pollords/src/php/getFeedbackResults.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ courseId: courseId }),
        });

        const result = await response.json();
        document.getElementById('response').innerText = JSON.stringify(result);
    } catch (error) {
        console.error("Error:", error);
        document.getElementById('response').innerText = "Error: Unable to fetch feedback results.";
    }
}