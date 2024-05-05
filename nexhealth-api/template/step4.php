<h2 class="form-title">ADDITIONAL INFORMATION</h2>
<div class="container">
    <form>
        <div class="form-group">
            <label>Do you have dental insurance?</label><br>
            <input type="radio" id="insuranceYes" name="insurance" value="yes">
            <label for="insuranceYes">Yes</label><br>
            <input type="radio" id="insuranceNo" name="insurance" value="no">
            <label for="insuranceNo">No</label>
        </div>
        <div class="form-group">
            <label>Are you scheduling this appointment for you, or someone else?</label><br>
            <input type="radio" id="appointmentMe" name="appointment" value="me">
            <label for="appointmentMe">Me</label><br>
            <input type="radio" id="appointmentSomeoneElse" name="appointment" value="someoneElse">
            <label for="appointmentSomeoneElse">Someone else</label>
        </div>
    </form>
</div>
<div class="buttons-row">  
    <input type="button" id="bk-btn" value="Back">
    <input type="submit" id="btn-ctn" value="Continue">
</div>
<div class="container">
  <div class="row need-help">
    <h3>Need help? Our friendly staff are here to help. Call <a href="tel:(516)565-6565">(516)565-6565</a></h3>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#bk-btn').click(function(e){
            e.preventDefault();
            window.location.href = 'index.php?step=3';
        });
    });
    $('#btn-ctn').click(function(e){
        e.preventDefault();
        var insurance = $('input[name="insurance"]:checked').val();
        var appointment = $('input[name="appointment"]:checked').val();
        var step = 4;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                insurance: insurance,
                appointment: appointment,
                step: step
            },
            success: function(response){
                if (response.status == 'success') {
                    window.location.href = 'index.php?step=5';
                }
                console.log(response);
            }
        });
    });
</script>