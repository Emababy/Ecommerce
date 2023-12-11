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

$(document).ready(function() {
    $('.live-name').on('input', function() {
        $('.live-review .caption h3').text($(this).val());
    });

    $('.live-desc').on('input', function() {
        $('.live-review .caption p').text($(this).val());
    });

    $('.live-price').on('input', function() {
        $('.live-review .price-tag').text('$' + $(this).val());
    });
    
});






















