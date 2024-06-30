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
                console.log("Feedback CLosed")
                feedbackStatus.textContent = "Closed Feedback";
                feedbackContainer.innerHTML = `
                    <div class="container">
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
                console.log("Feedback OPen")
                feedbackContainer.innerHTML = `
                    <div class="container">
                        <p>Open feedback to allow students to rate the pace of the class. Answers range from 1 to 5, with:</p>
                        <ul>
                            <li>1 - "I'm Lost"</li>
                            <li>2 - "I'm slightly struggling"</li>
                            <li>3 - "Just right"</li>
                            <li>4 - "I'm very comfortable"</li>
                            <li>5 - "This is easy"</li>
                        </ul>
                        <canvas id="myBarChart"></canvas>
                        <button id="close-feedback-btn">Close Feedback</button>
                    </div>`;

                const closeButton = document.getElementById('close-feedback-btn');
                if (closeButton) {
                    closeButton.addEventListener('click', handleCloseFeedback);
                }
                var ctx = document.getElementById('myBarChart').getContext('2d');


                // Create the data for the bar chart
                var data = {
                    labels: ['1-Lost', '2-Struggling', '3-Just Right', '4-Comfortable', '5-Easy'],
                    datasets: [{
                        label: 'Responses',
                        data: [0,0,0,0,0],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                }

                // Create the bar chart
                var myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })
                updateChart(myBarChart)
                setInterval(function () {updateChart(myBarChart);},5000);

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
        console.log("Feedback opened");
        // Add logic to handle opening feedback
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        setFeedback(1,courseId);
    }

    function handleCloseFeedback(event) {
        console.log("Feedback closed");
        // Add logic to handle closing feedback
        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        setFeedback(0,courseId);
    }
    async function setFeedback(action,courseID) {
        const formData = new FormData();
        formData.append("action",action)
        formData.append("courseID",courseID)
        if (action != 1 && action != 0) {
            console.log('Invalid Request, must be 1 or 0');
        } else {
            try {
                const response = await fetch('php/setFeedback.php', {
                    method: 'POST',
                    body: formData,
                });

                const data = await response.json();

                if (data.status == 1) {
                    console.log(data.message);
                    location.reload();
                } else {
                    console.log(data.message);
                }


            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            }
        }
    }
    async function sendCourseId(courseId,chart) {
        // Check for invalid course ID formats
        console.log(courseId);
        const formData = new FormData();
        formData.append("courseId",courseId)
        if (isNaN(courseId) || courseId < 0) {
            console.error("Invalid Course Format");
            document.getElementById('response').innerText = "Invalid Course Format";
            return;
        }

        try {
            const response = await fetch('php/getFeedbackResults.php', {
                method: 'POST',
                body: formData
            });
            //console.log(response.text())

            const result = await response.json();
            console.log(result)
            //document.getElementById('response').innerText = JSON.stringify(result);
            var data = {
                labels: ['1-Lost', '2-Struggling', '3-Just Right', '4-Comfortable', '5-Easy'],
                datasets: [{
                    label: 'Responses',
                    data: [result.one,result.two,result.three,result.four,result.five],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }



            chart.data = data;
            chart.update('none');
        } catch (error) {
            console.error("Error:", error);
            // document.getElementById('response').innerText = "Error: Unable to fetch feedback results.";
        }
    }

    function updateChart(chart){

        const urlParam = new URLSearchParams(window.location.search);
        let courseId = urlParam.get("courseId");
        sendCourseId(courseId,chart);
    }




});