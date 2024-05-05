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
</script>