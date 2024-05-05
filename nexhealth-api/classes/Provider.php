<?php

class Provider {
    public $id;
    public $email;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $name;
    public $created_at;
    public $updated_at;
    public $institution_id;
    public $foreign_id;
    public $foreign_id_type;
    public $bio = [
        "phone_number" => "",
        "cell_phone_number" => "",
        "home_phone_number" => ""
    ];
    public $inactive;
    public $last_sync_time;
    public $display_name;
    public $npi;
    public $nexhealth_specialty;
    public $profile_url;
    public $provider_requestables;
    public $availabilities;
    public $appointment_slots;
    public $last_visited;

    public function __construct($id = null, $email = null, $first_name = null, $middle_name = null, $last_name = null, $name = null, $created_at = null, $updated_at = null, $institution_id = null, $foreign_id = null, $foreign_id_type = null, $bio = null, $inactive = null, $last_sync_time = null, $display_name = null, $npi = null, $nexhealth_specialty = null, $profile_url = null, $provider_requestables = null, $availabilities = null, $appointment_slots = null, $last_visited = false)
    {
        $this->id = $id;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->institution_id = $institution_id;
        $this->foreign_id = $foreign_id;
        $this->foreign_id_type = $foreign_id_type;
        $this->bio = $bio;
        $this->inactive = $inactive;
        $this->last_sync_time = $last_sync_time;
        $this->display_name = $display_name;
        $this->npi = $npi;
        $this->nexhealth_specialty = $nexhealth_specialty;
        $this->profile_url = $profile_url;
        $this->provider_requestables = $provider_requestables;
        $this->availabilities = $availabilities;
        $this->appointment_slots = $appointment_slots;
        $this->last_visited = $last_visited;
    }

    public static function setThisAppointmentSlots($json){
        $appointment_slots = [];
        foreach ($json as $slot) {
            $appointment_slots[] = array(
                'operatory_id' => $slot['operatory_id'],
                'full_time' => $slot['full_time'],
                'pid' => $slot['pid'],
                'time' => $slot['time']
            );
        }
        return $appointment_slots;

    }
    
}