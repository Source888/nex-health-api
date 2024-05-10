<?php
require_once 'classes/Patient.php';
require_once 'classes/Provider.php';
$prod = false;
if($prod === true){
    $GLOBALS['subdomain'] = 'island-dental-associates';
    $GLOBALS['location_id'] = 19226;
    $GLOBALS['api_token'] = 'aXNsYW5kLWRlbnRhbC1hc3NvYw.qE4eYXuMGt5PiTW_LKEq6-y2Ngc-pRey';
} else {
    $GLOBALS['subdomain'] = 'island-dental-associates-sandbox';
    $GLOBALS['location_id'] = 119742;
    $GLOBALS['api_token'] = 'aXNsYW5kLWRlbnRhbC1hc3NvYy1zYW5kYm94.7cPCG59VSZbD0kKiX4_HRJA0V4ZWKiMy';
}

function getBearerToken() {

    $now = new DateTime();
    if (!file_exists('token.file')) {
        $token_date_time = file_get_contents('token.file');
        $token_date_time = explode("\n", $token_date_time);
    } else {
        $token_date_time = false;
    }
    
    
    $bearer_token = $token_date_time[0];
    $date_time = isset($token_date_time[1]) ? $token_date_time[1] : null;
    if(!$date_time || $now->diff(new DateTime($date_time))->m > 50) {
        $url = 'https://nexhealth.info/authenticates';
        
        $headers = array(
            'Accept: application/vnd.Nexhealth+json; version=2',
            'Content-Type: application/json',
            'Authorization: ' . $GLOBALS['api_token']
        );
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
    
        curl_close($ch);
    
        $responseArray = json_decode($response, true);
        // Get the current date and time
        $date = new DateTime();
        // Format it as a string
        $dateString = $date->format('Y-m-d H:i:s');
        // Add the date and time to the token file
        
    
        if (isset($responseArray['data']['token'])) {
            $bearer_token = $responseArray['data']['token'];
            file_put_contents('token.file', $bearer_token . "\n" . $dateString);
        } 
        return $bearer_token;
    } else {
        return $bearer_token;
    }
    
}
function getDoctors($patient = null) {
    $url = "https://nexhealth.info/providers?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&include[]=appointment_types&per_page=70";
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    if(!is_array($responseArray['error'])){
        $doctors_array = [];
        foreach($responseArray['data'] as $doctor){
            $provider = new Provider();
            $provider->id = $doctor['id'];
            $provider->first_name = $doctor['first_name'];
            $provider->last_name = $doctor['last_name'];
            $provider->email = $doctor['email'];
            $provider->bio['phone_number'] = $doctor['bio']['phone_number'];
            $provider->nexhealth_specialty = $doctor['nexhealth_specialty'];
            $provider->profile_url = $doctor['profile_url'];
            $provider->provider_requestables = $doctor['provider_requestables'];
            $provider->availabilities = $doctor['availabilities'];
            $provider->appointment_slots = $doctor['appointment_slots'];
            $provider->nexhealth_specialty = ($doctor['nexhealth_specialty'] == "Unknown") ? "" : $doctor['nexhealth_specialty'];
            if(!is_null($patient) && $patient->last_visited_provider_id == $provider->id){
                $provider->last_visited = true;
            } else {
                $provider->last_visited = false;
            }
            
            $doctors_array[] = $provider;

        }
        return $doctors_array;
    } else {
        return $responseArray['error'];
    }
}

function findExtPatientId($patient){
    if(!$patient->email || is_null($patient->email) || $patient->email == 'test@test.com') {
        $url = "https://nexhealth.info/patients?name=".$patient->first_name."%20".$patient->last_name."&new_patient=false&subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}";
    } else {
        $url = "https://nexhealth.info/patients?new_patient=false&subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&email=" . $patient->email;
    }
    
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    //var_dump($response);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    //var_dump($responseArray);
    if(!is_array($responseArray['error'])){
        $patient = new Patient();
        $user_arr = $responseArray['data']['patients'][0];
        $patient->id = $user_arr['id'];
        $patient->first_name = $user_arr['first_name'];
        $patient->last_name = $user_arr['last_name'];
        $patient->email = $user_arr['email'];
        $patient->phone_number = $user_arr['bio']['phone_number'];
        if(isset($user_arr['last_visited_appointmen']) && is_array($user_arr['last_visited_appointmen'])){
            $patient->last_visited_provider_id = $user_arr['last_visited_appointmen']['provider_id'];
        }
        return $patient;
    } else {
        return $responseArray['error'][0];
    }
}
function findExtPatient($patient_id){
    $url = "https://nexhealth.info/patients/{$patient_id}?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&include[]=last_visited_appointment";
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    //var_dump($response);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    //var_dump($responseArray);
    if(!is_array($responseArray['error'])){
        $patient = new Patient();
        $user_arr = $responseArray['data'];
        $patient->id = $user_arr['id'];
        $patient->first_name = $user_arr['first_name'];
        $patient->last_name = $user_arr['last_name'];
        $patient->email = $user_arr['email'];
        $patient->phone_number = $user_arr['bio']['phone_number'];
        if(isset($user_arr['last_visited_appointment']) && is_array($user_arr['last_visited_appointment'])){
            $patient->last_visited_provider_id = $user_arr['last_visited_appointment']['provider_id'];
        }
        return $patient;
    } else {
        return $responseArray['error'];
    }
}
function createPatient($patient){
    $url = "https://nexhealth.info/patients?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}";
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );

    $data = array(
        'patient' => array(
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'email' => $patient->email,
            
            'bio' => array(
                'phone_number' => $patient->phone_number,
                'date_of_birth' => $patient->date_of_birth
            ),
            
        ),
        
        'provider' => array(
            'provider_id' => $patient->provider_id
        ),
        
        
    );

    $data = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    
    $responseArray = json_decode($response, true);
    if(!is_array($responseArray['error'])){
        $patient = new Patient();
        $user_arr = $responseArray['data']['user'];
        $patient->id = $user_arr['id'];
        $patient->first_name = $user_arr['first_name'];
        $patient->last_name = $user_arr['last_name'];
        $patient->email = $user_arr['email'];
        $patient->phone_number = $user_arr['bio']['phone_number'];
        return $patient;
    } else {
        return $responseArray['error'];
    }

}
function getAppointmentSlots($pids, $appointment_type_id = null, $start_date = null, $days = null) {
    if(is_null($start_date)){
        $start_date = date('Y-m-d');
    }
    if(is_null($days)){
        $days = 7;
    }
    if(is_array($pids)){
        $providers_part = implode('&pids[]=', $pids);
    } else {
        $providers_part = '&pids[]='.$pids;
    }
    
    if(!is_null($appointment_type_id)){
        $appointment_type_id_part = "&appointment_type_id={$appointment_type_id}";
    } else {
        $appointment_type_id_part = '';
    }
    //var_dump($providers_part);
    $url = "https://nexhealth.info/appointment_slots?subdomain={$GLOBALS['subdomain']}&start_date={$start_date}&days={$days}&lids[]={$GLOBALS['location_id']}&pids[]={$providers_part}&overlapping_operatory_slots=false&slot_length=30{$appointment_type_id_part}";
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );

    

    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    if(!is_array($responseArray['error'])){
        return $responseArray['data'];
    } else {
        return $responseArray['error'];
    }
    return $responseArray;
}
function createAppointment($appointment){//($provider_id, $patient_id, $operatory_id, $start_time, $appointment_type_id = null){
    $url = "https://nexhealth.info/appointments?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&notify_patient=false";
    $token = getBearerToken();
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    );
   /* $date = new DateTime($appointment->start_date);
    $date->add(new DateInterval('PT30M'));
    $end_date_time = $date->format('Y-m-d\TH:i:s.vP');
    if(is_null($appointment_type_id)){
        $data = array(
            'appt' => array(
                'provider_id' => $provider_id,
                'patient_id' => $patient_id,
                'operatory_id' => $operatory_id,
                'start_time' => $start_time,
                'end_time' => $end_date_time,
                'is_new_clients_patient' => !$_SESSION["existing_patient"]
            )
        );
    } else {
        $data = array(
            'appt' => array(
                'provider_id' => $provider_id,
                'patient_id' => $patient_id,
                'operatory_id' => $operatory_id,
                'start_time' => $start_time,
                'end_time' => $end_date_time,
                'appointment_type_id' => $appointment_type_id,
                'is_new_clients_patient' => !$_SESSION["existing_patient"]
            )
        );
    }*/
    $data = $appointment->getPostData();

    $data = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    if(!is_array($responseArray['error'])){
        $aapt = $responseArray['data']['appt'];
        $appointment->id = $aapt['id'];
        return $appointment;
    } else {
        return $responseArray['error'][0];
    }
}
function getAppointmentTypeId($app_type){
    if($app_type == 'Cleaning' || $app_type == 'Emergency'){
        if ($_SESSION["existing_patient"]){
            $app_type = 'Existing Patient '.$app_type;
        } else {
            $app_type = 'New Patient '.$app_type;
        
        }
    }
    $url = "https://nexhealth.info/appointment_types?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&include[]=descriptors";
    $token = getBearerToken();
    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/vnd.Nexhealth+json;version=2',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    $responseArray = json_decode($response, true);
    if(!is_array($responseArray['error'])){
        $responseArray['data'];
        foreach($responseArray['data'] as $app){
            if($app['name'] == $app_type){
                return $app['id'];
            }
        }
    } else {
        return $responseArray['error'];
    }
}
function getAvailabilities($provider_id=null, $operatory_id=null) {
    if(!is_null($provider_id)){
        $provider_id_part = "&provider_id={$provider_id}";
    } else {
        $provider_id_part = '';
    }
    if(!is_null($operatory_id)){
        $operatory_id_part = "&operatory_id={$operatory_id}";
    } else {
        $operatory_id_part = '';
    }
    $token = getBearerToken();
    $url = "https://nexhealth.info/availabilities?subdomain={$GLOBALS['subdomain']}&location_id={$GLOBALS['location_id']}&page=1&include[]=appointment_types&per_page=5&active=true&ignore_past_dates=false".$provider_id_part.$operatory_id_part;
    $headers = array(
        'Accept: application/vnd.Nexhealth+json; version=2',
        'Content-Type: application/json',
        'Authorization: ' . $token
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $responseArray = json_decode($response, true);
    return $responseArray;
}
