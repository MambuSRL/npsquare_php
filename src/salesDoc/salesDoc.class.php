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
class salesDoc {
    
    private string $type;
    private string $date;
    private ?int $paymentId;
    private ?int $paymentMethodId;
    private array $productItems;
    private Stakeholder $stakeholder;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->fromArray($data);
        }
    }

    // Metodo per creare l'oggetto da array/JSON
    public function fromArray(array $data): self {
        $this->type = $data['Type'] ?? '';
        $this->date = $data['Date'] ?? '';
        $this->paymentId = $data['PaymentId'] ?? null;
        $this->paymentMethodId = $data['PaymentMethodId'] ?? null;
        $this->productItems = [];
        foreach($data["ProductItems"] as $item) {
            $this->productItems[] = ProductItem::fromArray($item);
        }
        $this->stakeholder = Stakeholder::fromArray($data["Stakeholder"]);
        return $this;
    }

    // Metodo per creare l'oggetto da JSON string
    public function fromJson(string $json): self {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }
        
        return $this->fromArray($data);
    }

    // Metodo per convertire l'oggetto in array
    public function toArray(): array {
        $productItemsArray = [];
        foreach ($this->productItems as $item) {
            $productItemsArray[] = $item->toArray();
        }
        return [
            'Type' => $this->type,
            'Date' => $this->date,
            'PaymentId' => $this->paymentId,
            'PaymentMethodId' => $this->paymentMethodId,
            'ProductItems' => $productItemsArray,
            'Stakeholder' => $this->stakeholder->toArray()
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter e Setter per Type
    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    // Getter e Setter per Date
    public function getDate(): string {
        return $this->date;
    }

    public function setDate(string $date): self {
        $this->date = $date;
        return $this;
    }

    // Getter e Setter per PaymentId
    public function getPaymentId(): ?int {
        return $this->paymentId;
    }

    public function setPaymentId(?int $paymentId): self {
        $this->paymentId = $paymentId;
        return $this;
    }

    // Getter e Setter per PaymentMethodId
    public function getPaymentMethodId(): ?int {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $paymentMethodId): self {
        $this->paymentMethodId = $paymentMethodId;
        return $this;
    }

    // Getter e Setter per ProductItems
    public function getProductItems(): array {
        return $this->productItems;
    }

    public function setProductItems(array $productItems): self {
        $this->productItems = $productItems;
        return $this;
    }

    // Metodo per aggiungere un prodotto
    public function addProductItem(ProductItem $productItem): self {
        $this->productItems[] = $productItem;
        return $this;
    }

    // Getter e Setter per Stakeholder
    public function getStakeholder(): Stakeholder {
        return $this->stakeholder;
    }

    public function setStakeholder(Stakeholder $stakeholder): self {
        $this->stakeholder = $stakeholder;
        return $this;
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];

        if (empty($this->type)) {
            $errors[] = 'Type is required';
        }

        if (empty($this->date)) {
            $errors[] = 'Date is required';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->date)) {
            $errors[] = 'Date must be in YYYY-MM-DD format';
        }

        if (empty($this->productItems)) {
            $errors[] = 'At least one product item is required';
        }

        if (is_null($this->stakeholder)) {
            $errors[] = 'Stakeholder is required';
        }

        return $errors;
    }

    // Metodo per verificare se il documento è valido
    public function isValid(): bool {
        return empty($this->validate());
    }
}