document.addEventListener('DOMContentLoaded', function() {

    let inputFields = document.querySelectorAll("input");

    inputFields.forEach(function (input){
        input.addEventListener("focus" , () => {
            input.setAttribute("data-toggle", input.getAttribute("placeholder"));
            input.setAttribute("placeholder","");
        });

        input.addEventListener("blur" , () => {
            input.setAttribute("placeholder",input.getAttribute("data-toggle"));
        });
    });

    const passwordInput = document.querySelector('input[name="password"]');
    const passwordToggle = document.getElementById('password-toggle');
    
    passwordToggle.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        if (type === 'password') {
            passwordToggle.classList.remove('fa-eye-slash');
            passwordToggle.classList.add('fa-eye');
        } else {
            passwordToggle.classList.remove('fa-eye');
            passwordToggle.classList.add('fa-eye-slash');
        }
    });
    
});

function confirmDelete(event) {
    event.preventDefault(); // Prevents the default behavior of the link
    
    swal({
        title: 'Delete',
        text: 'Are You Sure',
        icon: 'error',
        buttons: ['Cancel', 'Delete'],
        dangerMode: true,
    })
}

// Get all elements with the class 'categoryName'
var categoryNameElements = document.querySelectorAll('.categoryName');

// Add click event listener to each element
categoryNameElements.forEach(function (element) {

    element.addEventListener('click', function () {
    // Get the value of the 'data-index' attribute
    var dataIndex = element.getAttribute('data-index');

    // Get the corresponding toggle-details div
    var toggleDetailsDiv = document.querySelector('.toggle-details-' + dataIndex);

    // Toggle the visibility of the toggle-details div
    toggleDetailsDiv.style.display = (toggleDetailsDiv.style.display === 'block') ? 'none' : 'block';
    });
});






















