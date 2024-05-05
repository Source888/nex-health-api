<div class="container-full-widht">
    <div class="step-sidebar">
        <ul class="steps checklist">
            <li class="step-item">

                <div class="step-mark <?php if($body_cont['step'] == 1){ echo 'current'; } else if ($body_cont['step'] > 1) { echo 'completed';} ?>"><span class="mark "><?php if($body_cont['step'] <= 1){ echo 1; } else if ($body_cont['step'] > 1) { echo '✔';} ?></span><div class="vert-line"></div></div><div  class="step-link <?php if($body_cont['step'] == 1){ echo 'current'; } else if ($body_cont['step'] > 1) { echo 'completed';} ?>"><a href="index.php">Your Information</a><p><?=($body_cont['fname'])?> <?=($body_cont['lname'])?></p></div>
            </li>
            <li class="step-item">
                <div class="step-mark <?php if($body_cont['step'] == 2){ echo 'current'; } else if ($body_cont['step'] > 2) { echo 'completed';} ?>"><span class="mark"><?php if($body_cont['step'] <= 2){ echo 2; } else if ($body_cont['step'] > 2) { echo '✔';} ?></span><div class="vert-line"></div></div><div  class="step-link <?php if($body_cont['step'] == 2){ echo 'current'; } else if ($body_cont['step'] > 2) { echo 'completed';} ?>"><a href="index.php?step=2">Reason for Visit</a><p> <?=($body_cont['appointment_type'])?></p></div>
            </li>
            <li class="step-item">
                <div class="step-mark <?php if($body_cont['step'] == 3){ echo 'current'; } else if ($body_cont['step'] > 3) { echo 'completed';} ?>"><span class="mark"><?php if($body_cont['step'] <= 3){ echo 3; } else if ($body_cont['step'] > 3) { echo '✔';} ?></span><div class="vert-line"></div></div><div  class="step-link <?php if($body_cont['step'] == 3){ echo 'current'; } else if ($body_cont['step'] > 3) { echo 'completed';} ?>"><a href="index.php?step=3">Date & Time</a><p> <?=($body_cont['day'])?> <?=($body_cont['time'])?></p></div>
            </li>
            <li class="step-item">
                <div class="step-mark <?php if($body_cont['step'] == 4){ echo 'current'; } else if ($body_cont['step'] > 4) { echo 'completed';} ?>"><span class="mark"><?php if($body_cont['step'] <= 4){ echo 4; } else if ($body_cont['step'] > 4) { echo '✔';} ?></span><div class="vert-line"></div></div><div  class="step-link <?php if($body_cont['step'] == 4){ echo 'current'; } else if ($body_cont['step'] > 4) { echo 'completed';} ?>"><a href="index.php?step=4">Additional Information</a></div>
            </li>
            <li class="step-item">
                <div class="step-mark last <?php if($body_cont['step'] == 5){ echo 'current'; } else if ($body_cont['step'] > 5) { echo 'completed';} ?>"><span class="mark"><?php if($body_cont['step'] <= 5){ echo 5; } else if ($body_cont['step'] > 5) { echo '✔';} ?></span><div class="vert-line"></div></div><div  class="step-link <?php if($body_cont['step'] == 5){ echo 'current'; } else if ($body_cont['step'] > 5) { echo 'completed';} ?>"><a href="index.php?step=5">Appointment review</a></div>
            </li>
        </ul>
    </div>
    <div class="appointment-container">
        <div class="form-block">
        <?php
        if(isset($body_cont) && isset($body_cont['step']) ){
            switch ($body_cont['step']) {
                case 2:
                    include('template/step2.php');
                    break;
                case 3:
                    include('template/step3.php');
                    break;
                case 4:
                    include('template/step4.php');
                    break;
                case 5:
                    include('template/step5.php');
                    break;
                default:
                    include('template/step1.php');
                    break;
            }
            
         } else {
            include('template/step1.php');
         }
        ?>
        </div>
    </div>
</div>