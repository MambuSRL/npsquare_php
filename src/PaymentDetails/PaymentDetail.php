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

final class PaymentDetail
{
    private float $paymentAmount;
    private string $paymentDate;
    private string $paymentMethod;

    public function __construct(float $paymentAmount, string $paymentDate, string $paymentMethod)
    {
        $this->setPaymentAmount($paymentAmount);
        $this->setPaymentDate($paymentDate);
        $this->setPaymentMethod($paymentMethod);
    }

    public static function fromArray(array $data): self
    {
        if (!array_key_exists('PaymentAmount', $data)) {
            throw new \InvalidArgumentException('PaymentAmount is required');
        }
        if (!array_key_exists('PaymentDate', $data)) {
            throw new \InvalidArgumentException('PaymentDate is required');
        }
        if (!array_key_exists('PaymentMethod', $data)) {
            throw new \InvalidArgumentException('PaymentMethod is required');
        }

        return new self(
            (float) $data['PaymentAmount'],
            (string) $data['PaymentDate'],
            (string) $data['PaymentMethod']
        );
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON for PaymentDetail');
        }

        return self::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'PaymentAmount' => $this->paymentAmount,
            'PaymentDate' => $this->paymentDate,
            'PaymentMethod' => $this->paymentMethod,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function getPaymentAmount(): float
    {
        return $this->paymentAmount;
    }

    public function setPaymentAmount(float $paymentAmount): self
    {
        if ($paymentAmount < 0) {
            throw new \InvalidArgumentException('PaymentAmount must be >= 0');
        }
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    public function getPaymentDate(): string
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(string $paymentDate): self
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $paymentDate)) {
            throw new \InvalidArgumentException('PaymentDate must be in YYYY-MM-DD format');
        }
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): self
    {
        if (trim($paymentMethod) === '') {
            throw new \InvalidArgumentException('PaymentMethod is required');
        }
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function validate(): array
    {
        $errors = [];

        if ($this->paymentAmount < 0) {
            $errors[] = 'PaymentAmount must be >= 0';
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->paymentDate)) {
            $errors[] = 'PaymentDate must be in YYYY-MM-DD format';
        }
        if (trim($this->paymentMethod) === '') {
            $errors[] = 'PaymentMethod is required';
        }

        return $errors;
    }

    public function isValid(): bool
    {
        return empty($this->validate());
    }
}
