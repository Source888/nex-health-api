<h2 class="form-title">ADDITIONAL INFORMATION</h2>
<div class="container">
    <form>
        <div class="form-group">
            <h4>Do you have dental insurance?</h4>
            <div class="insurance-block">
                <input type="radio" id="insuranceYes" name="insurance" value="yes">
                <label for="insuranceYes">Yes</label>
                <input type="radio" id="insuranceNo" name="insurance" value="no">
                <label for="insuranceNo">No</label>
            </div>
        </div>
        <div class="form-group">
            <h4>Are you scheduling this appointment for you, or someone else?</h4>
            <div class="whois-app">
                <input type="radio" id="appointmentMe" name="appointment" value="me" <?php if(is_null($body_cont['for_who_app']) || $body_cont['for_who_app'] == 'me' ){ echo 'checked';} ?>>
                <label for="appointmentMe">Me</label>
                <input type="radio" id="appointmentSomeoneElse" name="appointment" value="someoneElse" <?php if($body_cont['for_who_app'] == 'someoneElse' ){ echo 'checked';} ?>>
                <label for="appointmentSomeoneElse">Someone else</label>
            </div>
        </div>
        <div class="form-group other-pat" <?php if(is_null($body_cont['for_who_app']) || $body_cont['for_who_app'] == 'me' ){ echo 'style="display:none;"';} ?>>
            <div class="text-inputs">
                <input class="form-control" type="text" id="patient-fname" name="fname" placeholder="Patient first name*" required>
                
                <input class="form-control" type="text" id="patient-lname" name="lname" placeholder="Patient last name*" required>
                
                <input class="form-control" type="text" id="patient-dob" name="dob" placeholder="Patient data of birth*" required>  
            </div> 
            <div class="form-group parent-guardian">
                
                <h4>Are you the parent or legal guardian of the patient?</h4>
                <div class="parent-guardian-inputs">
                    <input type="radio" id="parent" name="parent-guardian" value="yes">
                    <label for="parent">Yes</label>
                    <input type="radio" id="guardian" name="parent-guardian" value="no">
                    <label for="guardian">No</label>      
                </div>  
            </div>
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
    function toggleOtherPatBlock(){
        var forWho = $('input[name="appointment"]:checked').val();
        if(forWho == 'someoneElse'){
            $('.other-pat').fadeIn();
        }else{
            $('.other-pat').fadeOut();
        }
        
    }
    $(document).ready(function(){
        
        $('input[name="appointment"]').change(function(){
            toggleOtherPatBlock();
        });
    });
</script>