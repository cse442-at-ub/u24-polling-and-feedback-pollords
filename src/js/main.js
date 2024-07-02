if (!localStorage.getItem("userEmail")) {
  window.location.href = "index.html"; // Redirect to login page
} else {
  checkLogin().then(()=>{
    const user = localStorage.getItem("userEmail");
    let courses = [];
    if(localStorage.getItem("courses")!==""){
        courses = localStorage.getItem("courses").split(',');
    }


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
            <div class="temp">
              <button onclick="startFeedback(${courseId})" style="margin-left: auto">Feedback</button>
              <button onclick="createPoll(${courseId})" style="display: none">Poll</button>
              <button onclick="uploadStudents(${courseId})">Upload Students File</button>
              <button onclick="addStudents(${courseId})">Add Students</button>
              <div class='errorText' id='errorText${courseId}' style="color: black;"></div>
              <input id='studentsfile${courseId}' type="file" style="display: none" onchange='updateError(${courseId})'></input>
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
function courseCreate(){
    // Logic to start feedback mode
    window.location.href = `courseCreation.html`;
}
function startFeedback(courseId) {
  // Logic to start feedback mode
  window.location.href = `InstructorFBUI.html?courseId=${courseId}`;
}

function createPoll(courseId) {
  // Logic to create a poll
  window.location.href = `poll.html?courseId=${courseId}`;
}

function updateError(courseId) {
    const fileInput = document.getElementById("studentsfile"+courseId);
    const file = fileInput.files[0]
    const errorText = document.getElementById("errorText"+courseId);
    errorText.style.color = "black";
    errorText.innerText = "Uploaded: "+file.name;
}

function uploadStudents(courseId){
    var input = document.getElementById("studentsfile"+courseId);
    input.type = 'file';
    input.click();
}

async function addStudents(courseId) {
    const errorText = document.getElementById("errorText"+courseId);
    if (!courseId) {
        console.log('Please enter a Course ID.');
        return;
    }

    //const file = input.files[0];
    const fileInput = document.getElementById("studentsfile"+courseId);
    const file = fileInput.files[0];

    if (!file) {
        errorText.style.color = "red";
        errorText.innerText = 'Please upload a CSV file.';
        console.log('Please select a CSV file.');
        return;
    }
    if(file.size === 0){
        console.log('Please select a CSV file that is not empty.');
        errorText.style.color = "red";
        errorText.innerText = 'Please select a CSV file that is not empty.';
        return;
    }
    if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
        console.log('Please submit a CSV file only.');
        errorText.style.color = "red";
        errorText.innerText = 'Please submit a CSV file only.';
        return;
        return;
    }

    const text = await file.text();
    const emails = text.split('\n').map(line => line.trim()).filter(line => line !== '');
    const students = emails.join(',');

    const formData = new FormData();
    formData.append("id",courseId)
    formData.append("students",students)


    const response = await fetch('php/addStudents.php', {
        method: 'POST',
        body: formData
    }).then((response) => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        //console.log(response)
        //console.log(response.text())
        let temp = response.json()
        //console.log(temp)
        return temp;
    })

        .then((result) => {
            if(result.success){
                errorText.style.color = "green";
                errorText.innerText = result.message;
            } else {
                errorText.style.color = "red";
                errorText.innerText = result.message;
            }
        })
        .catch((error) => {
            console.error("Error sending data to the backend:", error);
        });
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