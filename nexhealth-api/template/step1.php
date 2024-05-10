<?php
require_once dirname(__DIR__) . '/classes/Patient.php';
?>
<h2 class="form-title">PLEASE ENTER YOUR INFORMATION</h2>
<form class="step-one" action="index.php" method="post">
    <div class="form-row row">
      <div class="form-group col-md-6 input-field">
      
        <input class="form-control" type="text" id="fname" name="fname" placeholder="First Name*" required <?php if(!is_null($body_cont['patient'])){ echo "value='".$body_cont['patient']->first_name."'"; } ?>>
        <label class="mobile-label" for="fname">First Name*</label> 
      </div>
      <div class="form-group col-md-6 input-field">
      
        <input  class="form-control" type="text" id="lname" name="lname" placeholder="Last Name*" required <?php if(!is_null($body_cont['patient'])){ echo "value='".$body_cont['patient']->last_name."'"; } ?>>
        <label class="mobile-label" for="lname">Last Name*</label>  
      </div>
    </div>
    <div class="form-row row">
      <div class="form-group col-md-6 input-field">
        
        <input class="form-control" type="email" id="email" name="email" placeholder="Email*" required <?php if(!is_null($body_cont['patient'])){ echo "value='".$body_cont['patient']->email."'"; } ?>>
        <label class="mobile-label" for="email">Email*</label>
      </div>
      <div class="form-group col-md-6 input-field">
     
        <input class="form-control" type="tel" id="phone" name="phone" placeholder="Phone Number*" required <?php if(!is_null($body_cont['patient'])){ echo "value='".$body_cont['patient']->phone_number."'"; } ?>>
        <label class="mobile-label" for="phone">Phone Number*</label>  
      </div>
    </div>
    
    <div class="radio-block">
      <div class="row">
        <div class="col-md-6">
       
          <h4>New or Existing Patient?</h4>
        </div>
      </div>
      <div class="form-row row">
        <div class="form-group radio-block-patient col-md-6">
        
          <div class="form-check">
            
            
            <input class="form-check-input" type="radio" name="existed_patient" id="new_patient" value="false">
            <label class="radio" for="new_patient">
            New patient
            </label>
          </div>
          <div class="form-check">
          
         
            <input class="form-check-input" type="radio" name="existed_patient" id="existed_patient" value="true">
            <label class="radio" for="existed_patient">
            Existing patient
            </label>
          </div>
        </div>
      </div>
      <div class="form-group col-md-6 dob-field">
        <label for="dob">DOB</label>
        <input class="form-control" type="text" id="dob" name="dob" placeholder="Select a date*"  <?php if(!is_null($body_cont['patient'])){ echo "value='".$body_cont['patient']->date_of_birth."'"; } ?>>
      </div>
  
    </div>
    
    <div class="form-row row">
      <div class = "error-message">
        <span class="error">Please fill out all required fields</span>
      </div>
      <div class="buttons-row">  
        <?php if($body_cont['editing'] === false){ ?>
            <input type="button" id="bk-btn" value="Back">
            <input type="submit" id="btn-ctn" value="Continue">
        <?php } else { ?>
          <input type="submit" id="btn-ctn" value="Save">
        <?php } ?>
      </div>
    </div>
</form>
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
  function clearErrors(){
        $('#fname').removeClass('is-invalid');
        $('#lname').removeClass('is-invalid');
        $('#email').removeClass('is-invalid');
        $('#phone').removeClass('is-invalid');
        $('.error-message').fadeOut();
    }
  function toggleDOBBlock(){
        var ex_p = $('input[name="existed_patient"]:checked').val();
        if(ex_p == 'false'){
            $('.dob-field').fadeIn();
            $('#dob').attr('required', 'required');
        }else{
            $('.dob-field').fadeOut();
            $('#dob').removeAttr('required');
        }
        
    }
    var dateInput = document.getElementById('dob');

    dateInput.addEventListener('focus', function (e) {
        this.type = 'date';
    });

    dateInput.addEventListener('blur', function (e) {
        if (this.value == "") {
            this.type = 'text';
        }
    });
    $(document).ready(function(){
        $('#bk-btn, #bk-btn-mb').click(function(e){
            e.preventDefault();
            window.location.href = 'index.php';
        });
        $('input[name="existed_patient"]').change(function(){
            toggleDOBBlock();
        });
        toggleDOBBlock();
        $('#btn-ctn, #btn-ctn-mb').click(function(e){
            e.preventDefault();
            clearErrors();
            var fname = $('#fname').val();
            var lname = $('#lname').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            let have_error = false;
            var after_edit = <?php if($body_cont['editing']){ echo 'true'; } else { echo 'false'; } ?>;
            if(fname == '' ) {
              $('#fname').addClass('is-invalid');
              have_error = true;
            }
            if(lname == '' ) {
              $('#lname').addClass('is-invalid');
              have_error = true;
            }
            if(email == '' ) {
              $('#email').addClass('is-invalid');
              have_error = true;
            }
            if(phone == '' ) {
              $('#phone').addClass('is-invalid');
              have_error = true;
            }
            var step = 1;
            var existed_patient = $('input[name="existed_patient"]:checked').val();
            if(existed_patient == 'false' && $('.dob-field').is(":visible")){
                var dob = $('#dob').val();
            } else {
                var dob = '';
            }
            if(!have_error){
              $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    fname: fname,
                    lname: lname,
                    email: email,
                    phone: phone,
                    after_edit: after_edit,
                    step: step,
                    dob: dob,
                    existed_patient: existed_patient
                },
                success: function(response){
                    if (response.status == 'success') {
                        
                            window.location.href = 'index.php?step=2';
                        
                    } else if(response.status == 'error'){ 
                      $('.error-message .error').text(response.message);
                      $('.error-message').fadeIn();
                    }else if (response.redirect) {
                        window.location.href = response.redirect;
                    } else{
                        alert(response.message);
                    }
                    console.log(response);
                }
              });
            } else {
              $('.error-message').fadeIn();
              $('.error-message .error').text('Please fill out all required fields!');
            }
        });
    });
    
</script>