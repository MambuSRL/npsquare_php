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

namespace Mambusrl\npsquare_php\PaymentDetails;

final class RetrivePaymentDetail
{
    private int $documentId;

    /** @var PaymentDetail[] */
    private array $paymentDetails = [];

    /**
     * @param PaymentDetail[] $paymentDetails
     */
    public function __construct(int $documentId, array $paymentDetails = [])
    {
        $this->setDocumentId($documentId);
        $this->setPaymentDetails($paymentDetails);
    }

    public static function fromArray(array $data): self
    {
        if (!array_key_exists('DocumentId', $data)) {
            throw new \InvalidArgumentException('DocumentId is required');
        }
        if (!array_key_exists('PaymentDetails', $data) || !is_array($data['PaymentDetails'])) {
            throw new \InvalidArgumentException('PaymentDetails is required and must be an array');
        }

        $details = [];
        foreach ($data['PaymentDetails'] as $item) {
            if ($item instanceof PaymentDetail) {
                $details[] = $item;
                continue;
            }
            if (!is_array($item)) {
                throw new \InvalidArgumentException('Each PaymentDetails item must be an array or PaymentDetail');
            }
            $details[] = PaymentDetail::fromArray($item);
        }

        return new self((int) $data['DocumentId'], $details);
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON for RetrivePaymentDetail');
        }

        return self::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'DocumentId' => $this->documentId,
            'PaymentDetails' => array_map(
                static fn(PaymentDetail $detail): array => $detail->toArray(),
                $this->paymentDetails
            ),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function setDocumentId(int $documentId): self
    {
        if ($documentId <= 0) {
            throw new \InvalidArgumentException('DocumentId must be greater than 0');
        }
        $this->documentId = $documentId;

        return $this;
    }

    /**
     * @return PaymentDetail[]
     */
    public function getPaymentDetails(): array
    {
        return $this->paymentDetails;
    }

    /**
     * @param PaymentDetail[] $paymentDetails
     */
    public function setPaymentDetails(array $paymentDetails): self
    {
        foreach ($paymentDetails as $detail) {
            if (!$detail instanceof PaymentDetail) {
                throw new \InvalidArgumentException('PaymentDetails must contain only PaymentDetail objects');
            }
        }
        $this->paymentDetails = array_values($paymentDetails);

        return $this;
    }

    public function addPaymentDetail(PaymentDetail $paymentDetail): self
    {
        $this->paymentDetails[] = $paymentDetail;

        return $this;
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->documentId <= 0) {
            $errors[] = 'DocumentId must be greater than 0';
        }

        if (empty($this->paymentDetails)) {
            $errors[] = 'PaymentDetails is required';
        }

        foreach ($this->paymentDetails as $index => $detail) {
            $detailErrors = $detail->validate();
            foreach ($detailErrors as $error) {
                $errors[] = sprintf('PaymentDetails[%d]: %s', $index, $error);
            }
        }

        return $errors;
    }

    public function isValid(): bool
    {
        return empty($this->validate());
    }
}
