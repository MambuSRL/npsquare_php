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

namespace npsquare_php;
class VatRates
{

    private string $id;
    private string $description;
    private float $rate;

    public function __construct(
        string $id = '',
        string $description = '',
        float $rate = 0.0
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->rate = $rate;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['description'] ?? '',
            $data['rate'] ?? 0.0
        );
    }

    // Metodo statico per creare l'oggetto da JSON string
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        return self::fromArray($data);
    }

    // Metodo per convertire l'oggetto in array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'rate' => $this->rate
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter per Id
    public function getId(): string
    {
        return $this->id;
    }

    // Setter per Id
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    // Getter per Description
    public function getDescription(): string
    {
        return $this->description;
    }

    // Setter per Description
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    // Getter per Rate
    public function getRate(): float
    {
        return $this->rate;
    }

    // Setter per Rate
    public function setRate(float $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    // Metodo per validare i dati
    public function validate(): array
    {
        $errors = [];

        if (empty($this->id)) {
            $errors[] = 'Id is required';
        }

        if (empty($this->description)) {
            $errors[] = 'Description is required';
        }

        if ($this->rate < 0) {
            $errors[] = 'Rate cannot be negative';
        }

        return $errors;
    }

    // Metodo per verificare se l'aliquota IVA è valida
    public function isValid(): bool
    {
        return empty($this->validate());
    }

    // Metodo per verificare se è esente IVA
    public function isExempt(): bool
    {
        return $this->rate == 0.0;
    }

    // Metodo per calcolare l'IVA su un importo
    public function calculateVat(float $amount): float
    {
        return ($amount * $this->rate) / 100;
    }

    // Metodo per ottenere l'aliquota in formato percentuale
    public function getRateFormatted(): string
    {
        return number_format($this->rate, 2) . '%';
    }

    // Metodo toString per rappresentazione
    public function __toString(): string
    {
        return $this->description . " ({$this->getRateFormatted()})";
    }
}