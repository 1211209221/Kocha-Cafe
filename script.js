// function categoryToggle(x) {
//     var parentLi = x.parentElement;
//     var ul = parentLi.querySelector('ul');
//     if (ul) {
//         ul.classList.toggle("categoryToggle");
//     }
//     x.classList.toggle("fa-angle-up");
// }

function categoryToggle(x) {
    var parentLi = x.parentElement;
    var ul = parentLi.querySelector('ul');
    if (ul) {
        ul.classList.toggle("categoryToggle");
        if (ul.classList.contains("categoryToggle")) {
            ul.style.maxHeight = "0"; // Set max-height to 0 initially
            ul.style.maxHeight = ul.scrollHeight + "px"; // Then expand to content height
            // Add event listener for transitionend to remove height after animation
            ul.addEventListener("transitionend", function() {
                ul.style.maxHeight = null;
            });
            x.classList.remove("fa-angle-down"); // Remove the down arrow class
            x.classList.add("fa-angle-up"); // Add the up arrow class
        }
        else{
            ul.style.maxHeight = "0"; // Collapse the list
            x.classList.remove("fa-angle-up"); // Remove the up arrow class
            x.classList.add("fa-angle-down"); // Add the down arrow class
        }
    }
}

 window.onload = function() {
    document.querySelector('.clear_filters').addEventListener('click', function() {
        var filterTags = document.querySelectorAll('.filter_tag');
        filterTags.forEach(function(tag) {
            tag.remove();
        });
    });
};


// Function to check if an element is in viewport
function isInViewport(element) {
    var bounding = element.getBoundingClientRect();
    return (
        bounding.top >= 0 &&
        bounding.left >= 0 &&
        bounding.top <= (window.innerHeight || document.documentElement.clientHeight) &&
        bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Function to add 'visible' class to elements in viewport
function addVisibleClass() {
    var elements = document.querySelectorAll('.fade_in');
    elements.forEach(function(element) {
        if (isInViewport(element)) {
            element.classList.add('visible');
        }
    });
}

// Add 'visible' class to elements when page loads and on scroll
document.addEventListener('DOMContentLoaded', function() {
    addVisibleClass();
});

window.addEventListener('scroll', function() {
    addVisibleClass();
});