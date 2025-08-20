<?php
/**
 * MIT License
 * 
 * Copyright (c) 2025 Francesco Picciati
 * 
 * This source code is licensed under the MIT license
 * found in the LICENSE file in the root directory of this source tree.
 */
declare(strict_types=1);

namespace Mambusrl\npsquare_php;
final class NPSquare {

    private ?string $accessToken = null;
    private ?string $keyIstitution = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $url = null;
    private bool $access_token = false;

    public function __construct(string $keyIstitution = '', string $username = '', string $password = '', string $url = '') {
        $this->keyIstitution = $keyIstitution;
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
    }

    public function disconnect(): string{
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/logout',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        switch ($code){
            case 204:
                return "Disconnected successfully";
            case 403:
                throw new \Exception("Forbidden");
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function connect(): string{
        if (empty($this->keyIstitution)) 
            throw new \Exception("Missing key institution");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query(array(
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => $this->keyIstitution
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        switch ($code){
            case 200:
                $this->access_token = json_decode($response)->access_token;
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function get_payment_method(): mixed{
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/reference-data/payment-methods?keyInstitution=' . $this->keyIstitution . '&page=1&size=100',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->accessToken
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        switch ($code){
            case 200:
                $data = json_decode($response, true);
                $paymentMethods = [];
                
                foreach ($data['items'] as $item) {
                    $paymentMethods[] = ReferenceData\PaymentMethods::fromArray($item);
                }
                
                return $paymentMethods;
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function get_vat_rates(): mixed{
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/reference-data/vat-rates?keyInstitution=' . $this->keyIstitution . '&page=1&size=100',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->accessToken
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        switch ($code){
            case 200:
                $data = json_decode($response, true);
                $vatRates = [];
                
                foreach ($data['items'] as $item) {
                    $vatRates[] = ReferenceData\VatRates::fromArray($item);
                }
                
                return $vatRates;
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function sendSalesDoc(salesDoc $doc){
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/documents/sales?keyInstitution='.$this->keyIstitution,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($doc->toArray()),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessToken
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        switch ($code) {
            case 200:
                return json_decode($response);
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }
}