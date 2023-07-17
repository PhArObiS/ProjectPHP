$(document).ready(function(){
    
    anime({
        targets: 'h1',
        translateX: [-5, 5],
        easing: 'easeInOutQuad',
        direction: 'alternate',
        loop: true
    });

    $("#registration-form").submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = 'home.html';
                } else {
                    alert(response.message);
                }
            }
        });
    });
});