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






















