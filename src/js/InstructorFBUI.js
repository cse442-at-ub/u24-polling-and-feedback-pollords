if (!localStorage.getItem("userEmail")) {
    window.location.href = "index.html"; // Redirect to login page
} else {
    checkFeedbackAuth()

}
function display(open){
    if(open==0){
        const dashboard = document.getElementById("dashboard");
        const courseBox = document.createElement('div');
        courseBox.className = 'courseBox';
        courseBox.innerHTML = `
            <div class="courseTitle">Not Open</div>
            `;
        dashboard.appendChild(courseBox);
    }
    if(open==1){
        const dashboard = document.getElementById("dashboard");
        const courseBox = document.createElement('div');
        courseBox.className = 'courseBox';
        courseBox.innerHTML = `
            <div class="courseTitle">Open</div>
            `;
        dashboard.appendChild(courseBox);
    }
}


function checkFeedbackAuth() {
    const urlParam = new URLSearchParams(window.location.search);
    let courseId = urlParam.get("courseId");
    const formData = new FormData();
    formData.append("courseID",courseId)

    console.log(formData)

    fetch("php/checkAuthFeedback.php", {
        method: "post",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            //console.log(response)
            //console.log(response.text())
            let temp = response.json()
            console.log(temp)
            return temp;
        }).then((data) => {
        let check = data.instructor;
        if(check == -1){
            location.href = 'main.html';
        }
        if(check == 0){
            location.href = 'mainStud.html';
        }
        display(data.feedbackOpen)

    })
        .catch((error) => {
            console.error("Error sending data to the backend:", error);
        });
}