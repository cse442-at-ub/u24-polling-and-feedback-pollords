

function handleResponse(data){
    console.log(data)
    if (data.success) {
        window.location.href = `accConfirmation.html`;
    } else {
        document.getElementById("errMsg").textContent = data.message;
    }
}