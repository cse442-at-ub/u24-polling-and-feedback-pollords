async function setFeedback(action,courseID) {
    const formData = new FormData();
    formData.append("action",action)
    formData.append("courseID",courseID)
    if (action != 1 && action != 0) {
        console.log('Invalid Request, must be 1 or 0');
    } else {
        try {
            const response = await fetch('../php/setFeedback.php', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (data.status == 1) {
                console.log(data.message);
                //location.reload();
            } else {
                console.log(data.message);
            }


        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    }
}


