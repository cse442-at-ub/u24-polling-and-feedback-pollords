


async function postReq(email, pw) {
    const formData = new FormData();
    formData.append("email",email)
    formData.append("password",pw)
    formData.append("instr",document.querySelector("#ddBtn span").innerText)

    console.log(formData)
    fetch("php/registrationPHP.php", {
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

}