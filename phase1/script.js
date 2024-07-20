const popupContainer = document.querySelector('.popup-container');


document.getElementById("registrationForm").onsubmit = function () {
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;
    var passwordMessage = document.getElementById("passwordMessage");

    if (password.length < 8) {
        passwordMessage.innerHTML = "Password must be at least 8 characters long";
        return false;
    }

    var hasNumber = /\d/.test(password);
    if (!hasNumber) {
        passwordMessage.innerHTML = "Password must contain at least one number";
        return false;
    }

    var hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    if (!hasSpecial) {
        passwordMessage.innerHTML = "Password must contain at least one special character";
        return false;
    }

    if (password !== confirm_password) {
        passwordMessage.innerHTML = "Passwords do not match";
        return false;
    }

    passwordMessage.innerHTML = "";
};

function getParameterByName(name) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(window.location.href);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

if (getParameterByName('message') != null) {
    document.getElementById('message').textContent = getParameterByName('message');
}

document.addEventListener('DOMContentLoaded', function () {
    var usernameInput = document.getElementById('username');
    var errorStatus = document.getElementById('already-taken-status');
    var availableStatus = document.getElementById('available-status');

    usernameInput.addEventListener('blur', function () {
        var username = usernameInput.value.trim();
        if (username.length > 0) {
            var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
            xhr.open('POST', 'check_username.php', true); // Configure it to send a POST request to check_username.php
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set the content type of the request

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // Request is successful
                    if (xhr.responseText === "200") {
                        availableStatus.textContent = "Username is available";
                        errorStatus.textContent = "";
                    } else if (xhr.responseText === "403") {
                        availableStatus.textContent = ""; // Display the response
                        errorStatus.textContent = "Username already Taken!"; // Display the response
                        var form = document.getElementById('registrationForm');
                        form.addEventListener('submit', function (event) {
                            // Prevent the form from submitting
                            event.preventDefault();
                        })
                    }
                } else {
                    // Handle errors here, like a status code outside the 200 range
                    availableStatus.textContent = "";
                    errorStatus.textContent = 'Error checking username.';
                }
            };

            // Handle network errors
            xhr.onerror = function () {
                errorStatus.textContent = 'Network error. Please try again.';
                availableStatus.textContent = '';
            };

            // Send the request with the username data
            xhr.send('username=' + encodeURIComponent(username));
        } else {
            errorStatus.textContent = 'Username field cannot be empty.'; // Display message if the username field is empty
            availableStatus.textContent = ''; // Display message if the username field is empty
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('registrationForm');
    form.addEventListener('submit', function (event) {
        // Prevent the form from submitting
        event.preventDefault();

        var email = document.getElementById('email').value;
        if (email.length > 0) {
            // Start the email check
            fetch('check_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'exists') {
                        document.getElementById('email-error').textContent = 'Email is already registered.';
                    } else {
                        form.submit(); // Uncomment this line to submit the form if email is not registered
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
    var today = new Date();
    var dateStr = today.toISOString().substr(0, 10);

    var dateOfBirthField = document.getElementById('birthdate');
    if (dateOfBirthField) {
        dateOfBirthField.setAttribute('max', dateStr);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // const showPopup = document.getElementById('showDatePicker');
    const showPopup = document.querySelector('.show-popup');
    const birthdateInput = document.getElementById('birthdate'); // Access the birthdate input field
    const popupContainer = document.querySelector('.popup-container');
    const closeBtn = document.querySelector('.close-btn');

    showPopup.onclick = () => {
        // popupContainer.classList.add('active');
        const birthdate = birthdateInput.value; // Get the value of the input field

        if (birthdate) {
            let [year, month, day] = birthdate.split('-');
            let birthdateWithoutYear = `${month}-${day}`;

            // Fetch request to the PHP file with birthdate as a query parameter
            fetch('API_ops.php?birthdate=' + encodeURIComponent(birthdateWithoutYear))
                .then(response => {
                    if (response.ok) {
                        return response.json();  // Process the response as JSON
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    const imagesContainer = document.getElementById('imagesContainer');
                    imagesContainer.innerHTML = ''; // Clear previous images if any

                    data.forEach(actor => {
                        let img = document.createElement('img');
                        img.src = actor.imageUrl;
                        img.alt = actor.name;
                        img.style.width = '100px'; // Set image width or other styling as needed
                        img.style.margin = '10px'; // Add some space around each image
                        let name = document.createElement('p');
                        name.textContent = actor.name; // Add the actor's name
                        name.style.color = 'black'; // Styling for the text

                        imagesContainer.appendChild(img);
                        imagesContainer.appendChild(name);
                    });

                    popupContainer.classList.add('active'); // Show the popup
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        } else {
            console.error('Birthdate is required');
        }
    };
    closeBtn.onclick = () => {
        popupContainer.classList.remove('active');
    }
});