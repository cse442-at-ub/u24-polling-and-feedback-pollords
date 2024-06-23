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
    } 
    /*else if (sem.split(" ").length !== 2 || semChecker(sem) === false) {
        err.textContent = "Semester format should be \"Season Year\"";
    }*/
    else if (sem.split(",").length !== 2 || semChecker(sem) === false) {
        err.textContent = "Semester format should be \"Season,Year\" (no spaces)";
    } else if (code.split(" ").length !== 2 || codeChecker(code) === false) {
        err.textContent = "Course code format should be \"CRS 101\"";
    } else if (emailChecker(instrs) === false) {
        err.textContent = "Provide buffalo.edu addresses separated by commas"
    } else {
        err.textContent = ""; // passing tests
        // POST request function call goes here
        coursePOST(name, code, sem, instrs, err.textContent); // moved
    }
    //coursePOST(name, code, sem, instrs, err.textContent);
}

function semChecker(sem) {
    //var semSplit = sem.split(" ");
    var semSplit = sem.split(",");
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

function codeChecker(code) {
    var codeSplit = code.split(" ");
    if (isNaN(parseInt(codeSplit[0])) === false) {
        return false;
    } else if (isNaN(parseInt(codeSplit[1])) === true) {
        return false;
    } else if (codeSplit[0].length !== 3 || codeSplit[1].length !== 3) {
        return false;
    } else {
        return true; // passes tests
    }
}

function emailChecker(instrs) {
    return true;
    /* instrsSplit = instrs.split(",");
    var instances = instrs.match(/@/g).length
    if (instances !== instrsSplit.length) {
        return false;
    }
    for (let i = 0; i < instrsSplit.length; i++) {
        if (instrsSplit[i].slice(-12) !== "@buffalo.edu") {
            return false;
        }
    }
    return true; // passes tests*/
}

async function coursePOST(name, code, sem, instrs, err) {
    /*if (err !== "") {
        console.log(err);
        return;
    }*/

    code = code.toUpperCase();
    sem = sem[0].toUpperCase() + sem.slice(1).toLowerCase();
    instrs = instrs.replace(/\s/g,'');
    instrs = instrs.toLowerCase();

    const courseData = new FormData();
    courseData.append("name", name);
    courseData.append("code", code);
    courseData.append("sem", sem);
    //courseData.append("instrs", instrs.split(","));
    courseData.append("instrs", instrs);
    //courseData.append("creator", localStorage.userEmail);
    
    fetch("php/addCourseDB.php", {
        method: "post",
        body: courseData,
    }).then((response) => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        let temp = response.json()
        return temp;
    }).then((data) => {
        responder(data);
    })
    .catch((error) => {
        console.error("Error sending data to the backend:", error);
    });
}

function responder(data) {
    if (data.success) {
        window.location.href = `main.html`;
    } else {
        document.getElementById("error").textContent = data.message;
    }
}