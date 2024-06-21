function backFunc() {
    window.location.href = `main.html`;
}

function errorCatcher() {
    var name = document.getElementById("courseName").value;
    var code = document.getElementById("courseCode").value;
    var sem = document.getElementById("semester").value;
    var instrs = document.getElementById("instructorEmails").value;
    var err = document.getElementById("error");

    if (name === "" || code === "" || sem === "") {
        err.textContent = "Please fill out all four fields";
    }
    else if (instrs === "") {
        err.textContent = "Please provide at least one instructor email for this course";
    }
}