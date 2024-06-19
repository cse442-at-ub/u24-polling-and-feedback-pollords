// Function to handle login data submission
async function submitLogin() {
  var addr = document.getElementById("emailLogin").value;
  var pw = document.getElementById("passwordLogin").value;

  const formData = new FormData();
  formData.append("email", addr);
  formData.append("password", pw);
  console.log(formData);

  fetch("php/login-user.php", {
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
          handleLoginResponse(data);
      })
      .catch((error) => {
          console.error("Error sending data to the backend:", error);
      });
}

function handleLoginResponse(response) {
  if (response.status === 200) {
      // Handle successful login
      localStorage.setItem('authToken', response.token);
      window.location.href = "dashboard.html";
  } else if (response.status === 401) {
      // Handle invalid credentials
      const errorMessage = document.querySelector('.error-message');
      errorMessage.innerText = response.message;
      errorMessage.style.display = 'block';
  } else if (response.status === 500) {
      // Handle server error
      const errorMessage = document.querySelector('.error-message');
      errorMessage.innerText = response.message;
      errorMessage.style.display = 'block';
  }
}