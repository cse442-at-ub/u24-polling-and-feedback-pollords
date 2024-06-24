

// Function to handle login data submission
async function submitLogin() {

  var addr = document.getElementById("emailLogin").value;
  var pw = document.getElementById("passwordLogin").value;

  const formData = new FormData();
  formData.append("email",addr)
  formData.append("password",pw)
  //console.log(loginForm)
  console.log(formData)


  fetch("php/login-user.php", {
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
      //console.log(temp)
      return temp;
    })


    .then((data) => {
      handleResponse(data)
    })
    .catch((error) => {
      console.error("Error sending data to the backend:", error);
    });
}

function handleResponse(data){
  console.log(data)
  if (data.success) {
    localStorage.setItem("userEmail", data.email);
    localStorage.setItem("instructor", data.instructor);
    localStorage.setItem("courses", data.courses);

    if (data.instructor == 1) {
      window.location.href = `main.html`;
    } else {
      window.location.href = `mainStud.html`;
    }
  } else {
    const errorElement = document.getElementById(`errorLogin`);
    errorElement.innerHTML = data.message;
    errorElement.style.display = "block";
  }
}


function toRegistration() {
  document.location = 'accCreation.html';
}

