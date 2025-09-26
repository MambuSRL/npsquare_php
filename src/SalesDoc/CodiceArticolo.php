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

namespace Mambusrl\npsquare_php\SalesDoc;

class CodiceArticolo {
    
    private string $CodiceTipo;
    private string $CodiceValore;

    public function __construct(
        string $CodiceTipo = '',
        string $CodiceValore = ''
    ) {
        $this->CodiceTipo = $CodiceTipo;
        $this->CodiceValore = $CodiceValore;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self {
        return new self(
            $data['CodiceTipo'] ?? '',
            $data['CodiceValore'] ?? null
        );
    }

    // Metodo statico per creare l'oggetto da JSON string
    public static function fromJson(string $json): self {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }
        
        return self::fromArray($data);
    }

    // Metodo per convertire l'oggetto in array
    public function toArray(): array {
        return [
            'CodiceTipo' => $this->CodiceTipo,
            'CodiceValore' => $this->CodiceValore
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter e Setter per CodiceTipo
    public function getCodiceTipo(): string {
        return $this->CodiceTipo;
    }

    public function setCodiceTipo(string $CodiceTipo): self {
        $this->CodiceTipo = $CodiceTipo;
        return $this;
    }

    // Getter e Setter per CodiceValore
    public function getCodiceValore(): string {
        return $this->CodiceValore;
    }

    public function setCodiceValore(string $CodiceValore): self {
        $this->CodiceValore = $CodiceValore;
        return $this;
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];

        if (empty($this->CodiceTipo)) {
            $errors[] = 'CodiceTipo is required';
        }
        if (empty($this->CodiceValore)) {
            $errors[] = 'CodiceValore is required';
        }

        return $errors;
    }

    // Metodo per verificare se il dato è valido
    public function isValid(): bool {
        return empty($this->validate());
    }

    // Metodo toString per rappresentazione
    public function __toString(): string {
        return $this->CodiceTipo .  ": " . $this->CodiceValore;
    }
}