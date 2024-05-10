<?php
include_once('api-controller.php');
include_once('classes/Patient.php');
include_once('classes/Provider.php');
include_once('classes/Appointment.php');
session_set_cookie_params(43200);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   
    ob_start();

   
    $data_header = [
        'title' => 'Appointment booking form',
        'description' => 'Island Dental Associates webbooking form',
        'address' => 'Franklin Squere, NY',
        'phone' => '(516)565-6565',
    ];

    
    extract($data_header);

    include('template/head.php');
    if (isset($_GET['step'])) {
       $step = $_GET['step'];
       $body_cont = [
                'step' => $step,
                'editing' => $_SESSION['editing'] ?? false,
                'patient' => $_SESSION['patient'] ?? null,
                'slots' => $_SESSION["slots"] ?? null,
                'show_type' => $_SESSION['show_type'] ?? false,
                'filter_days' => $_SESSION['filter_days'] ?? null,
                'filter_hours' => $_SESSION['filter_hours'] ?? null,
                'start_date' => $_SESSION['start_date'] ?? null,
                'end_date' => $_SESSION['end_date'] ?? null,
                'days' => $_SESSION['days'] ?? null,
                'dob' => $_SESSION['dob'] ?? null,
                'for_who_app' => $_SESSION["for_who_app"] ?? null,
                'appointment_type' => $_SESSION["app_type"] ?? '',
                'providers_ids' => $_SESSION["providers_ids"] ?? null,
                'full_time' => $_SESSION["full_time"] ?? null,
                'operatory_id' => $_SESSION["operatory_id"] ?? null,
                'day' => $_SESSION["day"] ?? null,
                'time' => $_SESSION["time"] ?? null,
                'existing_patient' => $_SESSION["existing_patient"] ?? false,
                'providers_arr' => $_SESSION['doctors_arr'] ?? null,
                'insurance' => $_SESSION["insurance"] ?? null,
                'appointment' => $_SESSION["appointment"] ?? null,
                'parentGuardian' => $_SESSION["parentGuardian"] ?? null,
                'guardian' => $_SESSION["guardian"] ?? null,
                'provider' => $_SESSION["provider"] ?? null,
         ];
       
       extract($body_cont);
       include('template/body.php');
    } else {
        $body_cont = [
            'step' =>$step ?? 1,
            'patient' => $_SESSION['patient'] ?? null,
            'editing' => $_SESSION['editing'] ?? false,
            'slots' => $_SESSION["slots"] ?? null,
            'show_type' => $_SESSION['show_type'] ?? false,
            'filter_days' => $_SESSION['filter_days'] ?? null,
            'filter_hours' => $_SESSION['filter_hours'] ?? null,
            'start_date' => $_SESSION['start_date'] ?? null,
            'end_date' => $_SESSION['end_date'] ?? null,
            'days' => $_SESSION['days'] ?? null,
            'dob' => $_SESSION['dob'] ?? null,
            'for_who_app' => $_SESSION["for_who_app"] ?? null,
            'appointment_type' => $_SESSION["app_type"] ?? '',
            'providers_ids' => $_SESSION["providers_ids"] ?? null,
            'full_time' => $_SESSION["full_time"] ?? null,
            'operatory_id' => $_SESSION["operatory_id"] ?? null,
            'day' => $_SESSION["day"] ?? null,
            'time' => $_SESSION["time"] ?? null,
            'existing_patient' => $_SESSION["existing_patient"] ?? false,
            'providers_arr' => $_SESSION['doctors_arr'] ?? null,
            'insurance' => $_SESSION["insurance"] ?? null,
            'appointment' => $_SESSION["appointment"] ?? null,
            'parentGuardian' => $_SESSION["parentGuardian"] ?? null,
            'guardian' => $_SESSION["guardian"] ?? null,
            'provider' => $_SESSION["provider"] ?? null,
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
        $_SESSION['step'] = $step;
        $after_edit = ($post_data['after_edit'] == 'true') ? true : false;
        /* Step 1 */
        if ($step == 1) {
            $fname = $post_data['fname'];
            $lname = $post_data['lname'];
            $email = $post_data['email'];
            $phone = $post_data['phone'];
            
            
            $existed_patient = $post_data['existed_patient'] === 'true' ? true : false;
            $_SESSION["existing_patient"] = $existed_patient;
            $patient = new Patient();
            $patient->setFirstName($fname);
            $patient->setLastName($lname);
            $patient->setEmail($email);
            $patient->setPhoneNumber($phone);
           
            //var_dump($existed_patient);
            if ($existed_patient) {
                
                
                $ext_pat_data = findExtPatientId($patient);
                if(is_string($ext_pat_data)){
                    var_dump($ext_pat_data);
                    $response = [
                        'status' => 'error',
                        'message' => 'Patient with this data not found. Please check the data and try again.',
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
               
                $_SESSION['patient_id'] = $ext_pat_data->id;
                $patient_data = findExtPatient($ext_pat_data->id);
                $patient->id = $ext_pat_data->id;
                //var_dump($patient_data);
                $doctors_arr = getDoctors($patient_data);
                $_SESSION['doctors_arr'] = $doctors_arr;
                
                $response = [
                    'status' => 'success',
                    'message' => 'Existing patient',
                    
                ];
                if($after_edit){
                    $_SESSION['editing'] = !$after_edit;
                    $response = ['redirect' => 'index.php?step=5'];
                   
                }
                //var_dump($response);
            } else {
                
                $dob = $post_data['dob'];
                $_SESSION['dob'] = $dob;
                $patient->setDateOfBirth($dob);
                
                $response = [
                    'status' => 'success',
                    'message' => 'New patient',
                ];
                if($after_edit){
                    $_SESSION['editing'] = !$after_edit;
                    $response = ['redirect' => 'index.php?step=5'];
                    
                }
            }
            $_SESSION['patient'] = $patient;
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
            if($after_edit){
                $_SESSION['editing'] = !$after_edit;
                header('Location: index.php?step=3');
                
            }
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
            $_SESSION['provider'] = getChusenProvider($_SESSION['doctors_arr'], $_SESSION['pid'][0]);
            $response = [
                'status' => 'success',
                'message' => 'Step 3',
            ];
            if($after_edit){
                $_SESSION['editing'] = false;
                $response = ['redirect' => 'index.php?step=5'];
                
            }
        } else if($step == 4){
            $insurance = $post_data['insurance'] ?? null;
            $appointment = $post_data['appointment'] ?? null;
            if(!is_null($appointment)){
                $for_who_app = $appointment;
                $_SESSION["for_who_app"] = $for_who_app;
                if($for_who_app == 'someoneElse'){
                    $fname = $post_data['fname'] ?? null;
                    $lname = $post_data['lname'] ?? null;
                    $dob = $post_data['dob'] ?? null;
                    $patient = new Patient();
                    $guardian = $_SESSION['patient'];
                    $patient->first_name = $fname;
                    $patient->last_name = $lname;
                    $patient->date_of_birth = $dob;
                    $patient->setPhoneNumber($guardian->phone_number);
                    $patient->setEmail($guardian->email);
                    $patient->provider_id = $guardian->provider_id;
                    $parentGuardian = $post_data['parentGuardian'] ?? null;
                    if(!is_null($parentGuardian) && $parentGuardian == 'yes'){
                        $_SESSION["parentGuardian"] = true;
                    } else {
                        $_SESSION["parentGuardian"] = false;
                    }
                    $_SESSION['guardian'] = $guardian;
                    $_SESSION['patient'] = $patient;
                    
                }
            }
            
            $_SESSION["insurance"] = $insurance;
            $_SESSION["appointment"] = $appointment;
           
            
            $response = [
                'status' => 'success',
                'message' => 'Step 4',
            ];
            if($after_edit){
                $_SESSION['editing'] = !$after_edit;
                $response = ['redirect' => 'index.php?step=5'];
                
            }
        } else if($step == 'destroy_session'){
            session_unset();
            session_destroy();
            $response = [
                'status' => 'success',
                'message' => 'Step 5',
            ];
        
        } else if ($step == 'confirm_appointment') {
            if(!$_SESSION["existing_patient"]){
                if($_SESSION["appointment"] == 'someoneElse' && $_SESSION["parentGuardian"]){
                    
                    $_SESSION['patient']->provider_id = $_SESSION["pid"][0];
                    $new_pat = createPatient($_SESSION['patient']);
                    //var_dump($new_pat);
                    if(is_array($new_pat)){
                        $idStartPos = strpos($new_pat[0], 'id=') + 3;
                        $id = substr($new_pat[0], $idStartPos);
                        $id = intval($id);
                        $_SESSION['patient_id'] = $id;
                    } else {
                        $_SESSION['patient_id'] = $new_pat->id;
                    }
                } else {
                    $_SESSION['patient']->provider_id = $_SESSION["pid"][0];
                    $new_pat = createPatient($_SESSION['patient']);
                    var_dump($new_pat);
                    //var_dump($new_pat);
                    if(is_array($new_pat)){
                        $idStartPos = strpos($new_pat[0], 'id=') + 3;
                        $id = substr($new_pat[0], $idStartPos);
                        $id = intval($id);
                        $_SESSION['patient_id'] = $id;
                    } else {
                        $_SESSION['patient_id'] = $new_pat->id;
                    }
                }
                
            } else {
                if($_SESSION["appointment"] == 'someoneElse' && $_SESSION["parentGuardian"]){
                    $_SESSION['patient_id'] = $_SESSION['guardian']->id;
                    
                } else {
                    $_SESSION['patient_id'] = $_SESSION['patient']->id;
                
                }
            }
            $comment = $post_data['comment'] ?? '';
            $_SESSION['comment'] = $comment;
            $appointment = new Appointment();
            if($_SESSION["appointment"] == 'someoneElse'){
                $appointment->for_else_people = true;
            }
            $appointment->patient_id = $_SESSION['patient_id'];
            $appointment->provider_id = $_SESSION['pid'][0];
            $appointment->operatory_id = $_SESSION['operatory_id'][0];
            $appointment->start_date = $_SESSION['full_time'];
            $appointment->appointment_type_id = $_SESSION['app_type_id'];
            $appointment->note = $comment;
            $appointment->is_new_clients_patient = !$_SESSION["existing_patient"];
            $appointment->is_guardian = $_SESSION["parentGuardian"];
            $appointment->patient = $_SESSION['patient'];
            //$res = createAppointment($_SESSION['pid'][0], $_SESSION['patient_id'], $_SESSION['operatory_id'][0], $_SESSION['full_time'],$_SESSION['app_type_id']);
            $res = createAppointment($appointment);
            //var_dump($res);
            //die();
            $response = [
                'status' => 'success',
                'message' => 'Step 4',
                'data' => $res,
            ];
        } else if ($step == 'filter_dates') {
            $date = $post_data['date'];
            $startDateAndDays = getStartDateAndDays($date);
            $_SESSION['start_date'] = $startDateAndDays[0];
            $_SESSION['end_date'] = $startDateAndDays[2];
            if($_SESSION['start_date'] == $_SESSION['end_date']){
                $_SESSION['days'] = 1;
            } else {
                $_SESSION['days'] = $startDateAndDays[1];
            }
            $filter_days = $post_data['days'] ?? null;
            $filter_hours = $post_data['times'] ?? null;
            if($filter_hours == 'all'){
                $filter_hours = null;
            }
            
            $_SESSION['filter_days'] = $filter_days;
            $_SESSION['filter_hours'] = $filter_hours;
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
            //$ext_pat = $_SESSION["existing_patient"];
            //getSlots($ext_pat, $_SESSION['start_date'] ?? null, $_SESSION['days'] ?? null, $_SESSION['filter_days'] ?? null, $_SESSION['filter_hours'] ?? null, $show_type);
            $response = [
                'status' => 'success',
                'message' => 'Display type changed',
            ];
        } else if($step == 'edit_step') {
            $step = $post_data['step_to_edit'];
            $_SESSION['editing'] = true;
            
            $response = ['redirect' => 'index.php?step='.$step];
           
        } 
        else {
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

    $startDate = DateTime::createFromFormat('Y-m-d', $startDateString);
    $endDate = DateTime::createFromFormat('Y-m-d', $endDateString);

    $startDateFormatted = $startDate->format('Y-m-d');
    $endDateFormatted = $endDate->format('Y-m-d');

    $interval = $startDate->diff($endDate);
    $days = $interval->days;
    $_SESSION['days'] = $days;
    return [$startDateFormatted, $days, $endDateFormatted];
}

function getSlots($ext_pat, $startDate = null, $Days = null, $filter_days = null, $filter_hours = null, $show_type = false){
    if(!$ext_pat){
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
    } else {
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
        $slots_all = getAppointmentSlots($ids, $_SESSION['app_type_id'], $startDate, $Days);
        
        //var_dump($slots);
        $slots_to_view_all = getDayTimeSlots($slots_all, $filter_days, $filter_hours);
        ksort($slots_to_view_all);
        //var_dump($slots_to_view); 
        
        $_SESSION["slots"] = $slots_to_view_all;
        return [$providers, $slots_to_view_all];
       
    }
    
}
function getChusenProvider($providers, $pid){
    foreach ($providers as $provider) {
        if($provider->id == $pid){
            return $provider;
        }
    }
}
