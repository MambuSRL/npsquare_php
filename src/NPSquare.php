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

    private string $access_token = "";
    private ?string $keyInstitution = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $url = null;

    public function __construct(string $keyInstitution = '', string $username = '', string $password = '', string $url = '') {
        $this->keyInstitution = $keyInstitution;
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
            CURLOPT_SSL_VERIFYPEER => false,
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

    public function testConnection(): bool|string{
        try{
            $out = $this->connect();
            if ($out === false){
                return "Connection failed";
            }
            $this->disconnect();
            return true;
        } catch (\Exception $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function connect(): string{
        if (empty($this->keyInstitution)) 
            throw new \Exception("Missing key institution");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query(array(
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => $this->keyInstitution
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
                return $this->access_token;
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
            CURLOPT_URL => $this->url . '/reference-data/payment-methods?keyInstitution=' . $this->keyInstitution . '&page=1&size=9999',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
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

    public function get_cost_centers(): mixed{
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/reference-data/cost-centers?keyInstitution=' . $this->keyInstitution . '&page=1&size=9999',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        switch ($code){
            case 200:
                $data = json_decode($response, true);
                $costCenters = [];
                
                foreach ($data['items'] as $item) {
                    $costCenters[] = ReferenceData\CostCenters::fromArray($item);
                }
                
                return $costCenters;
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function get_sub_accounts(): mixed{
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/reference-data/sub-accounts?keyInstitution=' . $this->keyInstitution . '&page=1&size=9999',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        switch ($code){
            case 200:
                $data = json_decode($response, true);
                $subAccounts = [];
                
                foreach ($data['items'] as $item) {
                    $subAccounts[] = ReferenceData\SubAccounts::fromArray($item);
                }
                
                return $subAccounts;
            case 401:
                throw new \Exception("Unauthorized");
            default:
                throw new \Exception("Unexpected error");
        }
    }

    public function get_document_types(): mixed{
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/documents/types?keyInstitution=' . $this->keyInstitution . '&page=1&size=9999',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        switch ($code){
            case 200:
                $data = json_decode($response, true);
                $documentTypes = [];
                
                foreach ($data['items'] as $item) {
                    $documentTypes[] = ReferenceData\DocumentTypes::fromArray($item);
                }
                
                return $documentTypes;
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
            CURLOPT_URL => $this->url . '/reference-data/vat-rates?keyInstitution=' . $this->keyInstitution . '&page=1&size=9999',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->access_token
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

    public function sendSalesDoc(SalesDoc\SalesDoc $doc){
        if (empty($this->access_token)) 
            throw new \Exception("Missing access token");

        // Validazione locale del documento prima dell'invio
        if (!$doc->isValid()) {
            $localErrors = $doc->validate();
            throw new Exceptions\ValidationException(
                "Document validation failed locally",
                array_map(function($error) {
                    return [
                        'loc' => ['local_validation'],
                        'msg' => $error,
                        'type' => 'local_validation_error'
                    ];
                }, $localErrors),
                json_encode(['local_errors' => $localErrors])
            );
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url . '/documents/sales?keyInstitution='.$this->keyInstitution,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($doc->toArray()),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->access_token
            ),
        ));

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        switch ($code) {
            case 200:
            case 201:
                return json_decode($response);
            case 401:
                throw new \Exception("Unauthorized");
            case 404:
                throw new \Exception("Not Found");
            case 422:
                return $this->handleValidationError($response);
            default:
                throw new \Exception("Unexpected error (HTTP $code): $response");
        }
    }

    private function handleValidationError(string $response): void
    {
        $responseData = json_decode($response, true);
        
        if (!$responseData || !isset($responseData['detail'])) {
            throw new Exceptions\ValidationException(
                "Validation error with invalid response format",
                [],
                $response
            );
        }

        $validationErrors = [];
        $errorMessages = [];

        foreach ($responseData['detail'] as $error) {
            $validationErrors[] = [
                'loc' => $error['loc'] ?? ['unknown'],
                'msg' => $error['msg'] ?? 'Unknown error',
                'type' => $error['type'] ?? 'unknown_type'
            ];
            
            $location = is_array($error['loc']) ? implode(' -> ', $error['loc']) : $error['loc'];
            $errorMessages[] = "Field '{$location}': " . ($error['msg'] ?? 'Unknown error');
        }

        $mainMessage = "Server validation failed with " . count($validationErrors) . " error(s):\n" . implode("\n", $errorMessages);

        throw new Exceptions\ValidationException(
            $mainMessage,
            $validationErrors,
            $response
        );
    }
}