
function confirm () {
    var err = document.getElementById("errMessage");
    var code = document.getElementById("confirmCode").value;
    if (code.length === 0) {
        err.textContent = "Please enter a confirmation code"
    }
    else if (code.length !== 7) {
        err.textContent = "Entered code was not 7 characters in length";
    } else {
        postReq(code);
    }
}
async function postReq(code) {

    const formData = new FormData();
    formData.append("codeIn",code)
    console.log(formData)
    fetch("php/confirm_code.php", {
        method: "post",
        body: formData,
    }).then((response) => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        //console.log(response)
        //console.log(response.text())
        let temp = response.json()
        //console.log(temp)
        return temp;
    }).then((data) => {
        handleResponse(data)
    })
        .catch((error) => {

            console.error("Error sending data to the backend:", error);
        });

    //document.location = 'accConfirmation.html'; // this should be nested within condition of POST request success
}


function handleResponse(data){
    console.log(data)
    if (data.success) {
        localStorage.setItem("userEmail", data.email);
        localStorage.setItem("instructor", data.instructor);

        window.location.href = `main.html`;
    } else {
        document.getElementById("errMessage").textContent = data.message;
    }
}

