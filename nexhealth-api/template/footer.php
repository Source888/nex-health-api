<div id="loader-overlay" style="display:none;"><div class="loader"></div></div>

<script>
    $(document).ready(function() {
    // Show the loading image when an AJAX request starts
    $(document).ajaxStart(function() {
        $('#loader-overlay').show();
    });

    // Hide the loading image when an AJAX request stops
    $(document).ajaxStop(function() {
        $('#loader-overlay').hide();
    });
});
var timeoutId = 0;
window.onbeforeunload = function() {
    timeoutId = setTimeout(function() {
    var step = 'session_destroy';
    var data = {
                step: step
                
               
            };
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                success: function(response){
                    
                }
            });
        }, 2000);     
};
window.onload = function() {
    clearTimeout(timeoutId);
};
</script>