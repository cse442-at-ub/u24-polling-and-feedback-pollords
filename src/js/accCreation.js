

function openCloseDropDown () {
    stdnt = document.getElementById("student");
    strctr = document.getElementById("instructor");

    if (stdnt.textContent === "") {
        stdnt.textContent = "Student";
        strctr.textContent = "Instructor";
        stdnt.style.display = "block";
        strctr.style.display = "block";
        document.getElementById("ddList").style.backgroundColor = "#F0F0F0";
    } else {
        stdnt.textContent = "";
        strctr.textContent = "";
        stdnt.style.display = "none";
        strctr.style.display = "none";
        document.getElementById("ddList").style.backgroundColor = "#A5C7CE";
    }
}

function typeSelector (type) {
    var img = document.getElementById("ddImg");
    if (type === 'student') {
        document.querySelector("#ddBtn span").innerText = "Student";
        document.querySelector("#ddBtn img").innerText = img;
        openCloseDropDown();
    } else {
        document.querySelector("#ddBtn span").innerText = "Instructor";
        document.querySelector("#ddBtn img").innerText = img;
        openCloseDropDown();
    }
}


function errDisplay () {
    var addr = document.getElementById("email").value;
    var pw = document.getElementById("password").value;
    var cpw = document.getElementById("confirmPassword").value;
    console.log(pw)
    var err = document.getElementById("errMsg");
    if (addr === "" || pw === "" || cpw === "") {
        err.textContent = "Please fill out all three fields and select a role";
    } else if  (addr.slice(-12) !== "@buffalo.edu"){
        err.textContent = "Email must be a buffalo.edu address";
    } else if (pw !== cpw) {
        err.textContent = "Passwords do not match";
    } else if (document.querySelector("#ddBtn span").innerText == "Account Type") {
        err.textContent = "Please select an account type";
    } else if (pw = cpw && pw.length < 8) {
        err.textContent = "Password needs to be at least 8 characters in length"
    } else { // IN THE FUTURE - ADD CHECK FOR IF EMAIL ALREADY REGISTERED
        err.textContent = "";
        postReq(addr, document.getElementById("password").value)
        

    }
}

function requestHandler(msg) {
    if (msg === "Successful code confirmation") {
        console.log("Request successful!");
    } else if (msg === "Incorrect password length") {
    }
    if (msg === "Incorrect password length") {
        console.log("Incorrect password length");
    }
}


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
    console.log(data)
    if (data.success) {
        window.location.href = `accConfirmation.html`;
    } else {
        document.getElementById("errMsg").textContent = data.message;
    }
}

