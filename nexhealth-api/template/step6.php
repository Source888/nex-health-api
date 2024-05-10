<div class = "main-banner">
    <style>
        body {
           background-color: transparent !important;
        }
    </style>
    <div class = "container">
        <div class = "row">
            <div class = "col-md-12">
                <h1 class="appt-info-ttl"><?=($body_cont['patient']->first_name)?>, YOU ARE ALL SET</h1>
            </div>
        </div>
        <div class="row">
            <div class = "col-md-12 greeting-message">
                <?php if($body_cont['existing_patient']){ ?>
                    <h2> WE ARE EXCITED TO SEE YOU SOON!</h2>
                <?php } else { ?>
                    <h2> WE ARE LOOKING FORWARD TO SEE YOU AGAIN!</h2>
                <?php } ?>
            </div>       
        </div>
        <div class="row">
            <div class = "col-md-12 short-instructions">
                <p>Your appointment request has been received.</p>
                <p>A confirmation email was sent.</p>
                <p class="ancor">*If you didn't receive the confirmation email, please check your spam or junk mail folder.</p>
            </div>
        </div>
        <div class="row sroft-appt-info">
            <div class = "col-md-6 address-block">
                <h4>Office location</h2>
                <p>639 Hempstead Turnpike, Franklin Square, NY 11010</p>
                <p>Phone: (516) 565-6565</p>
            </div>
            <div class = "col-md-6">
                 <?php if($body_cont['existing_patient']){ ?>

                    <h4>Date, Time & Provider</h3>
                <?php } else { ?>
                    <h4>Date & Time</h3>
                <?php } ?>
                <?php 
                    $date = new DateTime($body_cont['full_time']);
                    $date_to_wiev = $date->format('l, F d - g:i A');
                ?>
                <p><?=($date_to_wiev)?></p>
                <?php if($body_cont['existing_patient']){ ?>

                <p>Provider: <?=($body_cont['provider']->first_name)?> <?=($body_cont['provider']->last_name)?></p>
               
                <?php } ?>
            </div>
            <div class = "mobil-copy">
                <img src="/nexhealth-api/assets/img/copy.png" alt="copy-icon">
                <p>Copy to Clipboard</p>
            </div>
        </div>
        <div class="row">
            <div class = "col-md-9 buttons-after-app-form">
                <button class="btn btn-secondary" id="copy-to-cb">Copy to Clipboard</button>
                <button class="btn btn-primary" id="back-to-main-site">Back to Website</button>
            </div>
        </div>    
    </div>
</div>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
    new Clipboard('#copy-to-cb, .mobil-copy p', {
        text: function(trigger) {
            var text = $('.sroft-appt-info').text();
            text = text.replace(/\s\s+/g, ' ').trim();
            return text;
        }
    });
    $('#back-to-main-site').click(function(){
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
        window.location.href = '/';
    });
</script>