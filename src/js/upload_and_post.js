async function uploadAndPost() {
    const courseId = document.getElementById('courseIdInput').value.trim();
    if (!courseId) {
        alert('Please enter a Course ID.');
        return;
    }

    const fileInput = document.getElementById('csvFileInput');
    const file = fileInput.files[0];
    if (!file) {
        alert('Please select a CSV file.');
        return;
    }
if(file.size === 0){
    alert('Please select a CSV file that is not empty.');
    return;

}
    if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
        alert('Please submit a CSV file only.');
        return;
    }
    try {
        const text = await file.text();
        const emails = text.split('\n').map(line => line.trim()).filter(line => line !== '');
        const students = emails.join(',');

        const data = {
            id: courseId,
            students: students
        };

        const formData = new FormData();
        formData.append('data', JSON.stringify(data));

        const response = await fetch('php_to_db.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        document.getElementById('responseMessage').innerText = result.message;

    } catch (error) {
        document.getElementById('responseMessage').innerText = 'Error: ' + error.message;
    }
}


