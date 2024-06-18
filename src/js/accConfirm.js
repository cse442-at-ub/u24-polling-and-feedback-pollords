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

}

