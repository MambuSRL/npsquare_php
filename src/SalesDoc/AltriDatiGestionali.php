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

class AltriDatiGestionali {
    
    private string $tipoDato;
    private ?string $riferimentoTesto;
    private ?float $riferimentoNumero;
    private ?string $riferimentoData;

    public function __construct(
        string $tipoDato = '',
        ?string $riferimentoTesto = null,
        ?float $riferimentoNumero = null,
        ?string $riferimentoData = null
    ) {
        $this->tipoDato = $tipoDato;
        $this->riferimentoTesto = $riferimentoTesto;
        $this->riferimentoNumero = $riferimentoNumero;
        $this->riferimentoData = $riferimentoData;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self {
        return new self(
            $data['TipoDato'] ?? '',
            $data['RiferimentoTesto'] ?? null,
            $data['RiferimentoNumero'] ?? null,
            $data['RiferimentoData'] ?? null
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
            'TipoDato' => $this->tipoDato,
            'RiferimentoTesto' => $this->riferimentoTesto,
            'RiferimentoNumero' => $this->riferimentoNumero,
            'RiferimentoData' => $this->riferimentoData
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter e Setter per TipoDato
    public function getTipoDato(): string {
        return $this->tipoDato;
    }

    public function setTipoDato(string $tipoDato): self {
        $this->tipoDato = $tipoDato;
        return $this;
    }

    // Getter e Setter per RiferimentoTesto
    public function getRiferimentoTesto(): ?string {
        return $this->riferimentoTesto;
    }

    public function setRiferimentoTesto(?string $riferimentoTesto): self {
        $this->riferimentoTesto = $riferimentoTesto;
        return $this;
    }

    // Getter e Setter per RiferimentoNumero
    public function getRiferimentoNumero(): ?float {
        return $this->riferimentoNumero;
    }

    public function setRiferimentoNumero(?float $riferimentoNumero): self {
        $this->riferimentoNumero = $riferimentoNumero;
        return $this;
    }

    // Getter e Setter per RiferimentoData
    public function getRiferimentoData(): ?string {
        return $this->riferimentoData;
    }

    public function setRiferimentoData(?string $riferimentoData): self {
        $this->riferimentoData = $riferimentoData;
        return $this;
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];

        if (empty($this->tipoDato)) {
            $errors[] = 'TipoDato is required';
        }

        // Validazione formato data se presente
        if ($this->riferimentoData !== null && !$this->isValidDate($this->riferimentoData)) {
            $errors[] = 'RiferimentoData must be a valid date format (Y-m-d or Y-m-d H:i:s)';
        }

        return $errors;
    }

    // Metodo per verificare se il dato è valido
    public function isValid(): bool {
        return empty($this->validate());
    }

    // Metodo helper per validare il formato data
    private function isValidDate(string $date): bool {
        $formats = ['Y-m-d', 'Y-m-d H:i:s', 'd/m/Y', 'd-m-Y'];
        
        foreach ($formats as $format) {
            $dateTime = \DateTime::createFromFormat($format, $date);
            if ($dateTime && $dateTime->format($format) === $date) {
                return true;
            }
        }
        
        return false;
    }

    // Metodo per ottenere il tipo di riferimento utilizzato
    public function getTipoRiferimento(): ?string {
        if ($this->riferimentoTesto !== null) {
            return 'testo';
        } elseif ($this->riferimentoNumero !== null) {
            return 'numero';
        } elseif ($this->riferimentoData !== null) {
            return 'data';
        }
        
        return null;
    }

    // Metodo per ottenere il valore del riferimento indipendentemente dal tipo
    public function getValoreRiferimento(): mixed {
        if ($this->riferimentoTesto !== null) {
            return $this->riferimentoTesto;
        } elseif ($this->riferimentoNumero !== null) {
            return $this->riferimentoNumero;
        } elseif ($this->riferimentoData !== null) {
            return $this->riferimentoData;
        }
        return null;
    }
}