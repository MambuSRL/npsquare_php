<?php

declare(strict_types=1);
namespace Mambusrl\npsquare_php\SalesDoc;

final class Attachment
{
    private string $filename;
    private string $contentBase64;

    public function __construct(string $filename, string $contentBase64)
    {
        if ($filename === '') {
            throw new \InvalidArgumentException('filename richiesto');
        }
        if ($contentBase64 === '' || !self::isBase64($contentBase64)) {
            throw new \InvalidArgumentException('content_base64 non valido o vuoto');
        }
        $this->filename = $filename;
        $this->contentBase64 = $contentBase64;
    }

    /** Crea da contenuto binario. */
    public static function fromBinary(string $filename, string $binaryContent): self
    {
        return new self($filename, base64_encode($binaryContent));
    }

    /** Crea leggendo un file dal disco. */
    public static function fromFile(string $path, ?string $filenameOverride = null): self
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException("File non trovato o non leggibile: {$path}");
        }
        $binary = file_get_contents($path);
        $name   = $filenameOverride ?? basename($path);
        return self::fromBinary($name, $binary === false ? '' : $binary);
    }

    /** Ritorna la struttura per l’API. */
    public function toArray(): array
    {
        return [
            'filename'       => $this->filename,
            'content_base64' => $this->contentBase64,
        ];
    }

    /** Utility: crea il payload API da una lista di Attachment. */
    public static function buildPayload(array $attachments): array
    {
        return array_map(fn (self $a) => $a->toArray(), $attachments);
    }

    /** Check veloce per base64. */
    private static function isBase64(string $s): bool
    {
        // accetta stringhe base64 senza spazi/newline
        return $s !== '' && base64_encode(base64_decode($s, true) ?: '') === $s;
    }
}