function resetForm(form) {
    form.reset();

    const spans = document.querySelectorAll('#registrationForm span');

    spans.forEach(function (span) {
        span.textContent = "";
    });

}

function validatePassword(password, confirm_password, errorElement) {
    errorElement.textContent = ""; // Clear previous messages

    if (!password || password.length < 8) {
        errorElement.textContent = "Password must be at least 8 characters long";
        return false;
    }

    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        errorElement.textContent = "Password must contain at least one special character";
        return false;
    }

    if (!/\d/.test(password)) {
        errorElement.textContent = "Password must contain at least one number";
        return false;
    }

    if (!validateConfirmationPassword(password, confirm_password, errorElement))
        return false;

    errorElement.textContent = "";
    return true;
}

function validateConfirmationPassword(password, confirm_password, errorElement) {
    if (password !== confirm_password) {
        errorElement.textContent = "Passwords do not match";
        return false;
    }

    // Clear any previous errors if all validations pass
    errorElement.textContent = "";
    return true;
}

function validateFullName(full_name, errorElement) {
    if (full_name.length < 3) {
        errorElement.textContent = "The full name field must be at least 3 characters.";
        return false;
    }
    if (!/^[a-zA-Z\s\-]+$/.test(full_name)) {
        errorElement.textContent = "The full name should contains only alphabets";
        return false;
    }

    errorElement.textContent = "";
    return true;
}

async function checkUsernameAvailability(username) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = new Headers({
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': csrfToken
    });

    const messageElement = document.getElementById('username-message');

    if (username.length < 3) {
        messageElement.textContent = "Username minimum length is 3 characters.";
        messageElement.classList.remove('available');
        messageElement.classList.add('error');
        return false;
    } else if (username.length > 255) {
        messageElement.textContent = "Username maximum length is 255 characters.";
        messageElement.classList.remove('available');
        messageElement.classList.add('error');
        return false;
    } else {
        messageElement.textContent = ""; // Clear any previous messages if the length is valid
        messageElement.classList.remove('error');
    }

    try {
        const response = await fetch('/check-username', {
            method: 'POST',
            headers: headers,
            body: 'username=' + encodeURIComponent(username)
        });
        if (!response.ok) throw new Error(response.statusText);

        const data = await response.json();
        // const messageElement = document.getElementById('username-message');

        if (data.exists) {
            messageElement.textContent = "Username already taken!";
            messageElement.classList.remove('available');
            messageElement.classList.add('error');
        } else {
            messageElement.textContent = "Username is available";
            messageElement.classList.remove('error');
            messageElement.classList.add('available');
        }
        return !data.exists; // returns true if username is available
    } catch (error) {
        console.error('Error checking username:', error);
        const messageElement = document.getElementById('username-message');
        messageElement.textContent = 'Error checking username.';
        messageElement.classList.add('error');
        return false;
    }
}

async function checkEmailAvailability(email) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = new Headers({
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': csrfToken
    });
    try {
        const response = await fetch('/check-email', {
            method: 'POST',
            headers: headers,
            body: 'email=' + encodeURIComponent(email)
        });
        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();
        if (data.exists) {
            document.getElementById('email-error').textContent = 'Email is already registered.';
            return false;
        } else {
            return true;
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('failed').textContent = "Network error. Please try again.";
        document.getElementById('failed').style.display = 'block';
        return false;
    }
}

function validateMobileNumber(mobileNumber, errorElement) {
    const regex = /^\+?\d{8,15}$/; // Regex to validate the mobile number format

    // Clear any existing error messages
    errorElement.textContent = '';

    // Required validation
    if (!mobileNumber) {
        errorElement.textContent = 'Mobile number is required.';
        return false;
    }

    // Numeric validation using regex (also checks length 8-15)
    if (!regex.test(mobileNumber)) {
        errorElement.textContent = 'Enter a valid mobile number (8-15 digits, optional leading +).';
        return false;
    }

    // If passes all validations
    errorElement.textContent = '';
    return true;
}

function updateRegistrationMessage(element, message, type) {
    element.textContent = message;
    element.className = ''; // Clear previous classes
    element.classList.add(type === 'success' ? 'registration-success' : 'registration-error');
}

document.addEventListener('DOMContentLoaded', function () {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const form = document.getElementById('registrationForm');
    let isUsernameAvailable = false;
    let isMobileNumberAvailable = false;
    let isEmailAvailable = false;
    let isPasswordAvailable = false;
    let isFullNameAvailable = false;
    const mobileNumberInput = document.querySelector('input[name="mobile_number"]');
    const mobileNumberErrorSpan = document.querySelector('.mobile_number-error');
    const fullNameInput = document.querySelector('input[name="full_name"]');
    const fullNameErrorSpan = document.querySelector('.full_name-error');
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmationPasswordInput = document.querySelector('input[name="password_confirmation"]');
    const passwordErrorSpan = document.querySelector('.password-error');

    usernameInput.addEventListener('blur', async function () {
        const username = usernameInput.value.trim();
        isUsernameAvailable = await checkUsernameAvailability(username);
    });

    mobileNumberInput.addEventListener('input', function () {
        isMobileNumberAvailable = validateMobileNumber(mobileNumberInput.value, mobileNumberErrorSpan);
    });

    fullNameInput.addEventListener('blur', function () {
        isFullNameAvailable = validateFullName(fullNameInput.value, fullNameErrorSpan);
    });

    passwordInput.addEventListener('input', function () {
        isPasswordAvailable = validatePassword(passwordInput.value, confirmationPasswordInput.value, passwordErrorSpan);
    });

    confirmationPasswordInput.addEventListener('input', function () {
        isPasswordAvailable = validateConfirmationPassword(passwordInput.value, confirmationPasswordInput.value, passwordErrorSpan);
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault(); // Prevent the form from submitting traditionally

        if (!isUsernameAvailable) {
            const messageElement = document.getElementById('username-message');
            messageElement.textContent = "Username is not available, please choose another one.";
            messageElement.classList.remove('available');
            messageElement.classList.add('error');
            return;
        }

        if (!isMobileNumberAvailable) {
            return;
        }

        if (!isPasswordAvailable)
            return;

        const email = emailInput.value.trim();
        isEmailAvailable = await checkEmailAvailability(email);

        if (!isEmailAvailable) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const headers = new Headers({
            'X-CSRF-TOKEN': csrfToken
        });

        let data;

        const formData = new FormData(form);

        try {
            const response = await fetch('/register', {
                method: 'POST',
                headers: headers,
                body: formData
            });

            const data = await response.json();
            const registrationStatus = document.getElementById('registration-status');

            if (!response.ok) {
                // Handle non-ok responses by throwing an error.
                throw new Error(JSON.stringify(data.errors || 'Server validation failed'));
            }

            if (data && data.status === 'success') {
                resetForm(form)
                updateRegistrationMessage(registrationStatus, decodeURIComponent(data.message), 'success');
            } else {
                console.log(data.message);
                updateRegistrationMessage(registrationStatus, "Registration Failed!", 'error');
            }
        } catch (error) {
            if (error.message) {
                console.log(error.message);
            }

            try {
                const errors = JSON.parse(error.message);
                if (errors) {
                    Object.keys(errors).forEach(function (key) {
                        const errorElement = document.getElementById(key + '-error');
                        if (errorElement) {
                            errorElement.textContent = errors[key].join(', ');
                        }
                    });
                }
            } catch (parseError) {
                // If parsing fails, display a generic error message
                console.error('Error parsing error message:', parseError);
            }
        }
        return;
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

    showPopup.onclick = async () => {
        // popupContainer.classList.add('active');
        const birthdate = birthdateInput.value;

        if (birthdate) {
            let [year, month, day] = birthdate.split('-');
            let birthdateWithoutYear = `${month}-${day}`;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/get-born-today/' + encodeURIComponent(birthdateWithoutYear), {
                    method: 'GET',
                    headers: new Headers({
                        'X-CSRF-TOKEN': csrfToken
                    }),
                });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(JSON.stringify(data.errors || 'Network response was not ok.'));
                }
                const imagesContainer = document.getElementById('imagesContainer');
                imagesContainer.innerHTML = '';

                data.forEach(actor => {
                    let img = document.createElement('img');
                    img.src = actor.imageUrl;
                    img.alt = actor.name;
                    img.style.width = '100px';
                    img.style.margin = '10px';
                    let name = document.createElement('p');
                    name.textContent = actor.name;
                    name.style.color = 'black';

                    imagesContainer.appendChild(img);
                    imagesContainer.appendChild(name);
                });

                popupContainer.classList.add('active'); // Show the popup

            } catch (error) {
                if (error.message) {
                    console.log(error.message);
                }

                try {
                    const errors = JSON.parse(error.message);
                    if (errors) {
                        Object.keys(errors).forEach(function (key) {
                            const errorElement = document.getElementById(key + '-error');
                            if (errorElement) {
                                errorElement.textContent = errors[key].join(', ');
                            }
                        });
                    }
                } catch (parseError) {
                    console.error('Error parsing error message:', parseError);
                }
            }
        } else {
            console.error('Birthdate is required');
        }
    };
    closeBtn.onclick = () => {
        popupContainer.classList.remove('active');
    }
});
