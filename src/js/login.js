function handleLoginResponse(response) {
    if (response.status === 200) {
        // Handle successful login
        localStorage.setItem('authToken', response.token);
        window.location.href = "FIXME_URL_HERE"; // REPLACE WITH OUR ACTUAL URL
    } else if (response.status === 401) {
        // Handle invalid credentials
        document.querySelector('.error-message').innerText = response.message;
        document.querySelector('.error-message').style.display = 'block';
    } else if (response.status === 500) {
        // Handle server error
        document.querySelector('.error-message').innerText = response.message;
        document.querySelector('.error-message').style.display = 'block';
    }
}