function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
    
function isValidPhone(phone) {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
    return re.test(String(phone).toLowerCase());
}
    
    
const form = document.getElementById("Contactform");

form.addEventListener('submit', e => {
    e.preventDefault();
    if(!validateForm(form)) return;
});

function validateForm(form) {
    let valid = true;
    let name = form.querySelector(".name");
    let phno = form.querySelector(".phno");
    let email = form.querySelector(".email");
    let subject = form.querySelector(".subject");
    let message = form.querySelector(".message");

    // Reset error messages
    resetErrors(form);

    // Validate name
    if (name.value.trim() === "") {
        errorDisplay(name, "Please enter your name.");
        valid = false;
    }

    // Validate phone number
    if (phno.value.trim() === "") {
        errorDisplay(phno, "Please enter your phone number.");
        valid = false;
    } else if (!isValidPhone(phno.value.trim())) {
        errorDisplay(phno, "Please enter a valid phone number.");
        valid = false;
    }

    // Validate email
    if (email.value.trim() === "") {
        errorDisplay(email, "Please enter your email address.");
        valid = false;
    } else if (!isValidEmail(email.value.trim())) {
        errorDisplay(email, "Please enter a valid email address.");
        valid = false;
    }

    // Validate subject
    if (subject.value.trim() === "") {
        errorDisplay(subject, "Please enter a subject.");
        valid = false;
    }

    // Validate message
    if (message.value.trim() === "") {
        errorDisplay(message, "The message is required.");
        valid = false;
    }

    return valid;
}

function errorDisplay(input, message) {
    const errorElement = input.nextElementSibling;
    errorElement.innerText = message;
    input.classList.add('error');
}

function resetErrors(form) {
    const errorElements = form.querySelectorAll(".error-input");
    errorElements.forEach(element => {
        element.innerText = "";
    });
    // Remove error class from input elements
    const inputs = form.querySelectorAll("input, textarea");
    inputs.forEach(input => {
        input.classList.remove('error');
        input.classList.remove('error-input');
    });
}
