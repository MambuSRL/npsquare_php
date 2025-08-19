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
class Stakeholder {
    
    private ?string $externalId;
    private ?string $idSquare;
    private ?string $nome;
    private ?string $cognome;
    private ?string $ragioneSociale;
    private ?string $codfisc;
    private ?string $piva;
    private ?string $email;
    private ?string $telefono;
    private ?string $cellulare;
    private ?string $codiceSdi;
    private ?string $pec;
    private int $tipoDonatore;
    private ?string $nazioneIso2;
    private ?string $siglaProv;
    private ?string $citta;
    private ?string $cap;
    private ?string $indirizzo;
    private ?string $nCivico;
    private int $pubblicaAmministrazione;

    public function __construct(
        ?string $externalId = null,
        ?string $idSquare = null,
        ?string $nome = null,
        ?string $cognome = null,
        ?string $ragioneSociale = null,
        ?string $codfisc = null,
        ?string $piva = null,
        ?string $email = null,
        ?string $telefono = null,
        ?string $cellulare = null,
        ?string $codiceSdi = null,
        ?string $pec = null,
        int $tipoDonatore = 0,
        ?string $nazioneIso2 = null,
        ?string $siglaProv = null,
        ?string $citta = null,
        ?string $cap = null,
        ?string $indirizzo = null,
        ?string $nCivico = null,
        int $pubblicaAmministrazione = 0
    ) {
        $this->externalId = $externalId;
        $this->idSquare = $idSquare;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->ragioneSociale = $ragioneSociale;
        $this->codfisc = $codfisc;
        $this->piva = $piva;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->cellulare = $cellulare;
        $this->codiceSdi = $codiceSdi;
        $this->pec = $pec;
        $this->tipoDonatore = $tipoDonatore;
        $this->nazioneIso2 = $nazioneIso2;
        $this->siglaProv = $siglaProv;
        $this->citta = $citta;
        $this->cap = $cap;
        $this->indirizzo = $indirizzo;
        $this->nCivico = $nCivico;
        $this->pubblicaAmministrazione = $pubblicaAmministrazione;
    }

    // Metodo statico per creare l'oggetto da array/JSON
    public static function fromArray(array $data): self {
        return new self(
            $data['external_id'] ?? null,
            $data['id_square'] ?? null,
            $data['nome'] ?? null,
            $data['cognome'] ?? null,
            $data['ragionesociale'] ?? null,
            $data['codfisc'] ?? null,
            $data['piva'] ?? null,
            $data['email'] ?? null,
            $data['telefono'] ?? null,
            $data['cellulare'] ?? null,
            $data['codice_sdi'] ?? null,
            $data['pec'] ?? null,
            $data['tipo_donatore'] ?? null,
            $data['nazioneIso2'] ?? null,
            $data['sigla_prov'] ?? null,
            $data['citta'] ?? null,
            $data['cap'] ?? null,
            $data['indirizzo'] ?? null,
            $data['n_civico'] ?? null,
            $data['pubblica_amministrazione'] ?? null
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
            'external_id' => $this->externalId,
            'id_square' => $this->idSquare,
            'nome' => $this->nome,
            'cognome' => $this->cognome,
            'ragionesociale' => $this->ragioneSociale,
            'codfisc' => $this->codfisc,
            'piva' => $this->piva,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'cellulare' => $this->cellulare,
            'codice_sdi' => $this->codiceSdi,
            'pec' => $this->pec,
            'tipo_donatore' => $this->tipoDonatore,
            'nazioneIso2' => $this->nazioneIso2,
            'sigla_prov' => $this->siglaProv,
            'citta' => $this->citta,
            'cap' => $this->cap,
            'indirizzo' => $this->indirizzo,
            'n_civico' => $this->nCivico,
            'pubblica_amministrazione' => $this->pubblicaAmministrazione
        ];
    }

    // Metodo per convertire l'oggetto in JSON
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    // Getters
    public function getExternalId(): ?string { return $this->externalId; }
    public function getIdSquare(): ?string { return $this->idSquare; }
    public function getNome(): ?string { return $this->nome; }
    public function getCognome(): ?string { return $this->cognome; }
    public function getRagioneSociale(): ?string { return $this->ragioneSociale; }
    public function getCodfisc(): ?string { return $this->codfisc; }
    public function getPiva(): ?string { return $this->piva; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefono(): ?string { return $this->telefono; }
    public function getCellulare(): ?string { return $this->cellulare; }
    public function getCodiceSdi(): ?string { return $this->codiceSdi; }
    public function getPec(): ?string { return $this->pec; }
    public function getTipoDonatore(): ?int { return $this->tipoDonatore; }
    public function getNazioneIso2(): ?string { return $this->nazioneIso2; }
    public function getSiglaProv(): ?string { return $this->siglaProv; }
    public function getCitta(): ?string { return $this->citta; }
    public function getCap(): ?string { return $this->cap; }
    public function getIndirizzo(): ?string { return $this->indirizzo; }
    public function getNCivico(): ?string { return $this->nCivico; }
    public function getPubblicaAmministrazione(): ?int { return $this->pubblicaAmministrazione; }

    // Setters
    public function setExternalId(?string $externalId): self {
        $this->externalId = $externalId;
        return $this;
    }

    public function setIdSquare(?string $idSquare): self {
        $this->idSquare = $idSquare;
        return $this;
    }

    public function setNome(?string $nome): self {
        $this->nome = $nome;
        return $this;
    }

    public function setCognome(?string $cognome): self {
        $this->cognome = $cognome;
        return $this;
    }

    public function setRagioneSociale(?string $ragioneSociale): self {
        $this->ragioneSociale = $ragioneSociale;
        return $this;
    }

    public function setCodfisc(?string $codfisc): self {
        $this->codfisc = $codfisc;
        return $this;
    }

    public function setPiva(?string $piva): self {
        $this->piva = $piva;
        return $this;
    }

    public function setEmail(?string $email): self {
        $this->email = $email;
        return $this;
    }

    public function setTelefono(?string $telefono): self {
        $this->telefono = $telefono;
        return $this;
    }

    public function setCellulare(?string $cellulare): self {
        $this->cellulare = $cellulare;
        return $this;
    }

    public function setCodiceSdi(?string $codiceSdi): self {
        $this->codiceSdi = $codiceSdi;
        return $this;
    }

    public function setPec(?string $pec): self {
        $this->pec = $pec;
        return $this;
    }

    public function setTipoDonatore(?int $tipoDonatore): self {
        $this->tipoDonatore = $tipoDonatore;
        return $this;
    }

    public function setNazioneIso2(?string $nazioneIso2): self {
        $this->nazioneIso2 = $nazioneIso2;
        return $this;
    }

    public function setSiglaProv(?string $siglaProv): self {
        $this->siglaProv = $siglaProv;
        return $this;
    }

    public function setCitta(?string $citta): self {
        $this->citta = $citta;
        return $this;
    }

    public function setCap(?string $cap): self {
        $this->cap = $cap;
        return $this;
    }

    public function setIndirizzo(?string $indirizzo): self {
        $this->indirizzo = $indirizzo;
        return $this;
    }

    public function setNCivico(?string $nCivico): self {
        $this->nCivico = $nCivico;
        return $this;
    }

    public function setPubblicaAmministrazione(?int $pubblicaAmministrazione): self {
        $this->pubblicaAmministrazione = $pubblicaAmministrazione;
        return $this;
    }

    // Metodi di utilità
    public function getNomeCompleto(): ?string {
        if ($this->nome && $this->cognome) {
            return trim($this->nome . ' ' . $this->cognome);
        }
        return $this->ragioneSociale;
    }

    public function getIndirizzoCompleto(): string {
        $parts = array_filter([
            $this->indirizzo,
            $this->nCivico,
            $this->cap,
            $this->citta,
            $this->siglaProv ? '(' . $this->siglaProv . ')' : null
        ]);
        
        return implode(' ', $parts);
    }

    public function isPubblicaAmministrazione(): bool {
        return $this->pubblicaAmministrazione === 1;
    }

    public function hasPartitaIva(): bool {
        return !empty($this->piva);
    }

    // Metodo per validare i dati
    public function validate(): array {
        $errors = [];

        if (empty($this->ragioneSociale) && (empty($this->nome) || empty($this->cognome))) {
            $errors[] = 'Either ragionesociale or both nome and cognome are required';
        }

        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (!empty($this->pec) && !filter_var($this->pec, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid PEC email format';
        }

        if (!empty($this->codfisc) && !$this->validateCodiceFiscale($this->codfisc)) {
            $errors[] = 'Invalid codice fiscale format';
        }

        return $errors;
    }

    private function validateCodiceFiscale(string $cf): bool {
        return preg_match('/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/', strtoupper($cf));
    }

    // Metodo per verificare se lo stakeholder è valido
    public function isValid(): bool {
        return empty($this->validate());
    }
}