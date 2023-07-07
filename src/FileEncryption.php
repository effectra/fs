<?php

declare(strict_types=1);

namespace Effectra\Fs;

/**
 * Utility class for manipulating file encryption and decryption
 */
class FileEncryption
{
    private string $encryptionKey;

    public function __construct(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    public function encryptFile(string $sourceFile, string $destinationFile): void
    {
        $content = file_get_contents($sourceFile);
        $encryptedContent = $this->encrypt($content);
        file_put_contents($destinationFile, $encryptedContent);
    }

    public function decryptFile(string $sourceFile, string $destinationFile): void
    {
        $content = file_get_contents($sourceFile);
        $decryptedContent = $this->decrypt($content);
        file_put_contents($destinationFile, $decryptedContent);
    }

    private function encrypt($data): string
    {
        $iv = random_bytes(16);
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }

    private function decrypt($data): string
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encryptedData = substr($data, 16);
        return openssl_decrypt($encryptedData, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }
}
