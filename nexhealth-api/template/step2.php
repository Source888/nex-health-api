<h2 class="form-title">What type of appointment would you like to schedule?</h2>
<div class="blocks container">
    <div class="blocks-wrapper">
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/whitening.png" alt="Cleaning">
                <h3>Cleaning</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/clinic.png" alt="Emergency">
                <h3>Emergency</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/jaw.png" alt="Periodontist Consult">
                <h3>Periodontist Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/dentist.png" alt="General Consult">
                <h3>General Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/denture.png" alt="Denture Consult">
                <h3>Denture Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/implant.png" alt="Implant Consult">
                <h3>Implant Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/root_canal.png" alt="Endodontist Consult">
                <h3>Endodontist Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/orthodontic.png" alt="Orthodontist Consult">
                <h3>Orthodontist Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/dentist_chair.png" alt="Oral Surgeon Consult">
                <h3>Oral Surgeon Consult</h3>
        </div>
        <div class="app-type-block">
                <img src="/nexhealth-api/assets/img/scedule.png" alt="Other Services">
                <h3>Other Services</h3>
        </div>
    </div>
</div>
<div class="buttons-row">  
  
      <?php if($body_cont['editing'] === false){ ?>
          <input type="button" id="bk-btn" value="Back">
          <input type="submit" id="btn-ctn" value="Continue">
      <?php } else { ?>
        <input type="submit" id="btn-ctn" value="Save">
      <?php } ?>
 
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
                window.location.href = 'index.php?step=1';
                });
                $('.app-type-block').click(function(e){
                        $('.app-type-block').removeClass('selected');
                        $(this).addClass('selected');
                });
                $('#btn-ctn').click(function(e){
                        e.preventDefault();
                        var step = 2;
                        var after_edit = <?php if($body_cont['editing']){ echo 'true'; } else { echo 'false'; } ?>;
                        var app_type = $('.app-type-block.selected h3').text();
                        $.ajax({
                                url: 'index.php',
                                type: 'POST',
                                data: {
                                        step: step,
                                        app_type: app_type,
                                        
                                },
                                success: function(response) {
                                        if(response && typeof response.redirect == 'string'){
                                                window.location.href = response.redirect;
                                        } else if (response.status == 'success') {
                                                window.location.href = 'index.php?step=3';
                                        } else{
                                                alert(response.message);
                                        }
                                }
                        });
                });
        });
</script>