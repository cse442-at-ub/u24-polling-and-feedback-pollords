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
    } else if (instrs === "") {
        err.textContent = "Please provide at least one instructor email for this course";
    } else if (sem.split(" ").length !== 2 || semChecker(sem) === false) {
        err.textContent = "Semester format should be \"Season Year\"";
    } else {
        err.textContent = ""; // passing tests
        // POST request function call goes here
    }
}

function semChecker(sem) {
    var semSplit = sem.split(" ");
    var season = semSplit[0].toLowerCase();
    var year = semSplit[1];
    var truthOne;
    var truthTwo;

    switch(season) {
        case "fall":
            truthOne = true;
            break;
        case "winter":
            truthOne = true;
            break;
        case "spring":
            truthOne = true;
            break;
        case "summer":
            truthOne = true;
            break;
        default:
            truthOne = false;
    }

    if (year.length !== 4) {
        truthTwo = false;
    } else if (parseInt(year) >= 2024 && parseInt(year) < 2100) { // being conservative and assuming this app will be abandoned in the next 75 years
        truthTwo = true;
    } else {
        truthTwo = false;
    }

    if (truthOne === false || truthTwo === false) {
        return false;
    } else {
        return true;
    }
}