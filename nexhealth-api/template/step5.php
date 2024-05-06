<?php
require_once dirname(__DIR__) . '/classes/Patient.php';
require_once dirname(__DIR__) . '/classes/Provider.php'; 
?>
<h2 class="form-title">REVIEW & CONFIRM APPOINTMENT</h2>
<h4>Click 'Confirm My Appointment' to complete your scheduling</h4>
<div class="container">
    <div class="appointment-info">
        <div class="patient-info">
            <div class="title-edit-block">
            <h3>Patient Information</h3>
            <a href="index.php?step=1">Edit</a>
            </div>
            <div class="patient-info-block">
                <p>Name: <?=($body_cont['patient']->first_name)?> <?=($body_cont['patient']->last_name)?></p>
                <p>Email: <?=($body_cont['patient']->email)?></p>
                <p>Phone: <?=($body_cont['patient']->phone)?></p>
            </div>
        </div>
        <div class="additional-info">
            <div class="title-edit-block">
            <h3>Additional Information</h3>
            <a href="index.php?step=4">Edit</a>
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
                <a href="index.php?step=2">Edit</a>
            </div>
            <div class="date-time-info-block">
                <?php 
                    $date = new DateTime('2024-05-07T09:30:00.000-07:00');
                    $date_to_wiev = $date->format('l, F Y - g:i A');
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
                <a href="index.php?step=3">Edit</a>
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
<div class="container">
  <div class="row need-help">
    <h3>Need help? Our friendly staff are here to help. Call <a href="tel:(516)565-6565">(516)565-6565</a></h3>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#app-conf').click(function(e){
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
                    console.log(response);
                }
            });
        });
        $('#comment').on('input', function() {
            var symbols = $(this).val().length;
            $('.symbols-left').text(symbols + '/2000');
        });
    });
</script>
