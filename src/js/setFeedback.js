async function setFeedback(action) {
    try {
        const response = await fetch('setFeedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action })
        });

        const data = await response.json();

        if (data.status === 1) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }

        if (data.status !== 1 && data.status !== 0) {
            console.log('Invalid Request, must be 1 or 0');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    }
}
