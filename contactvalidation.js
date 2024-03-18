const isValidEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
    };
    
    const isValidPhone = (phone) => {
    const re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
    return re.test(String(phone).toLowerCase());
    };
    
    
    const Contactform = document.querySelector("form");
    
    
    Contactform.addEventListener('submit', e => {
        e.preventDefault();
        if(!validateform(form)) return;
    });
    
    const validateform = (form) => {
        let valid = true;
        let name = form.querySelector(".name");
        let phno = form.querySelector(".phno");
        let email = form.querySelector(".email");
        let subject = form.querySelector(".subject");
        let message = form.querySelector(".message");
    
        if(name.value === ""){
            errorDisplay(name, "Please enter your name.");
        }
    
    };
    
    const errorDisplay = (field, message) => {
        let parentElement = field.parentElement;
        parentElement.classList.add("error");
        let existingError = parentElement.querySelector(".error-input");
        if(existingError){
            existingError.remove();
        }
        let error = document.createElement("small");
        error.textContent = message;
        error.classList.add("error-input");
        parentElement.appendChild(error);
    } 