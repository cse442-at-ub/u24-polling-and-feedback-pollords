async function setFeedback(action) {
    try {
        const response = await fetch('setFeedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({action })
        });

        const data = await response.json();

        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert(data.message);
        }
        if(data !== 'true' && data !== 'false'){
            console.log('Invalid Request, must be true or false');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    }
}