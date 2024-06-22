
// Check if userEmail is stored in localStorage
if (!localStorage.getItem("userEmail")) {
  window.location.href = "index.html"; // Redirect to login page
} else {
  checkLogin().then(()=>{
    // Get firstName and lastName from URL parameters
    const user = localStorage.getItem("userEmail")

    // Display welcome message
    const welcomeMessage = document.getElementById("welcomeMessage");
    welcomeMessage.innerHTML = `${user}`;


    // Add click event listener to the logout button
    const logoutButton = document.getElementById("logoutButton");
    logoutButton.addEventListener("click", () => {
      // Remove userEmail from localStorage and redirect to login page
      localStorage.removeItem("userEmail");
      localStorage.removeItem("instructor");
      //window.location.href = "index.html";
      location.href = 'php/logOut.php';
    });
  })
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
        if(check==0){
          location.href = 'mainStud.html';
        }
      })
      .catch((error) => {
        console.error("Error sending data to the backend:", error);
      });
}

