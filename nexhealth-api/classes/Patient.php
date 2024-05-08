<?php
class Patient {
    public $id;
    public $provider_id;
    public $first_name;
    public $last_name;
    public $email;
    public $date_of_birth;
    public $phone_number;
    public $home_phone_number;
    public $cell_phone_number;
    public $work_phone_number;
    public $custom_contact_number;
    public $gender;
    public $weight;
    public $height;
    public $street_address;
    public $address_line_1;
    public $address_line_2;
    public $city;
    public $state;
    public $zip_code;
    public $insurance_name;
    public $ssn;
    public $race;
    public $last_visited_provider_id;

    // Add your methods here

    public function toJson() {
        return json_encode([
            'provider' => ['provider_id' => $this->provider_id],
            'patient' => [
                'bio' => [
                    'home_phone_number' => $this->home_phone_number,
                    'date_of_birth' => $this->date_of_birth,
                    'phone_number' => $this->phone_number,
                    'cell_phone_number' => $this->cell_phone_number,
                    'work_phone_number' => $this->work_phone_number,
                    'custom_contact_number' => $this->custom_contact_number,
                    'gender' => $this->gender,
                    'weight' => $this->weight,
                    'height' => $this->height,
                    'street_address' => $this->street_address,
                    'address_line_1' => $this->address_line_1,
                    'address_line_2' => $this->address_line_2,
                    'city' => $this->city,
                    'state' => $this->state,
                    'zip_code' => $this->zip_code,
                    'insurance_name' => $this->insurance_name,
                    'ssn' => $this->ssn,
                    'race' => $this->race
                ],
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email
            ]
        ]);
    }

public function __construct($provider_id = null, $first_name = null, $last_name = null, $email = null, $date_of_birth = null, $phone_number = null, $home_phone_number = null, $cell_phone_number = null, $work_phone_number = null, $custom_contact_number = null, $gender = null, $weight = null, $height = null, $street_address = null, $address_line_1 = null, $address_line_2 = null, $city = null, $state = null, $zip_code = null, $insurance_name = null, $ssn = null, $race = null) {
    $this->provider_id = $provider_id;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->date_of_birth = $date_of_birth;
    $this->phone_number = $phone_number;
    $this->home_phone_number = $home_phone_number;
    $this->cell_phone_number = $cell_phone_number;
    $this->work_phone_number = $work_phone_number;
    $this->custom_contact_number = $custom_contact_number;
    $this->gender = $gender;
    $this->weight = $weight;
    $this->height = $height;
    $this->street_address = $street_address;
    $this->address_line_1 = $address_line_1;
    $this->address_line_2 = $address_line_2;
    $this->city = $city;
    $this->state = $state;
    $this->zip_code = $zip_code;
    $this->insurance_name = $insurance_name;
    $this->ssn = $ssn;
    $this->race = $race;
}

// Getters and Setters
public function getProviderId() {
    return $this->provider_id;
}

public function setProviderId($provider_id) {
    $this->provider_id = $provider_id;
}

// Repeat the above getter and setter methods for all the other properties
public function getFirstName() {
    return $this->first_name;
}

public function setFirstName($first_name) {
    $this->first_name = $first_name;
}

public function getLastName() {
    return $this->last_name;
}

public function setLastName($last_name) {
    $this->last_name = $last_name;
}

// Repeat the above getter and setter methods for all the other properties

public function getEmail() {
    return $this->email;
}

public function setEmail($email) {
    $this->email = $email;
}

public function getDateOfBirth() {
    return $this->date_of_birth;
}

public function setDateOfBirth($date_of_birth) {
    $date = new DateTime($date_of_birth);
    $formated_date = $date->format('Y-m-d');
    $this->date_of_birth = $formated_date;
}

public function getPhoneNumber() {
    return $this->phone_number;
}

public function setPhoneNumber($phone_number) {
    $this->phone_number = $phone_number;
}

public function getHomePhoneNumber() {
    return $this->home_phone_number;
}

public function setHomePhoneNumber($home_phone_number) {
    $this->home_phone_number = $home_phone_number;  
}

public function getCellPhoneNumber() {
    return $this->cell_phone_number;
}

public function setCellPhoneNumber($cell_phone_number) {
    $this->cell_phone_number = $cell_phone_number;
}

public function getWorkPhoneNumber() {
    return $this->work_phone_number;
}

public function setWorkPhoneNumber($work_phone_number) {
    $this->work_phone_number = $work_phone_number;

}

public function getCustomContactNumber() {
    return $this->custom_contact_number;
}

public function setCustomContactNumber($custom_contact_number) {
    $this->custom_contact_number = $custom_contact_number;
}

public function getGender() {
    return $this->gender;
}

public function setGender($gender) {
    $this->gender = $gender;
}

public function getWeight() {
    return $this->weight;
}

public function setWeight($weight) {
    $this->weight = $weight;
}

public function getHeight() {
    return $this->height;
}

public function setHeight($height) {
    $this->height = $height;
}

public function getStreetAddress() {
    return $this->street_address;
}

public function setStreetAddress($street_address) {
    $this->street_address = $street_address;
}

public function getAddressLine1() {
    return $this->address_line_1;
}

public function setAddressLine1($address_line_1) {
    $this->address_line_1 = $address_line_1;
}

public function getAddressLine2() {
    return $this->address_line_2;
}

public function setAddressLine2($address_line_2) {
    $this->address_line_2 = $address_line_2;
}

public function getCity() {
    return $this->city;
}

public function setCity($city) {
    $this->city = $city;
}

public function getState() {
    return $this->state;
}

public function setState($state) {
    $this->state = $state;
}

public function getZipCode() {
    return $this->zip_code;

}

public function setZipCode($zip_code) {
    $this->zip_code = $zip_code;
}

public function getInsuranceName() {
    return $this->insurance_name;
}

public function setInsuranceName($insurance_name) {
    $this->insurance_name = $insurance_name;
}

public function getSsn() {
    return $this->ssn;
}

public function setSsn($ssn) {
    $this->ssn = $ssn;
}

public function getRace() {
    return
    $this->race;
}

public function setRace($race) {
    $this->race = $race;
}



}