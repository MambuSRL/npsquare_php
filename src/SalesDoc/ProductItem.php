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
class ProductItem {
    
    private int $productQuantity;
    private string $productDescription;
    private float $unitProductPrice;
    private string $productVatRateCode;
    private float $productDiscount;
    private ?string $codiceSottoconto;
    private ?string $codiceCentroRicavo;
    private ?string $effectiveData;
    private ?string $codiceArticolo;
    private ?AltriDatiGestionali $altriDatiGestionali;

    public function __construct(
        int $productQuantity = 1,
        string $productDescription = '',
        float $unitProductPrice = 0.0,
        string $productVatRateCode = '',
        float $productDiscount = 0.0,
        ?string $codiceSottoconto = null,
        ?string $codiceCentroRicavo = null,
        ?string $effectiveData = null,
        ?string $codiceArticolo = null,
        ?AltriDatiGestionali $altriDatiGestionali = null
    ) {
        $this->productQuantity = $productQuantity;
        $this->productDescription = $productDescription;
        $this->unitProductPrice = $unitProductPrice;
        $this->productVatRateCode = $productVatRateCode;
        $this->productDiscount = $productDiscount;
        $this->codiceSottoconto = $codiceSottoconto;
        $this->codiceCentroRicavo = $codiceCentroRicavo;
        $this->effectiveData = $effectiveData;
        $this->codiceArticolo = $codiceArticolo;
        $this->altriDatiGestionali = $altriDatiGestionali;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self {
        return new self(
            $data['ProductQuantity'] ?? 1,
            $data['ProductDescription'] ?? '',
            $data['UnitProductPrice'] ?? 0.0,
            $data['ProductVatRateCode'] ?? '',
            $data['ProductDiscount'] ?? 0.0,
            $data['CodiceSottoconto'] ?? null,
            $data['CodiceCentroRicavo'] ?? null,
            $data['EffectiveData'] ?? null,
            $data['CodiceArticolo'] ?? null,
            isset($data['AltriDatiGestionali']) && is_array($data['AltriDatiGestionali']) ? AltriDatiGestionali::fromArray($data['AltriDatiGestionali']) : null
        );
    }

    // Metodo per convertire l'oggetto in array
    public function toArray(): array {
        return [
            'ProductQuantity' => $this->productQuantity,
            'ProductDescription' => $this->productDescription,
            'UnitProductPrice' => $this->unitProductPrice,
            'ProductVatRateCode' => $this->productVatRateCode,
            'ProductDiscount' => $this->productDiscount,
            'CodiceSottoconto' => $this->codiceSottoconto,
            'CodiceCentroRicavo' => $this->codiceCentroRicavo,
            'EffectiveData' => $this->effectiveData,
            'CodiceArticolo' => $this->codiceArticolo,
            'AltriDatiGestionali' => $this->altriDatiGestionali ? $this->altriDatiGestionali->toArray() : null
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter e Setter per ProductQuantity
    public function getProductQuantity(): int {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): self {
        $this->productQuantity = $productQuantity;
        return $this;
    }

    // Getter e Setter per ProductDescription
    public function getProductDescription(): string {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): self {
        $this->productDescription = $productDescription;
        return $this;
    }

    // Getter e Setter per UnitProductPrice
    public function getUnitProductPrice(): float {
        return $this->unitProductPrice;
    }

    public function setUnitProductPrice(float $unitProductPrice): self {
        $this->unitProductPrice = $unitProductPrice;
        return $this;
    }

    // Getter e Setter per ProductVatRateCode
    public function getProductVatRateCode(): string {
        return $this->productVatRateCode;
    }

    public function setProductVatRateCode(string $productVatRateCode): self {
        $this->productVatRateCode = $productVatRateCode;
        return $this;
    }

    // Getter e Setter per ProductDiscount
    public function getProductDiscount(): float {
        return $this->productDiscount;
    }

    public function setProductDiscount(float $productDiscount): self {
        $this->productDiscount = $productDiscount;
        return $this;
    }

    // Getter e Setter per CodiceSottoconto
    public function getCodiceSottoconto(): ?string {
        return $this->codiceSottoconto;
    }

    public function setCodiceSottoconto(?string $codiceSottoconto): self {
        $this->codiceSottoconto = $codiceSottoconto;
        return $this;
    }

    // Getter e Setter per CodiceCentroRicavo
    public function getCodiceCentroRicavo(): ?string {
        return $this->codiceCentroRicavo;
    }

    public function setCodiceCentroRicavo(?string $codiceCentroRicavo): self {
        $this->codiceCentroRicavo = $codiceCentroRicavo;
        return $this;
    }

    // Getter e Setter per EffectiveData
    public function getEffectiveData(): ?string {
        return $this->effectiveData;
    }

    public function setEffectiveData(?string $effectiveData): self {
        $this->effectiveData = $effectiveData;
        return $this;
    }

    // Getter e Setter per CodiceArticolo
    public function getCodiceArticolo(): ?string {
        return $this->codiceArticolo;
    }

    public function setCodiceArticolo(?string $codiceArticolo): self {
        $this->codiceArticolo = $codiceArticolo;
        return $this;
    }

    // Getter e Setter per AltriDatiGesionali
    public function getAltriDatiGesionali(): ?AltriDatiGestionali {
        return $this->altriDatiGestionali;
    }

    public function setAltriDatiGesionali(?AltriDatiGestionali $altriDatiGesionali): self {
        $this->altriDatiGestionali = $altriDatiGesionali;
        return $this;
    }

    // Metodi di calcolo
    public function getTotalPrice(): float {
        return $this->unitProductPrice * $this->productQuantity;
    }

    public function getDiscountAmount(): float {
        return ($this->getTotalPrice() * $this->productDiscount) / 100;
    }

    public function getTotalPriceAfterDiscount(): float {
        return $this->getTotalPrice() - $this->getDiscountAmount();
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];
        if ($this->productQuantity <= 0) {
            $errors[] = 'ProductQuantity must be greater than 0';
        }

        if (empty($this->productDescription)) {
            $errors[] = 'ProductDescription is required';
        }

        if ($this->unitProductPrice < 0) {
            $errors[] = 'UnitProductPrice cannot be negative';
        }

        if (empty($this->productVatRateCode)) {
            $errors[] = 'ProductVatRateCode is required';
        }

        if ($this->productDiscount < 0 || $this->productDiscount > 100) {
            $errors[] = 'ProductDiscount must be between 0 and 100';
        }
        return $errors;
    }

    // Metodo per verificare se il prodotto è valido
    public function isValid(): bool {
        return empty($this->validate());
    }
}