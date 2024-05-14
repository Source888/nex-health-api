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
    
    <div class="radio-block row">
      <div class="row">
        <div class="col-md-6">
       
          <h4>New or Existing Patient?</h4>
        </div>
      </div>
      <div class="form-row row">
        <div class="form-group radio-block-patient col-md-6">
        
          <div class="form-check">
            
            
            <input class="form-check-input" type="radio" name="existed_patient" id="new_patient" value="false" <?php if(!$body_cont['existed_patient']) { echo 'checked';} ?>>
            <label class="radio" for="new_patient">
            New patient
            </label>
          </div>
          <div class="form-check">
          
         
            <input class="form-check-input" type="radio" name="existed_patient" id="existed_patient" value="true" <?php if($body_cont['existed_patient']) { echo 'checked';} ?>>
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
        $('.is-invalid').removeClass('is-invalid');
        $('.error-text').remove();
        
    }
    function showError(element, error_text){
        
        error_element = '<span class="error-text">'+error_text+'</span>';
        element.parent().append(error_element);  
        
    }
    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
    function validatePhone(phone) {
        var re = /^\d{10}$/;
        return re.test(phone);
    }
    function validateDOB(dob) {
      var dobDate = new Date(dob);
      var currentDate = new Date();

      
      dobDate.setHours(0, 0, 0, 0);
      currentDate.setHours(0, 0, 0, 0);

      return dobDate <= currentDate;
    }
  function toggleDOBBlock(){
        var ex_p = $('input[name="existed_patient"]:checked').val();
        if(ex_p == 'false'){
            $('.dob-field').fadeIn();
            $('#dob').attr('required', 'required');
        }else{
            $('.dob-field').fadeOut();
            $('#dob').val('');
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
              showError($('#fname'), 'First Name is required!');
              have_error = true;
            }
            if(lname == '' ) {
              showError($('#lname'), 'Last Name is required!');
              $('#lname').addClass('is-invalid');
              have_error = true;
            }
            if(email == '') {
              showError($('#email'), 'Email is required!');
              $('#email').addClass('is-invalid');
              have_error = true;
            }
            if(!validateEmail(email)){
              showError($('#email'),'Please enter a valid email address');
              $('#email').addClass('is-invalid');
              have_error = true;
            }
            if(phone == '' ) {
              showError($('#phone'),'Phone Number is required!');
              $('#phone').addClass('is-invalid');
              have_error = true;
            }
            if(!validatePhone(phone)){
              showError($('#phone'), 'Please enter a valid phone number');
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
            if(existed_patient == 'false' && dob == ''){
                showError($('#dob'), 'Date of Birth is required!');
                $('#dob').addClass('is-invalid');
                have_error = true;
            }
            if(!validateDOB(dob) && dob != ''){
              showError($('#dob'), 'Please enter a valid date of birth');
              $('#dob').addClass('is-invalid');
              have_error = true;
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
            } 
        });
    });
    
</script>