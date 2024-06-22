if (!localStorage.getItem("userEmail")) {
  window.location.href = "index.html"; // Redirect to login page
} else {
  checkLogin().then(()=>{
    const user = localStorage.getItem("userEmail");
    const courses = localStorage.getItem("courses").split(',');

    const welcomeMessage = document.getElementById("welcomeMessage");
    welcomeMessage.innerHTML = `${user}`;

    const dashboard = document.getElementById("dashboard");
    courses.forEach(courseId => {
      fetch(`php/getCourseDetails.php?courseId=${courseId}`)
        .then(response => response.json())
        .then(course => {
          const courseBox = document.createElement('div');
          courseBox.className = 'courseBox';
          courseBox.innerHTML = `
            <div class="courseTitle">${course.courseName}</div>
            <div>
              <button onclick="startFeedback(${courseId})">Feedback</button>
              <button onclick="createPoll(${courseId})">Poll</button>
              <button onclick="addStudents(${courseId})">Add Students</button>
              <div id="errorText" style="color: red;"></div>
            </div>`;
          dashboard.appendChild(courseBox);
        });
    });

    const logoutButton = document.getElementById("logoutButton");
    logoutButton.addEventListener("click", () => {
      localStorage.removeItem("userEmail");
      localStorage.removeItem("instructor");
      localStorage.removeItem("courses");
      location.href = 'php/logOut.php';
    });
  });
}

function startFeedback(courseId) {
  // Logic to start feedback mode
  window.location.href = `feedback.html?courseId=${courseId}`;
}

function createPoll(courseId) {
  // Logic to create a poll
  window.location.href = `poll.html?courseId=${courseId}`;
}

function addStudents(courseId) {
  // Logic to add students
  // This could involve opening a file upload dialog and handling the upload
  // Here we just show an example error message for demonstration purposes
  const errorText = document.getElementById('errorText');
  errorText.innerText = 'An error occurred while adding students.';
}

async function checkLogin() {
  fetch("php/checkLogin.php", {
      method: "get",
  })
      .then((response) => {
          if (!response.ok) {
              throw new Error("Network response was not ok");
          }
          let temp = response.json();
          return temp;
      }).then((data) => {
      let check = data.instructor;
      if(check == -1){
          localStorage.removeItem("userEmail");
          localStorage.removeItem("instructor");
          location.href = 'php/logOut.php';
      }
      if(check == 0){
          location.href = 'mainStud.html';
      }
  })
      .catch((error) => {
          console.error("Error sending data to the backend:", error);
      });
}