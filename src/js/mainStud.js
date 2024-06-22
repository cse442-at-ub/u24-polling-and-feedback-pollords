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
            courseBox.innerHTML = `
              <div class="courseBox">
                <div class="courseTitle">${course.courseName}</div>
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