<?php
require_once dirname(__DIR__) . '/classes/Provider.php'; 

?>

<h2 class="form-title">DATE & TIME</h2>
<div class="container">
<div class="dateselect">
    <label for="rangePicker" class="calendar-label">Calendar</label>
  <input type="text" id="rangePicker" class="form-control" size="10" data-datepick="showOtherMonths: true, firstDay: 1, dateFormat: 'yyyy-mm-dd', 
        minDate: 'new Date()'" placeholder="Select a date or range" <?php if(!is_null($body_cont['start_date']) && !is_null($body_cont['end_date']) ) { echo "value='{$body_cont['start_date']} - {$body_cont['end_date']}'";}?>>
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
        <div class="mobile-toggler">
            <span onclick="tabToggle('doc', this)" class="<?php if(!$body_cont['show_type']) { echo 'active';} ?>">By provider</span>
            <span onclick="tabToggle('dates', this)" class="<?php if($body_cont['show_type']) { echo 'active';} ?>">Next available</span>
        </div>
        <?php } else { ?>
            <div class="today">
                <label for="today"> <input type="checkbox" id="today" name="today" value="today" <?php if($body_cont['start_date'] == $body_cont['end_date']) { echo 'checked';} ?>>
                Today</label> 
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
                            <div class="prov-info-txt">
                                <h3><?php echo $provider->first_name; ?> <?php echo $provider->last_name; ?></h3>
                                <p><?php echo $provider->nexhealth_specialty; ?></p>
                            </div>
                            
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
                                                    <div class="slot-block  <?php if ((!is_null($body_cont['full_time']) && $body_cont['full_time'] == $time['full_time']) && (!is_null($body_cont['pid']) && in_array($body_cont['pid'], explode(",",$time['pid'])))) { echo "selected"; } ?>">
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
                    <div class="row blocks-mb">
                        <?php
                        $date = DateTime::createFromFormat('Y-m-d', $day);
                        $day_to_print = $date->format('D, d'); 
                        ?>
                        <span class="slot-day"><?php echo $day_to_print; ?></span>
                        <div class="slots-wrapper slider">
                            <?php foreach ($times as $time) { ?>
                                <div class="slot-block <?php if (!is_null($body_cont['full_time']) && $body_cont['full_time'] == $time['full_time']) { echo "selected"; } ?>">
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
<div class="mobile-calendar-and-filter-holder">
    <div class="tab-head">
        
            <span class="active" onclick="openTab('calendar-tab', this)">Range</span>
        
            <span onclick="openTab('filter-tab', this)">Filter</span>
        
        
    </div>
    <div class="tab-content">
       <div class="tab-filter" id="calendar-tab"></div>
       <div class="tab-filter" id="filter-tab">
        <div class="filter-tab-content-wrapper">
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
                    <input type="radio" id="all-time-mb" name="filter-by-time" value="ALL">
                    <label for="all-time-mb" class="by-time-mb">All</label><br>
                    <input type="radio" id="am-time-mb" name="filter-by-time" value="AM">
                    <label for="am-time-mb" class="by-time-mb">am</label>
                    <input type="radio" id="pm-time-mb" name="filter-by-time" value="PM">
                    <label for="pm-time-mb" class="by-time-mb">pm</label>
                </div>
                
            </div>
            </div>
       </div>     
     </div>
     <div class="tab-buttons-row">
        <button class="filter-reset" onclick="setDefaults();">Reset</button>
        <div></div>
        <button class="filter-cancel" onclick="hideFilters();">Cancel</button>
        <button class="filter-apply-mob" onclick="updateView();">Apply</button>

     </div>
</div>
</section>
<script>
    function showError(error_text){
                $('.error-message').fadeIn();
                $('.error').text(error_text);
        }
        function clearErrors(){
                $('.error-message').fadeOut();
        }
    function firstLastBorders(){
        var selectedElements = document.querySelectorAll('a.datepick-selected');
        var firstA = $('a.datepick-selected').first();
        var lastA = $('a.datepick-selected').last();
        if(screen.width <= 820) {
            if($('a.datepick-selected').length == 1){
                firstA.css("cssText", "border-radius: 5vw;");
                firstA.parent().css("cssText", "border-radius: 5vw;");
            } else {
                firstA.css("cssText", "border-radius: 5vw; background-color: #27c0c8 !important;");
                firstA.parent().css("cssText", "border-top-left-radius: 5vw; border-bottom-left-radius: 5vw; background-color: #27c0c878 !important;");
                lastA.css("cssText", "border-radius: 5vw; background-color: #27c0c8 !important;");
                lastA.parent().css("cssText", "border-top-right-radius: 5vw; border-bottom-right-radius: 5vw; background-color: #27c0c878 !important;");
            }
        } else {
            if($('a.datepick-selected').length == 1){
                firstA.css("cssText", "border-radius: 20px; background-color: #27c0c8 !important;");
                firstA.parent().css("cssText", "border-radius: 20px;");
            } else {
                firstA.css("cssText", "border-radius: 20px; background-color: #27c0c8 !important;");
                firstA.parent().css("cssText", "border-top-left-radius: 20px; border-bottom-left-radius: 20px; background-color: #27c0c878 !important;");
                lastA.css("cssText", "border-radius: 20px; background-color: #27c0c8 !important;");
                lastA.parent().css("cssText", "border-top-right-radius: 20px; border-bottom-right-radius: 20px; background-color: #27c0c878 !important;");
            }
        }
        
        
    }
    function hideFilters(){
        $('.mobile-calendar-and-filter-holder').css('display', 'none');
    }
    function getFormatedDate(date){
        var year = date.getFullYear();
        var month = date.getMonth() + 1; // getMonth() returns a zero-based value (0-11)
        var day = date.getDate();

        // Pad the month and day with leading zeros if necessary
        if (month < 10) month = '0' + month;
        if (day < 10) day = '0' + day;

        var formattedDate = year + '-' + month + '-' + day;
        return formattedDate;
    }
    function setDefaults(){
        var filter_el = '.tab-filter div #filter-by-day div';
        var time_el = '.tab-filter div input[name="filter-by-time"]';
        $(filter_el).each(function(){
            $(this).removeClass('selected');
        });
        $(time_el).each(function(){
            $(this).prop('checked', false);
        });
        var date = new Date();
        date_to_set = getFormatedDate(date);
        $('#rangePicker').val(date_to_set + ' - ' + date_to_set);
       
        updateView();
    }
    function openTab(tabToDisplay, elmnt) {
        var i;
        var tab_head = document.querySelectorAll('.tab-head span');
        for (i = 0; i < tab_head.length; i++) {
            tab_head[i].classList.remove("active");
        }
        var x = document.getElementsByClassName("tab-filter");

        for (i = 0; i < x.length; i++) {
            
            x[i].style.display = "none";  
        }
        elmnt.classList.add("active");
        document.getElementById(tabToDisplay).style.display = "flex";  
    }
    function initSlides(){
        
        $('.slider').each(function() {
            var existing_pat = <?php if($body_cont['existing_patient']) { echo 'true'; } else { echo 'false'; } ?>;
            if(existing_pat && $('input[name="show_type"]:checked').val() == 'doc'){
                var slidesToShow = 3;
            } else {
                var slidesToShow = 5;
            }
            
            if(screen.width <= 820){
                slidesToShow = 4;
            } 

            $(this).slick({
                slidesToShow: slidesToShow,
                slidesToScroll: 1,
                centerPadding: '10px',
                prevArrow: '<button type="button" class="slick-prev">&lt;</button>',
                nextArrow: '<button type="button" class="slick-next">&gt;</button>',
                responsive: [
                    {
                        breakpoint: 820,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
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
        var days = [];
        var times = [];
        $('.mobile-calendar-and-filter-holder').css('display', 'none');
        if(screen.width <= 820){
            var filter_el = '.tab-filter div #filter-by-day div';
            var time_el = '.tab-filter div input[name="filter-by-time"]';
        } else {
            var filter_el = '#filter-by-day div';
            var time_el = 'input[name="filter-by-time"]';
        }
        $(filter_el).each(function(){
            if ($(this).hasClass('selected')) {
                days.push($(this).data('day'));
            }
        });
        $(time_el).each(function(){
            if ($(this).is(':checked')) {
                times.push($(this).val());
            }
        });
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                date: date,
                step: step,
                days: days,
                times: times
            },
            success: function(response){
                if (response.status == 'success') {
                    updateBlockContainer();
                }
                console.log(response);
            }
        });
    }
    function moveFilterToTab(){
        var filter_el = $('.filter-popup-content');
        var tab_el = $('.tab-filter');
        tab_el.append(filter_el);
    }
    function backFilter(){
        var filter_el = $('.filter-popup-content');
        var filter_popup = $('.filter-popup');
        filter_popup.append(filter_el);
    }
    function viewToggle(type){
        var step = 'show_type';
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    show_type: type,
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
    function tabToggle(type, element){
        $('.mobile-toggler span').removeClass('active');
        element.classList.add('active');
        
        viewToggle(type);
    }
    function filterApply(){
            var days = [];
            var times = [];
            if(screen.width <= 820){
                var filter_el = '.tab-filter div #filter-by-day div';
                var time_el = '.tab-filter div input[name="filter-by-time"]';
            } else {
                var filter_el = '#filter-by-day div';
                var time_el = 'input[name="filter-by-time"]';
            }
            $(filter_el).each(function(){
                if ($(this).hasClass('selected')) {
                    days.push($(this).data('day'));
                }
            });
            $(time_el).each(function(){
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
                    updateView();
                }
            });
    }
var date_string = '<?php if(!is_null($body_cont['start_date']) && !is_null($body_cont['end_date']) ) { echo "{$body_cont['start_date']} - {$body_cont['end_date']}";}?>';
if(screen.width <= 820){
    var cont_to_view = '.mobile-calendar-and-filter-holder .tab-content #calendar-tab';
$('#calendar-tab').datepick({ 
    rangeSelect: true,
    prevText: '<',
    nextText: '>',
    todayText: '',
    rangeSeparator: ' - ',
    onSelect: function(dates) {
        date_start = getFormatedDate(dates[0]);
        date_end = getFormatedDate(dates[1]);;
        $('#rangePicker').val(date_start + ' - ' + date_end);
        
    },
    onShow: function() {
        firstLastBorders();
        
        
        
        
    },
    minDate: new Date(),
    clearText: 'Reset',
    closeText: 'Apply',
    changeMonth: false,
    changeYear: false,
    popupContainer: cont_to_view,
    onClose: function() {
        
        
        if(typeof $('#rangePicker').val() == undefined || date_string != $('#rangePicker').val()){
            date_string = $('#rangePicker').val();
            if(typeof $('#today') != undefined && $('#today').is(':checked')){
                $('#today').prop('checked', false);
            }
            updateView();
        }
        
    },
    });
} else {
    var cont_to_view = 'body';
    $('#rangePicker').datepick({ 
    selectDefaultDate: true,
    defaultDate: new Date(),
    
    rangeSelect: true,
    prevText: '<',
    nextText: '>',
    todayText: '',
    rangeSeparator: ' - ',
    
    onShow: function() {
        firstLastBorders();
        
        
        
        
    },

    minDate: new Date(),
    clearText: 'Reset',
    closeText: 'Apply',
    changeMonth: false,
    changeYear: false,
    popupContainer: cont_to_view,
    onClose: function() {
        
        
        if(typeof $('#rangePicker').val() == undefined || date_string != $('#rangePicker').val()){
            date_string = $('#rangePicker').val();
            if(typeof $('#today') != undefined && $('#today').is(':checked')){
                $('#today').prop('checked', false);
            }
            updateView();
        }
        
    },
    });
}
 
    
    $(document).ready(function(){
        initSlides();
        $('.by-time label').click(function() {
            var radioButtonId = $(this).attr('for');
            $('.by-time input[type=radio]').prop('checked', false);
            $('#' + radioButtonId).prop('checked', true);
        });


        if(screen.width <= 820){
            $('#rangePicker').on('click', function(){
                if($('.mobile-calendar-and-filter-holder').css('display') == 'block'){
                    
                    $('.mobile-calendar-and-filter-holder').css('display', 'none');
                } else {
                   
                    $('.mobile-calendar-and-filter-holder').css('display', 'block');
                }
                
            });
        }
        $(document).on('click', '.slot-block', function(e){
            $('.slot-block').removeClass('selected');
            $(this).addClass('selected');
        });
        $(document).on('click', 'tr td a', function(){
            firstLastBorders();
        
        });
        $(document).on('click', '.filter-apply', function(e){
            e.preventDefault();
            filterApply();
        });
        $(document).on('click', '.toggler input', function(){
            var show_type = $('input[name="show_type"]:checked').val();
            viewToggle(show_type);
        });
    $(document).on('click', '#filter-by-day div', function(e){
        
        $(this).toggleClass('selected');
    });
    $(document).on('change', '#today', function(e){
        var d_string = $('#rangePicker').val();
        var today = new Date();
            
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            var to_ins = today + ' - ' + today;
            console.log(to_ins, d_string);
        if ($(this).is(':checked') && d_string != to_ins) {
            
            $('#rangePicker').val(to_ins);
            updateView();
        }
    });
    $(document).on('click', '#bk-btn, #bk-btn-mb', function(e){
        e.preventDefault();
        window.location.href = 'index.php?step=2';
    });
    $(document).on('click', '#btn-ctn, #btn-ctn-mb', function(e){
        e.preventDefault();
        if($('.slot-block.selected') == undefined || $('.slot-block.selected').length == 0){
            showError('Please select appointment time');
            return;
        }
        var date_time = $('.slot-block.selected span').data('full-time');
        var time = $('.slot-block.selected span').text();
        var day = $('.slot-block.selected').parent().parent().parent().parent().find('.slot-day').text();
        var operatory_id = $('.slot-block.selected span').data('operatory-id');
        var pid = $('.slot-block.selected span').data('pid');
        var step = 3;
        var after_edit = <?php if($body_cont['editing']){ echo 'true'; } else { echo 'false'; } ?>;
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                date_time: date_time,
                time: time,
                day: day,
                operatory_id: operatory_id,
                pid: pid,
                step: step,
                after_edit: after_edit
            },
            success: function(response){
                if (response.status == 'success') {
                    window.location.href = 'index.php?step=4';
                } else if (typeof response.redirect === 'string') {
                        window.location.href = response.redirect;
                } else{
                    alert(response.message);
                }
                console.log(response);
            }
        });
    });
    
});
</script>
