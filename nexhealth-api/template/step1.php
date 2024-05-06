<?php
require_once dirname(__DIR__) . '/classes/Patient.php';
?>
<h2 class="form-title">PLEASE ENTER YOUR INFORMATION</h2>
<form class="step-one" action="index.php" method="post">
    <div class="form-row row">
    <div class="form-group col-md-6">
    <input class="form-control" type="text" id="fname" name="fname" placeholder="First Name*" required>
    </div>
    <div class="form-group col-md-6">
    <input  class="form-control" type="text" id="lname" name="lname" placeholder="Last Name*" required>
    </div>
    </div>
    <div class="form-row row">
    <div class="form-group col-md-6">
    <input class="form-control" type="email" id="email" name="email" placeholder="Email*" required>
    </div>
    <div class="form-group col-md-6">
    <input class="form-control" type="tel" id="phone" name="phone" placeholder="Phone Number*" required>
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
    </div>
    <div class="buttons-row">  
    <input type="button" id="bk-btn" value="Back">
    <input type="submit" id="btn-ctn" value="Continue">

    </div>
    
</form>
<div class="container">
  <div class="row need-help">
    <h3>Need help? Our friendly staff are here to help. Call <a href="tel:(516)565-6565">(516)565-6565</a></h3>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#bk-btn').click(function(e){
            e.preventDefault();
            window.location.href = 'index.php';
        });
        $('#btn-ctn').click(function(e){
            e.preventDefault();
            var fname = $('#fname').val();
            var lname = $('#lname').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var step = 1;
            var existed_patient = $('input[name="existed_patient"]:checked').val();
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    fname: fname,
                    lname: lname,
                    email: email,
                    phone: phone,
                    step: step,
                    existed_patient: existed_patient
                },
                success: function(response){
                    if (response.status == 'success') {
                        
                            window.location.href = 'index.php?step=2';
                        
                    }
                    console.log(response);
                }
            });
        });
    });
    
</script>