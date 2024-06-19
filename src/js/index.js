
function handleResponse(data){
    console.log(data)
  if (data.success) {
    localStorage.setItem("userEmail", data.token);

    window.location.href = `main.html?user=${data.token}`;
  } else {
    const errorElement = document.getElementById(`errorLogin`);
    errorElement.innerHTML = data.message;
    errorElement.style.display = "block";
  }
}





