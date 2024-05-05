<?php
require_once dirname(__DIR__) . '/classes/Provider.php'; 

?>

<h2 class="form-title">DATE & TIME</h2>
<div class="container">
<div class="dateselect">
    <label for="rangePicker" class="calendar-label">Calendar</label>
  <input type="text" id="rangePicker" class="form-control" size="10" data-datepick="showOtherMonths: true, firstDay: 1, dateFormat: 'dd/mm/yyyy', 
        minDate: 'new Date()'" placeholder="Select a date or range">
  <button class="filter-btn" id="filter-btn" onclick="showFilter();">Filter</button>
</div>
<div class="toggler-section">
    <?php if (isset($body_cont['existing_patient']) && $body_cont['existing_patient'] === true) { ?>
        <div class="toggler">
            <input type="radio" id="by-doc" name="show_type" value="doc" <?php if(!$body_cont['show_type']) { echo 'checked';} ?> >
            <label for="by-doc">By provider</label>
            <input type="radio" id="by-dates" name="show_type" value="dates" <?php if($body_cont['show_type']) { echo 'checked';} ?>>
            <label for="by-dates">Next available</label>
        </div>
        <?php } else { ?>
            <div class="today">
                <input type="checkbox" id="today" name="today" value="today">
                <label for="today">Today</label> 
            </div>
    <?php } ?>
</div>
<section class="appointment-slots">
<?php if (isset($body_cont['existing_patient']) && $body_cont['existing_patient'] === true && !$body_cont['show_type']) { ?>
    <?php if ($body_cont['providers_arr'] && is_array($body_cont['providers_arr'])) { ?>
        <div class="blocks container">
            <div class="doctors-blocks-wrapper">
                <?php foreach ($body_cont['providers_arr'] as $provider) { ?>
                    <?php if ($provider->appointment_slots && is_array($provider->appointment_slots)){ ?>
                    <div class="provider-block">
                        <div class="provider-info">
                            <h3><?php echo $provider->first_name; ?> <?php echo $provider->last_name; ?></h3>
                            <p><?php echo $provider->specialty; ?></p>
                            <div class="provider-img">
                                <img src="<?php echo $provider->profile_url; ?>" alt="<?php echo $provider->first_name; ?>">
                            </div>
                            <?php if($provider->last_visited) { ?>
                                <div class="last-visited">
                                    
                                <p class="previous-provider">This is your previous provider.</p>
                                <p class="previous-provider">You can also chose another provider.</p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="provider-slots">
                            <?php if ($provider->appointment_slots && is_array($provider->appointment_slots)) { ?>
                                <div class="provider-slots-wrapper">
                                    <?php foreach ($provider->appointment_slots as $day => $slot) { ?>
                                        <div class="row">
                                            <?php
                                            
                                            $date = DateTime::createFromFormat('Y-m-d', $day);
                                            $day_to_print = $date->format('D, d'); 
                                            ?>
                                            <h3 class="slot-day"><?php echo $day_to_print; ?></h3>
                                            <div class="slots-wrapper slider">
                                                <?php foreach ($slot as $time) { ?>
                                                    <div class="slot-block">
                                                        <span data-operatory-id="<?php echo $time['operatory_id'] ?>" data-full-time="<?php echo $time['full_time'] ?>" data-pid="<?php echo $time['pid'] ?>"><?php echo $time['time']; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
<?php } else { ?>
    <?php if (isset($body_cont['slots']) && is_array($body_cont['slots'])) { ?>
        <div class="blocks container">
            <div class="blocks-wrapper-days">
                <?php foreach ($body_cont['slots'] as $day => $times) { ?>
                    <div class="row">
                        <?php
                        $date = DateTime::createFromFormat('Y-m-d', $day);
                        $day_to_print = $date->format('D, d'); 
                        ?>
                        <span class="slot-day"><?php echo $day_to_print; ?></span>
                        <div class="slots-wrapper slider">
                            <?php foreach ($times as $time) { ?>
                                <div class="slot-block">
                                    <span data-operatory-id="<?php echo $time['operatory_id'] ?>" data-full-time="<?php echo $time['full_time'] ?>" data-pid="<?php echo $time['pid'] ?>"><?php echo $time['time']; ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
<?php } ?>

<div class="buttons-row">  
    <input type="button" id="bk-btn" value="Back">
    <input type="submit" id="btn-ctn" value="Continue">
</div>
</div>
<div class="container">
  <div class="row need-help">
    <h3>Need help? Our friendly staff are here to help. Call <a href="tel:(516)565-6565">(516)565-6565</a></h3>
    </div>
</div>
<div class="filter-popup">
    <span class="close-filter-popup">✕</span>
    <div class="filter-popup-content">
        <h3>Filter by</h3>
        <div class="filter-by-day">
            <span>Which day work best?</span>
            <div name="filter-by-day" id="filter-by-day">
                <div data-day="Mon" <?php if(is_array($body_cont['filter_days']) && in_array('Mon', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>M</div>
                <div data-day="Tue" <?php if(is_array($body_cont['filter_days']) && in_array('Tue', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>T</div>
                <div data-day="Wed" <?php if(is_array($body_cont['filter_days']) && in_array('Wed', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>W</div>
                <div data-day="Thu" <?php if(is_array($body_cont['filter_days']) && in_array('Thu', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>T</div>
                <div data-day="Fri" <?php if(is_array($body_cont['filter_days']) && in_array('Fri', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>F</div>
                <div data-day="Sat" <?php if(is_array($body_cont['filter_days']) && in_array('Sat', $body_cont['filter_days'])) { echo 'class="selected"';} ?>>S</div>
            </div>
        </div>
        <div class="filter-by-time">
            <span>What time of day?</span>
            <div class="by-time">
                <input type="radio" id="all-time" name="filter-by-time" value="ALL">
                <label for="all-time">All</label><br>
                <input type="radio" id="am-time" name="filter-by-time" value="AM">
                <label for="am-time">am</label>
                <input type="radio" id="pm-time" name="filter-by-time" value="PM">
                <label for="pm-time">pm</label>
            </div>
            
        </div>
    </div>  
    <div class="filter-popup-buttons">
        <button class="filter-apply">Apply</button>
    </div> 
</div>
</section>
<script>
    function initSlides(){
        $('.slider').slick({
            slidesToShow: <?php if($body_cont['existing_patient']) { echo 3; } else { echo 5; } ?>,
            slidesToScroll: 1,
            centerPadding: '10px',
            prevArrow: '<button type="button" class="slick-prev">&lt;</button>',
            nextArrow: '<button type="button" class="slick-next">&gt;</button>'
        });
    }
    function updateBlockContainer() {
        $.ajax({
            url: 'index.php?step=3',
            type: 'GET',
            success: function(response) {
                
                var $response = $(response);

               
                var $part = $response.find('section.appointment-slots');

                
                $('section.appointment-slots').html($part.html());
                initSlides();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed: ' + textStatus);
            }
        });
    }
    function showFilter(){
        $('.filter-popup').css('display', 'block');
    }
    $('.close-filter-popup').click(function(){
        $('.filter-popup').css('display', 'none');
    });
    function updateView(){
        var date = $('#rangePicker').val();
        var step = 'filter_dates';
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                date: date,
                step: step
            },
            success: function(response){
                if (response.status == 'success') {
                    updateBlockContainer();
                }
                console.log(response);
            }
        });
    }
    
 $('#rangePicker').datepick({ 
    rangeSelect: true,
    prevText: '<',
    nextText: '>',
    todayText: '',
    clearText: 'Reset',
    closeText: 'Apply',
    changeMonth: false,
    changeYear: false,
    onClose: function() {
        updateView();
    },
    });
    $(document).ready(function(){
        initSlides();
        $(document).on('click', '.slot-block', function(e){
            $('.slot-block').removeClass('selected');
            $(this).addClass('selected');
        });
        $(document).on('click', '.filter-apply', function(e){
            e.preventDefault();
            var days = [];
            var times = [];
            $('#filter-by-day div').each(function(){
                if ($(this).hasClass('selected')) {
                    days.push($(this).data('day'));
                }
            });
            $('input[name="filter-by-time"]').each(function(){
                if ($(this).is(':checked')) {
                    times.push($(this).val());
                }
            });
            var step = 'set_filter';
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    days: days,
                    times: times,
                    step: step
                },
                success: function(response){
                    if (response.status == 'success') {
                        location.reload();
                    }
                    console.log(response);
                }
            });
        });
        $(document).on('click', '.toggler input', function(){
            var show_type = $('input[name="show_type"]:checked').val();
            var step = 'show_type';
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    show_type: show_type,
                    step: step
                },
                success: function(response){
                    if (response.status == 'success') {
                        updateBlockContainer();
                    }
                    console.log(response);
                }
            });
        });
    $(document).on('click', '#filter-by-day div', function(e){
        
        $(this).toggleClass('selected');
    });
    
    $(document).on('click', '#bk-btn', function(e){
        e.preventDefault();
        window.location.href = 'index.php?step=2';
    });
    $(document).on('click', '#btn-ctn', function(e){
        e.preventDefault();
        var date_time = $('.slot-block.selected span').data('full-time');
        var time = $('.slot-block.selected span').text();
        var day = $('.slot-block.selected').parent().parent().parent().parent().find('.slot-day').text();
        var operatory_id = $('.slot-block.selected span').data('operatory-id');
        var pid = $('.slot-block.selected span').data('pid');
        var step = 3;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                date_time: date_time,
                time: time,
                day: day,
                operatory_id: operatory_id,
                pid: pid,
                step: step
            },
            success: function(response){
                if (response.status == 'success') {
                    window.location.href = 'index.php?step=4';
                }
                console.log(response);
            }
        });
    });
    
});
</script>
