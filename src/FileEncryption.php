<?php

declare(strict_types=1);

namespace Effectra\Fs;

/**
 * Utility class for file encryption and decryption.
 */
class FileEncryption
{
    private string $encryptionKey;

    /**
     * FileEncryption constructor.
     *
     * @param string $encryptionKey The encryption key.
     */
    public function __construct(string $encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Encrypts a file and saves the encrypted content to a destination file.
     *
     * @param string $sourceFile      The path to the source file.
     * @param string $destinationFile The path to the destination file.
     * @return void
     */
    public function encryptFile(string $sourceFile, string $destinationFile): void
    {
        $content = file_get_contents($sourceFile);
        $encryptedContent = $this->encrypt($content);
        file_put_contents($destinationFile, $encryptedContent);
    }

    /**
     * Decrypts a file and saves the decrypted content to a destination file.
     *
     * @param string $sourceFile      The path to the source file.
     * @param string $destinationFile The path to the destination file.
     * @return void
     */
    public function decryptFile(string $sourceFile, string $destinationFile): void
    {
        $content = file_get_contents($sourceFile);
        $decryptedContent = $this->decrypt($content);
        file_put_contents($destinationFile, $decryptedContent);
    }

    /**
     * Encrypts data using AES-256-CBC encryption.
     *
     * @param mixed $data The data to encrypt.
     * @return string The encrypted data.
     */
    private function encrypt($data): string
    {
        $iv = random_bytes(16);
        $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }

    /**
     * Decrypts data that was encrypted using AES-256-CBC encryption.
     *
     * @param string $data The encrypted data.
     * @return string The decrypted data.
     */
    private function decrypt($data): string
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encryptedData = substr($data, 16);
        return openssl_decrypt($encryptedData, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }
}
