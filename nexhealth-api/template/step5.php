<?php
require_once dirname(__DIR__) . '/classes/Patient.php';
require_once dirname(__DIR__) . '/classes/Provider.php'; 
//var_dump($_SESSION['patient']);
?>
<style>
        .step-topbar {
            display: none !important;
        }
</style>
<h2 class="form-title">REVIEW & CONFIRM APPOINTMENT</h2>
<h4 class="hint">Click 'Confirm My Appointment' to complete your scheduling</h4>
<div class="container">
    <div class="appointment-info">
        <div class="patient-info">
            <div class="title-edit-block">
            <h3>Patient Information</h3>
            <a href="javascript:void(0)" class="edit-step" data-step="1">Edit</a>
            </div>
            <div class="patient-info-block">
                <p><?=($body_cont['patient']->first_name)?> <?=($body_cont['patient']->last_name)?></p>
                <p><?=($body_cont['patient']->date_of_birth)?></p>
                
            </div>
        </div>
        <div class="additional-info">
            <div class="title-edit-block">
                <?php if($body_cont['existing_patient']){ ?>
                    <h3>Your Information</h3>
                <?php } else { ?>
                    
                    <h3>Additional Information</h3>
                <?php } ?>
            <a href="javascript:void(0)" class="edit-step" data-step="4">Edit</a>
            </div>
            <div class="additional-info-block">
                <p><?=($body_cont['patient']->email)?></p>
                <p><?=($body_cont['patient']->phone_number)?></p>
                <?php if($body_cont['insurance'] == 'yes'){ ?>
                <p>I have insurance</p>
                <?php } else { ?>
                <p>I don't have insurance</p>
                <?php } ?>
            </div>
        </div>
        <div class="date-time-info">
            <div class="title-edit-block">
                <?php if($body_cont['existing_patient']){ ?>

                    <h3>Date, Time & Provider</h3>
                <?php } else { ?>
                    <h3>Date & Time</h3>
                <?php } ?>
                <a href="javascript:void(0)" class="edit-step" data-step="3">Edit</a>
            </div>
            <div class="date-time-info-block">
                <?php 
                    $date = new DateTime($body_cont['full_time']);
                    $date_to_wiev = $date->format('l, F d - g:i A');
                ?>
                <p><?=($date_to_wiev)?></p>
                <?php if($body_cont['existing_patient']){ ?>

                <p>Provider: <?=($body_cont['provider']->first_name)?> <?=($body_cont['provider']->last_name)?></p>
               
                <?php } ?>
            </div>
        </div>
        <div class="reason-info">
            <div class="title-edit-block">
                <h3>Reason for Visit</h3>
                <a href="javascript:void(0)" class="edit-step" data-step="2">Edit</a>
            </div>
            <div class="reason-info-block">
                <p><?=($body_cont['appointment_type'])?></p>
            </div>
        </div>
    </div>
    <div class="appointment-comment">
        <label for="comment">Comments or special requests * optional</label>
        <textarea class="form-control" name="comment" id="comment" rows="5" style="min-width: 100%"></textarea>
        <div class="symbols-left">0/2000</div>
    </div>
</div>
<div class="buttons-row">  
    <input type="submit" id="app-conf" value="Confirm My Appointment">
    
</div>
<div class="buttons-row-mobile">  
    <input type="submit" id="app-conf-mb" value="Confirm My Appointment">
    
</div>
<div class="container">
  <div class="row need-help">
    <h3>Need help? Our friendly staff are here to help. Call <a href="tel:(516)565-6565">(516)565-6565</a></h3>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.edit-step').click(function(){
            var step_to_edit = $(this).data('step');
            var step = 'edit_step';
            var data = {
                step: step,
                step_to_edit : step_to_edit
            };
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                success: function(response){
                    if(response && typeof response.redirect == 'string'){
                        window.location.href = response.redirect;
                    }
                }
            });
        });
        $('#app-conf, #app-conf-mb').click(function(e){
            e.preventDefault();
            var comment = $('#comment').val();
            var step = 'confirm_appointment';
           
            var data = {
                step: step,
                comment: comment,
               
            };
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: data,
                success: function(response){
                    if(typeof response.data == 'string'){
                        alert('Error:' + response.data);
                    } else {
                        window.location.href = 'index.php?step=6';
                    }
                }
            });
        });
        $('#comment').on('input', function() {
            var symbols = $(this).val().length;
            $('.symbols-left').text(symbols + '/2000');
        });
    });
</script>
