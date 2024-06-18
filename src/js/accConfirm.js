

function handleResponse(data){
    console.log(data)
    if (data.success) {

        localStorage.setItem("userEmail", data.token);

        window.location.href = `main.html?user=${data.token}`;
    } else {
        document.getElementById("errMsg").textContent = data.message;
    }
}