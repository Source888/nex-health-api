<?php
require_once 'Patient.php';
class Appointment {
    public $id;
    public $patient_id;
    public $provider_id;
    public $operatory_id;
    public $start_date;
    public $appointment_type_id;
    public $note;
    public $is_new_clients_patient;
    public Patient $patient;
    public $is_guardian = false;


    public function __construct($data = null){
        if($data){
            $this->id = $data['id'];
            $this->patient_id = $data['patient_id'];
            $this->provider_id = $data['provider_id'];
            $this->operatory_id = $data['operatory_id'];
            $this->start_date = $data['start_date'];
            $this->appointment_type_id = $data['appointment_type_id'];
            $this->note = $data['note'];
            $this->is_new_clients_patient = $data['is_new_clients_patient'];
            $this->patient = new Patient($data);
        }
    }

    public function getPostData(){
        $data = [];
        $date = new DateTime($this->start_date);
        $date->add(new DateInterval('PT30M'));
        $end_date_time = $date->format('Y-m-d\TH:i:s.vP');
        if($this->is_guardian === true && is_object($this->patient)){
            $data['patient_id'] = $this->patient_id;
            $data['provider_id'] = $this->provider_id;
            $data['operatory_id'] = $this->operatory_id;
            $data['start_time'] = $this->start_date;
            $data['end_time'] = $end_date_time;
            $data['note'] = $this->note;
            $data['is_new_clients_patient'] = $this->is_new_clients_patient;
            $data['is_guardian'] = $this->is_guardian;
            $data['patient'] = [
                'first_name' => $this->patient->first_name,
                'last_name' => $this->patient->last_name,
                'bio' => [
                    'date_of_birth' => $this->patient->date_of_birth,
                ]
            ];
        } else {
            $data['patient_id'] = $this->patient_id;
            $data['provider_id'] = $this->provider_id;
            $data['operatory_id'] = $this->operatory_id;
            $data['start_time'] = $this->start_date;
            $data['end_time'] = $end_date_time;
            $data['note'] = $this->note;
            $data['is_new_clients_patient'] = $this->is_new_clients_patient;
        }

        if(isset($this->appointment_type_id)){
            $data['appointment_type_id'] = $this->appointment_type_id;
        }
        return $data;
    }
}