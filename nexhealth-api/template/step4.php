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
                <div><input class="form-control" type="text" id="patient-fname" name="fname" placeholder="Patient first name*" required></div>
                
                <div><input class="form-control" type="text" id="patient-lname" name="lname" placeholder="Patient last name*" required></div>
                
                <div><input class="form-control" type="text" id="patient-dob" name="dob" placeholder="Patient data of birth*" required></div>
                
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
      
      <?php if($body_cont['editing'] === false){ ?>
          <input type="button" id="bk-btn" value="Back">
          <input type="submit" id="btn-ctn" value="Continue">
      <?php } else { ?>
        <input type="submit" id="btn-ctn" value="Save">
      <?php } ?>
    
</div>
<div class="buttons-row-mobile">  
      <?php if($body_cont['editing'] === false){ ?>
          <input type="button" id="bk-btn-mb" value="Back">
          <input type="submit" id="btn-ctn-mb" value="Continue">
      <?php } else { ?>
        <input type="submit" id="btn-ctn-mb" value="Save">
      <?php } ?>
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
    function clearErrors(){
        $('.is-invalid').removeClass('is-invalid');
        $('.error-text').remove();
        
    }
    function showError(element, error_text){
        
        error_element = '<span class="error-text">'+error_text+'</span>';
        element.parent().append(error_element);  
        
    }
    $(document).ready(function(){
        $('#bk-btn, #bk-btn-mb').click(function(e){
            e.preventDefault();
            window.location.href = 'index.php?step=3';
        });
        $('input[name="appointment"]').change(function(){
            toggleOtherPatBlock();
        });
        $('#btn-ctn, #btn-ctn-mb').click(function(){
            clearErrors();
            var insurance = $('input[name="insurance"]:checked').val();
            var appointment = $('input[name="appointment"]:checked').val();
            var fname = $('#patient-fname').val();
            var lname = $('#patient-lname').val();
            var dob = $('#patient-dob').val();
            var after_edit = <?php if($body_cont['editing']){ echo 'true'; } else { echo 'false'; } ?>;
            var parentGuardian = $('input[name="parent-guardian"]:checked').val();
            var error = false;
            if(insurance == undefined){
                $('input[name="insurance"]').addClass('is-invalid');
                showError($('input[name="insurance"]'), 'Please select if you have dental insurance');
                error = true;
            }
            if(appointment == undefined){
                $('input[name="appointment"]').addClass('is-invalid');
                showError($('input[name="appointment"]'), 'Please select who you are scheduling this appointment for');
                error = true;
            }
            if(appointment == 'someoneElse'){
                if(fname == ''){
                    $('#patient-fname').addClass('is-invalid');
                    showError($('#patient-fname'), 'Please enter patient first name');
                    error = true;
                }
                if(lname == ''){
                    $('#patient-lname').addClass('is-invalid');
                    showError($('#patient-lname'), 'Please enter patient last name');
                    error = true;
                }
                if(dob == ''){
                    $('#patient-dob').addClass('is-invalid');
                    showError($('#patient-dob'), 'Please enter patient date of birth');
                    error = true;
                }
                if(parentGuardian == undefined || parentGuardian == ''){
                    $('input[name="parent-guardian"]').addClass('is-invalid');
                    showError($('input[name="parent-guardian"]'), 'Please select if you are the parent or legal guardian of the patient');
                    error = true;
                }
            }
            if(error){
                return false;
            }
            var data = {
                insurance: insurance,
                appointment: appointment,
                fname: fname,
                lname: lname,
                dob: dob,
                step: 4,
                parentGuardian: parentGuardian,
                after_edit: after_edit
            };
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                success: function(response){
                    if(response.status == 'success'){
                        window.location.href = 'index.php?step=5';
                    } else if (response.redirect) {
                        window.location.href = response.redirect;
                    } else{
                        alert(response.message);
                    }
                }
            });
        });
    });
</script>
