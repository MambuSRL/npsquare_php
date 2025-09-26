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
class SalesDoc {
    
    private string $Type;
    private string $Date;
    private ?string $PaymentId;
    private ?int $PaymentMethodId;
    private ?float $StampDutyAmount;
    private ?string $Notes;
    private bool $IsPaid = false;
    private array $ProductItems;
    private Stakeholder $Stakeholder;
    private ?DatiAggiuntiviFatturazione $DatiOrdineAcquisto;
    private ?DatiAggiuntiviFatturazione $DatiContratto;
    private ?DatiAggiuntiviFatturazione $DatiConvenzione;
    private ?DatiAggiuntiviFatturazione $DatiFattureCollegate;
    private ?array $Attachments;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->fromArray($data);
        }
    }

    // Metodo per creare l'oggetto da array/JSON
    public function fromArray(array $data): self {
        $this->Type = $data['Type'] ?? '';
        $this->Date = $data['Date'] ?? '';
        $this->StampDutyAmount = $data['StampDutyAmount'] ?? null;
        $this->Notes = $data['Notes'] ?? null;
        $this->IsPaid = $data['IsPaid'] ?? false;
        $this->PaymentId = $data['PaymentId'] ?? null;
        $this->PaymentMethodId = $data['PaymentMethodId'] ?? null;
        $this->ProductItems = [];
        foreach($data["ProductItems"] as $item) {
            $this->ProductItems[] = ProductItem::fromArray($item);
        }
        $this->Stakeholder = Stakeholder::fromArray($data["Stakeholder"]);
        $this->DatiOrdineAcquisto = is_null($data["DatiOrdineAcquisto"]) ? null : DatiAggiuntiviFatturazione::fromArray($data["DatiOrdineAcquisto"]);
        $this->DatiContratto = is_null($data["DatiContratto"]) ? null : DatiAggiuntiviFatturazione::fromArray($data["DatiContratto"]);
        $this->DatiConvenzione = is_null($data["DatiConvenzione"]) ? null : DatiAggiuntiviFatturazione::fromArray($data["DatiConvenzione"]);
        $this->Attachments = $data["Attachments"] ?? [];
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
        foreach ($this->ProductItems as $item) {
            $productItemsArray[] = $item->toArray();
        }
        return [
            'Type' => $this->Type,
            'Date' => $this->Date,
            'StampDutyAmount' => $this->StampDutyAmount,
            'Notes' => $this->Notes,
            'IsPaid' => $this->IsPaid,
            'PaymentId' => $this->PaymentId,
            'PaymentMethodId' => $this->PaymentMethodId,
            'ProductItems' => $productItemsArray,
            'Stakeholder' => $this->Stakeholder->toArray(),
            'Attachments' => $this->Attachments,
            'DatiOrdineAcquisto' => $this->DatiOrdineAcquisto->toArray()
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getter e Setter per Type
    public function getType(): string {
        return $this->Type;
    }

    public function setType(string $type): self {
        $this->Type = $type;
        return $this;
    }

    // Getter e Setter per Date
    public function getDate(): string {
        return $this->Date;
    }

    public function setDate(string $date): self {
        $this->Date = $date;
        return $this;
    }

    // Getter e Setter per IsPaid
    public function getIsPaid(): bool {
        return $this->IsPaid;
    }

    public function setIsPaid(bool $IsPaid): self {
        $this->IsPaid = $IsPaid;
        return $this;
    }

    //Getter e Setter per StampDutyAmount
    public function getStampDutyAmount(): ?float {
        return $this->StampDutyAmount;
    }

    public function setStampDutyAmount(?float $StampDutyAmount): self {
        $this->StampDutyAmount = $StampDutyAmount;
        return $this;
    }

    //Getter e Setter per Notes
    public function getNotes(): ?string {
        return $this->Notes;
    }

    public function setNotes(?string $Notes): self {
        $this->Notes = $Notes;
        return $this;
    }

    // Getter e Setter per Attachments
    public function getAttachments(): array {
        return $this->Attachments;
    }

    public function setAttachments(array $Attachments): self {
        $this->Attachments = $Attachments;
        return $this;
    }

    // Getter e Setter per PaymentId
    public function getPaymentId(): ?string {
        return $this->PaymentId;
    }

    public function setPaymentId(?string $PaymentId): self {
        $this->PaymentId = $PaymentId;
        return $this;
    }

    // Getter e Setter per PaymentMethodId
    public function getPaymentMethodId(): ?int {
        return $this->PaymentMethodId;
    }

    public function setPaymentMethodId(?int $PaymentMethodId): self {
        $this->PaymentMethodId = $PaymentMethodId;
        return $this;
    }

    // Getter e Setter per ProductItems
    public function getProductItems(): array {
        return $this->ProductItems;
    }

    public function setProductItems(array $ProductItems): self {
        $this->ProductItems = $ProductItems;
        return $this;
    }

    // Metodo per aggiungere un prodotto
    public function addProductItem(ProductItem $ProductItem): self {
        $this->ProductItems[] = $ProductItem;
        return $this;
    }

    // Getter e Setter per Stakeholder
    public function getStakeholder(): Stakeholder {
        return $this->Stakeholder;
    }

    public function setStakeholder(Stakeholder $Stakeholder): self {
        $this->Stakeholder = $Stakeholder;
        return $this;
    }

    // Getter e Setter per DatiOrdineAcquisto
    public function getDatiOrdineAcquisto(): DatiAggiuntiviFatturazione {
        return $this->DatiOrdineAcquisto;
    }

    public function setDatiOrdineAcquisto(DatiAggiuntiviFatturazione $DatiOrdineAcquisto): self {
        $this->DatiOrdineAcquisto = $DatiOrdineAcquisto;
        return $this;
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];

        if (empty($this->Type)) {
            $errors[] = 'Type is required';
        }

        if (empty($this->Date)) {
            $errors[] = 'Date is required';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->Date)) {
            $errors[] = 'Date must be in YYYY-MM-DD format';
        }

        if (empty($this->ProductItems)) {
            $errors[] = 'At least one product item is required';
        }

        if (is_null($this->Stakeholder)) {
            $errors[] = 'Stakeholder is required';
        }

        return $errors;
    }

    // Metodo per verificare se il documento è valido
    public function isValid(): bool {
        return empty($this->validate());
    }
}