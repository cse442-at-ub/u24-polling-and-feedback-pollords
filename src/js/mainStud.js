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
                <button onclick="provideFeedback(${courseId})">Feedback</button>
                <button onclick="participatePoll(${courseId})">Poll</button>
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
  
  function provideFeedback(courseId) {
    // Logic to provide feedback
  }
  
  function participatePoll(courseId) {
    // Logic to participate in poll
  }
  
  
  async function checkLogin() {
    fetch("php/checkLogin.php", {
        method: "get",
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            //console.log(response)
            //console.log(response.text())
            let temp = response.json()
            //console.log(temp)
            return temp;
        }).then((data) => {
        let check = data.instructor;
        console.log(check)
        if(check==-1){
            localStorage.removeItem("userEmail");
            localStorage.removeItem("instructor");
            location.href = 'php/logOut.php';
        }
        if(check==1){
            location.href = 'main.html';
        }
    })
        .catch((error) => {
            console.error("Error sending data to the backend:", error);
        });
  }