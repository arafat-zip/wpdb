jQuery(document).ready(function($){
    $('#submit').on('click', function(e){
        e.preventDefault();
        if($('#name').val() === '' || $('#phone').val() === '' || $('#email').val() === ''){
            alert('Please fill in all fields');
            return;
        }
        $.ajax({
            url: dbDelta.ajax_url,

            method: 'POST',
            data: {
                action: 'db_delta_ajax_form',
                _ajax_nonce: dbDelta.ajax_nonce,
                name: $('#name').val(),
                phone: $('#phone').val(),
                email: $('#email').val()
            },
            success: function(response){
                console.log(response);
            },
            error: function(error){
                console.log(error);
            }
            
        });
    });
});