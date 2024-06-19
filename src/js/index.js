
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

}



