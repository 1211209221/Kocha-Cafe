const isValidEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
};

const isValidPhone = (phone) => {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
    return re.test(String(phone).toLowerCase());
};

const form = document.getElementById("Contactform");

form.addEventListener('submit', e => {
    e.preventDefault(); // Prevent default form submission behavior
    if (!validateForm(form)) {
        return false; // Prevent form submission if validation fails
    } else {
        alert('Form submitted successfully.'); // Just for testing purposes, replace this with your desired form submission logic
    }
});

const validateForm = (form) => {
    let valid = true;
    let name = form.querySelector(".name");
    let phno = form.querySelector(".phno");
    let email = form.querySelector(".email");
    let subject = form.querySelector(".subject");
    let message = form.querySelector(".message");

    if (name.value.trim() === "") {
        errorDisplay(name, "Please enter your name.");
        valid = false;
    } else {
        removeError(name);
    }

    if (phno.value.trim() === "") {
        errorDisplay(phno, "Phone number is required.");
        valid = false;
    } else if (!isValidPhone(phno.value.trim())) {
        errorDisplay(phno, "Please enter a valid phone number.");
        valid = false;
    } else {
        removeError(phno);
    }

    if (email.value.trim() === "") {
        errorDisplay(email, "Email address is required.");
        valid = false;
    } else if (!isValidEmail(email.value.trim())) {
        errorDisplay(email, "Please enter a valid email address.");
        valid = false;
    } else {
        removeError(email);
    }

    if (subject.value.trim() === "") {
        errorDisplay(subject, "Subject is required.");
        valid = false;
    } else {
        removeError(subject);
    }

    if (message.value.trim() === "") {
        errorDisplay(message, "Message is required.");
        valid = false;
    } else {
        removeError(message);
    }

    return valid;
};

const errorDisplay = (field, message) => {
    let parentElement = field.parentElement;
    parentElement.classList.add("error");
    let existingError = parentElement.querySelector(".error-input");
    if (existingError) {
        existingError.textContent = message;
    } else {
        let error = document.createElement("small");
        error.textContent = message;
        error.classList.add("error-input");
        parentElement.appendChild(error);
    }
};

const removeError = (field) => {
    let parentElement = field.parentElement;
    parentElement.classList.remove("error");
    let existingError = parentElement.querySelector(".error-input");
    if (existingError) {
        existingError.remove();
    }
};