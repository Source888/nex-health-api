<?php
include_once('api-controller.php');
include_once('classes/Patient.php');
include_once('classes/Provider.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle POST request
    ob_start();

    // Define your data
    $data_header = [
        'title' => 'Appointment booking form',
        'description' => 'My Description',
        'address' => 'Franklin Squere, NY',
        'phone' => '(516)565-6565',
    ];

    // Make $data available in the included files
    extract($data_header);

    include('template/head.php');
    if (isset($_GET['step'])) {
       $step = $_GET['step'];
       $body_cont = [
                'step' => $step,
                'fname' => $_SESSION["fname"] ?? '',
                'lname' => $_SESSION["lname"] ?? '',
                'slots' => $_SESSION["slots"] ?? null,
                'show_type' => $_SESSION['show_type'] ?? false,
                'filter_days' => $_SESSION['filter_days'] ?? null,
                'filter_hours' => $_SESSION['filter_hours'] ?? null,
                'start_date' => $_SESSION['start_date'] ?? null,
                'end_date' => $_SESSION['end_date'] ?? null,
                'days' => $_SESSION['days'] ?? null,
                'appointment_type' => $_SESSION["app_type"] ?? '',
                'providers_ids' => $_SESSION["providers_ids"] ?? null,
                'full_time' => $_SESSION["full_time"] ?? null,
                'operatory_id' => $_SESSION["operatory_id"] ?? null,
                'day' => $_SESSION["day"] ?? null,
                'time' => $_SESSION["time"] ?? null,
                'existing_patient' => $_SESSION["existing_patient"] ?? false,
                'providers_arr' => $_SESSION['doctors_arr'] ?? null,
         ];
       
       extract($body_cont);
       include('template/body.php');
    } else {
        $body_cont = [
            'step' =>1,
            'fname' => $_SESSION["fname"] ?? '',
            'lname' => $_SESSION["lname"] ?? '',
            'existing_patient' => $_SESSION["existing_patient"] ?? false,
     ];
        include('template/body.php');
    }
    include('template/footer.php');
    $output = ob_get_clean();
echo $output;
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $post_data = $_POST;
    
    if (isset($post_data['step'])) {
        $step = $post_data['step'];
        
        /* Step 1 */
        if ($step == 1) {
            $fname = $post_data['fname'];
            $lname = $post_data['lname'];
            $email = $post_data['email'];
            $phone = $post_data['phone'];
            $_SESSION["fname"] = $fname;
            $_SESSION["lname"] = $lname;
            $_SESSION["email"] = $email;
            $_SESSION["phone"] = $phone;
            $existed_patient = $post_data['existed_patient'] === 'true' ? true : false;
            $patient = new Patient();
            $patient->setFirstName($fname);
            $patient->setLastName($lname);
            $patient->setEmail($email);
            $patient->setPhoneNumber($phone);
            //var_dump($existed_patient);
            if ($existed_patient) {
                $_SESSION["existing_patient"] = true;
                
                $ext_pat_data = findExtPatientId($patient);

               
                $_SESSION['patient_id'] = $ext_pat_data->id;
                $patient_data = findExtPatient($ext_pat_data->id);
                //var_dump($patient_data);
                $doctors_arr = getDoctors($patient_data);
                $_SESSION['doctors_arr'] = $doctors_arr;
                
                $response = [
                    'status' => 'success',
                    'message' => 'Existing patient',
                    
                ];
                //var_dump($response);
            } else {
                $_SESSION["existing_patient"] = false;
                
                //die();
                $response = [
                    'status' => 'success',
                    'message' => 'New patient',
                ];
            }
        } else if ($step == 2) {
            $appointment_type = $post_data['app_type'] ?? null;
            $_SESSION["app_type"] = $appointment_type;
            $app_type_id = getAppointmentTypeId($appointment_type);
            //var_dump($app_type_id);
            if(is_array($app_type_id)){
                $_SESSION['app_type_id'] = null;
            } else {
                $_SESSION['app_type_id'] = $app_type_id;
            }
            if($_SESSION["existing_patient"]){
                $providers = $_SESSION['doctors_arr'];
                $ids = array_map(function($provider) {
                    return $provider->id;
                }, $providers);
                $_SESSION["providers_ids"] = $ids;
                foreach ($providers as $provider) {
                    $slots = getAppointmentSlots($provider->id, $app_type_id);
                    $slots_to_view = getDayTimeSlots($slots);
                   
                    $provider->appointment_slots = $slots_to_view;
                }
                $_SESSION['doctors_arr'] = $providers;
            } else {
                $providers = getDoctors($app_type_id);
                $ids = array_map(function($provider) {
                    return $provider->id;
                }, $providers);
                $_SESSION["providers_ids"] = $ids;
                $slots = getAppointmentSlots($ids, $app_type_id);
                
                //var_dump($slots);
                $slots_to_view = getDayTimeSlots($slots);
                ksort($slots_to_view);
                //var_dump($slots_to_view); 
                
                $_SESSION["slots"] = $slots_to_view;
            }
            
            
            $response = [
                'status' => 'success',
                'message' => 'Step 2',
            ];
        } else if ($step == 3) {
            $full_time = $post_data['date_time'] ?? null;
            $operatory_id = $post_data['operatory_id'] ?? null;
            $day = $post_data['day'] ?? null;
            $time = $post_data['time'] ?? null;
            $_SESSION["full_time"] = $full_time;
            $_SESSION["operatory_id"] = explode(",", $operatory_id);
            $_SESSION["day"] = $day;
            $_SESSION["time"] = $time;
            $_SESSION['pid'] = explode(",", $post_data['pid']);

            $response = [
                'status' => 'success',
                'message' => 'Step 3',
            ];
        } else if ($step == 4) {
            if(!$_SESSION["existing_patient"]){
                $patient = new Patient();
                $patient->setFirstName($_SESSION["fname"]);
                $patient->setLastName($_SESSION["lname"]);
                $patient->setEmail($_SESSION["email"]);
                $patient->provider_id = $_SESSION["providers_ids"][0];
                $patient->date_of_birth = '1990-01-01';
                $patient->setPhoneNumber($_SESSION["phone"]);
                $new_pat = createPatient($patient);
               if(is_array($new_pat)){
                $idStartPos = strpos($new_pat[0], 'id=') + 3;
                $id = substr($new_pat[0], $idStartPos);
                $id = intval($id);
                $_SESSION['patient_id'] = $id;
               } else {
                $_SESSION['patient_id'] = $new_pat->id;
               }
            }
            
            $res = createAppointment($_SESSION['pid'][0], $_SESSION['patient_id'], $_SESSION['operatory_id'][0], $_SESSION['full_time'],$_SESSION['app_type_id']);
            var_dump($res);
            die();
            $response = [
                'status' => 'success',
                'message' => 'Step 4',
            ];
        } else if ($step == 'filter_dates') {
            $date = $post_data['date'];
            $startDateAndDays = getStartDateAndDays($date);
            $_SESSION['start_date'] = $startDateAndDays[0];
            $_SESSION['end_date'] = $startDateAndDays[2];
            $_SESSION['days'] = $startDateAndDays[1];
            $ext_pat = $_SESSION["existing_patient"];
            getSlots($ext_pat, $_SESSION['start_date'], $_SESSION['days'], $_SESSION['filter_days'] ?? null, $_SESSION['filter_hours'] ?? null, $_SESSION['show_type'] ?? false);
            $response = [
                'status' => 'success',
                'message' => 'Filter dates',
            ];

        } else if ($step == 'set_filter') {
            $filter_days = $post_data['days'] ?? null;
            $filter_hours = $post_data['times'] ?? null;
            if($filter_hours == 'all'){
                $filter_hours = null;
            }
            $ext_pat = $_SESSION["existing_patient"];
            $_SESSION['filter_days'] = $filter_days;
            $_SESSION['filter_hours'] = $filter_hours;
            getSlots($ext_pat, $_SESSION['start_date'] ?? null, $_SESSION['days'] ?? null, $filter_days, $filter_hours, $_SESSION['show_type'] ?? false);
            $response = [
                'status' => 'success',
                'message' => 'Filter dates',
            ];
        } else if ($step == 'show_type') {
            $show_type = ($post_data['show_type'] == 'dates') ?? false;
            $_SESSION['show_type'] = $show_type;
            $ext_pat = $_SESSION["existing_patient"];
            getSlots($ext_pat, $_SESSION['start_date'] ?? null, $_SESSION['days'] ?? null, $_SESSION['filter_days'] ?? null, $_SESSION['filter_hours'] ?? null, $show_type);
            $response = [
                'status' => 'success',
                'message' => 'Display type changed',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Invalid step',
            ];
        }
    }
   
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
    
}
function getDayTimeSlots($slots_array, $filter_days = null, $filter_hours = null){
   
    $newSlots = [];
    foreach ($slots_array as  $slots) {
        foreach ($slots['slots'] as $slot) {
            // Extract the date and time from the time string
            if(is_array($slot)){
                $dateTime = new DateTime($slot['time']);
                $date = $dateTime->format('Y-m-d');
                $time = $dateTime->format('H:i');
                $dayOfWeek = $dateTime->format('D');
                $meridiem = $dateTime->format('A');
                //var_dump($filter_days);
                //var_dump($filter_hours);
                //var_dump($dayOfWeek);
                //var_dump($meridiem);
                if((!is_null($filter_days) && in_array($dayOfWeek, $filter_days)) || is_null($filter_days)){
                   
                    if (!isset($newSlots[$date])) {
                        $newSlots[$date] = [];
                    }
                    if ((!is_null($filter_hours) && in_array($meridiem, $filter_hours)) || is_null($filter_hours)) {
                        
                        $time = (new DateTime($time))->format('g:i A');// Add the time to the date array
                        if (isset($newSlots[$date]) && is_array($newSlots[$date][$slot['time']])) {
                            $oldOid = $newSlots[$date][$slot['time']]['operatory_id'] ?? null;
                            $oldPid = $newSlots[$date][$slot['time']]['pid'] ?? null;
                            
                        }
                        $newSlots[$date][$slot['time']] = [
                            'pid' => (!is_null($oldPid)) ? $oldPid.','.$slots['pid'] : $slots['pid'],
                            'time' => strtolower($time),
                            'operatory_id' => (!is_null($oldOid)) ? $oldOid.','.$slot['operatory_id'] : $slot['operatory_id'],
                            'full_time' => $slot['time'],
                        ];
                    } 
                } 
                
            }
            
            
            
        }
    }
    foreach ($newSlots as $date => $slots) {
        ksort($newSlots[$date]);
        if(empty($newSlots[$date])){
            unset($newSlots[$date]);
        
        }
    }
    return $newSlots;
}
function getStartDateAndDays($date_string){
    list($startDateString, $endDateString) = explode(" - ", $date_string);

    $startDate = DateTime::createFromFormat('d/m/Y', $startDateString);
    $endDate = DateTime::createFromFormat('d/m/Y', $endDateString);

    $startDateFormatted = $startDate->format('Y-m-d');
    $endDateFormatted = $endDate->format('Y-m-d');

    $interval = $startDate->diff($endDate);
    $days = $interval->days;
    $_SESSION['days'] = $days;
    return [$startDateFormatted, $days, $endDateFormatted];
}

function getSlots($ext_pat, $startDate = null, $Days = null, $filter_days = null, $filter_hours = null, $show_type = false){
    if($ext_pat && !$show_type){
        $providers = $_SESSION['doctors_arr'];
        $ids = array_map(function($provider) {
            return $provider->id;
        }, $providers);
        $_SESSION["providers_ids"] = $ids;
        foreach ($providers as $provider) {
            $slots = getAppointmentSlots($provider->id, $_SESSION['app_type_id'], $startDate, $Days);
            $slots_to_view = getDayTimeSlots($slots, $filter_days, $filter_hours);
           
            $provider->appointment_slots = $slots_to_view;
        }
        //var_dump($providers);
        $_SESSION['doctors_arr'] = $providers;
        return $providers;
    } else {
        $providers = getDoctors($_SESSION['app_type_id']);
        $ids = array_map(function($provider) {
            return $provider->id;
        }, $providers);
        $_SESSION["providers_ids"] = $ids;
        $slots = getAppointmentSlots($ids, $_SESSION['app_type_id'], $startDate, $Days);
        
        //var_dump($slots);
        $slots_to_view = getDayTimeSlots($slots, $filter_days, $filter_hours);
        ksort($slots_to_view);
        //var_dump($slots_to_view); 
        
        $_SESSION["slots"] = $slots_to_view;
        return $slots_to_view;
    }
    
}