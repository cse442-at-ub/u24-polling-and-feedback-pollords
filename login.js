function login(username, password, code) {
    const url = 'https://jsonplaceholder.typicode.com/posts';
    const payload = {
        username: username,
        password: password,
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}