<?php
declare(strict_types=1);
namespace Mambusrl\npsquare_php\SalesDoc;

/**
 * Rappresenta l'oggetto "DatiAggiuntiviFatturazione" mostrato in screenshot.
 * Tutti i campi sono opzionali (possono essere null) e rispettano i tipi indicati.
 */
class DatiAggiuntiviFatturazione implements \JsonSerializable
{
    /** @var int|null */
    private ?int $RiferimentoNumeroLinea = null;
    /** @var string|null */
    private ?string $IdDocumento = null;
    /** @var string|null Data nel formato YYYY-MM-DD */
    private ?string $Data = null;
    /** @var string|null */
    private ?string $NumItem = null;
    /** @var string|null */
    private ?string $CodiceCommessaConvenzione = null;
    /** @var string|null */
    private ?string $CodiceCUP = null;
    /** @var string|null */
    private ?string $CodiceCIG = null;

    public function __construct(
        ?int $riferimentoNumeroLinea = null,
        ?string $idDocumento = null,
        ?string $data = null,
        ?string $numItem = null,
        ?string $codiceCommessaConvenzione = null,
        ?string $codiceCUP = null,
        ?string $codiceCIG = null
    ) {
        $this->setRiferimentoNumeroLinea($riferimentoNumeroLinea);
        $this->setIdDocumento($idDocumento);
        $this->setData($data);
        $this->setNumItem($numItem);
        $this->setCodiceCommessaConvenzione($codiceCommessaConvenzione);
        $this->setCodiceCUP($codiceCUP);
        $this->setCodiceCIG($codiceCIG);
    }

    // ----------------- Getters -----------------
    public function getRiferimentoNumeroLinea(): ?int
    {
        return $this->RiferimentoNumeroLinea;
    }
    public function getIdDocumento(): ?string
    {
        return $this->IdDocumento;
    }
    public function getData(): ?string
    {
        return $this->Data;
    }
    public function getNumItem(): ?string
    {
        return $this->NumItem;
    }
    public function getCodiceCommessaConvenzione(): ?string
    {
        return $this->CodiceCommessaConvenzione;
    }
    public function getCodiceCUP(): ?string
    {
        return $this->CodiceCUP;
    }
    public function getCodiceCIG(): ?string
    {
        return $this->CodiceCIG;
    }

    // ----------------- Setters (fluent) -----------------
    public function setRiferimentoNumeroLinea(?int $value): self
    {
        if ($value !== null && $value < 0) {
            throw new \InvalidArgumentException('RiferimentoNumeroLinea deve essere >= 0 o null');
        }
        $this->RiferimentoNumeroLinea = $value;
        return $this;
    }

    public function setIdDocumento(?string $value): self
    {
        $this->IdDocumento = $this->normalizeString($value);
        return $this;
    }

    /**
     * Accetta date nel formato YYYY-MM-DD; consente null.
     */
    public function setData(?string $value): self
    {
        if ($value !== null) {
            if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $value)) {
                throw new \InvalidArgumentException('Data deve essere nel formato YYYY-MM-DD oppure null');
            }
            // Validazione calendario reale
            [$y, $m, $d] = array_map('intval', explode('-', $value));
            if (!checkdate($m, $d, $y)) {
                throw new \InvalidArgumentException('Data non valida nel calendario');
            }
        }
        $this->Data = $value;
        return $this;
    }


    public function setNumItem(?string $value): self
    {
        $this->NumItem = $this->normalizeString($value);
        return $this;
    }

    public function setCodiceCommessaConvenzione(?string $value): self
    {
        $this->CodiceCommessaConvenzione = $this->normalizeString($value);
        return $this;
    }

    public function setCodiceCUP(?string $value): self
    {
        $this->CodiceCUP = $this->normalizeString($value);
        return $this;
    }

    public function setCodiceCIG(?string $value): self
    {
        $this->CodiceCIG = $this->normalizeString($value);
        return $this;
    }

    // ----------------- Helpers -----------------
    private function normalizeString(?string $value): ?string
    {
        if ($value === null)
            return null;
        $value = trim($value);
        return $value === '' ? null : $value;
    }

    /**
     * Rappresentazione array conforme ai nomi dei campi mostrati (in camelCase preservato).
     * I campi null non vengono inclusi, a meno che $includeNulls=true.
     */
    public function toArray(bool $includeNulls = false): array
    {
        $data = [
            'RiferimentoNumeroLinea' => $this->RiferimentoNumeroLinea,
            'IdDocumento' => $this->IdDocumento,
            'Data' => $this->Data,
            'NumItem' => $this->NumItem,
            'CodiceCommessaConvenzione' => $this->CodiceCommessaConvenzione,
            'CodiceCUP' => $this->CodiceCUP,
            'CodiceCIG' => $this->CodiceCIG,
        ];

        if ($includeNulls) {
            return $data;
        }
        // escludi i null
        return array_filter($data, static fn($v) => $v !== null);
    }


    public function jsonSerialize(): array
    {
        return $this->toArray(true);
    }

    /**
     * Crea l'istanza a partire da un array (ad es. decodifica JSON o payload http).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['RiferimentoNumeroLinea'] ?? null,
            $data['IdDocumento'] ?? null,
            $data['Data'] ?? null,
            $data['NumItem'] ?? null,
            $data['CodiceCommessaConvenzione'] ?? null,
            $data['CodiceCUP'] ?? null,
            $data['CodiceCIG'] ?? null
        );
    }
}