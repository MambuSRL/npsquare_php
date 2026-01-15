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

namespace Mambusrl\npsquare_php\ReferenceData;
class CostCenters
{
    private string $id;
    private string $description;

    public function __construct(
        string $id = 0,
        string $description = ''
    ) {
        $this->id = $id;
        $this->description = $description;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? 0,
            $data['description'] ?? ''
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
            'description' => $this->description
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

    // Metodo per validare i dati
    public function validate(): array
    {
        $errors = [];

        if ($this->id <= 0) {
            $errors[] = 'Id must be greater than 0';
        }

        if (empty($this->description)) {
            $errors[] = 'Description is required';
        }

        return $errors;
    }

    // Metodo per verificare se il metodo di pagamento è valido
    public function isValid(): bool
    {
        return empty($this->validate());
    }

    // Metodo per verificare se è contanti
    public function isCash(): bool
    {
        return stripos($this->description, 'CONTANTI') !== false;
    }

    // Metodo toString per rappresentazione
    public function __toString(): string
    {
        return $this->description . " (ID: {$this->id})";
    }
}