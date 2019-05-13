<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Vault\Dependency\Service;

class VaultToUtilEncryptionServiceBridge implements VaultToUtilEncryptionServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncryption\UtilEncryptionServiceInterface
     */
    protected $utilEncryptionService;

    /**
     * @param \Spryker\Service\UtilEncryption\UtilEncryptionServiceInterface $utilEncryptionService
     */
    public function __construct($utilEncryptionService)
    {
        $this->utilEncryptionService = $utilEncryptionService;
    }

    /**
     * @return string
     */
    public function generateEncryptInitVector(): string
    {
        return $this->utilEncryptionService->generateEncryptInitVector();
    }

    /**
     * @param string $chiperText
     * @param string $initVector
     * @param string $encryptionKey
     *
     * @return string
     */
    public function decrypt(string $chiperText, string $initVector, string $encryptionKey): string
    {
        return $this->utilEncryptionService->decrypt($chiperText, $initVector, $encryptionKey);
    }

    /**
     * @param string $plainText
     * @param string $initVector
     * @param string $encryptionKey
     *
     * @return string
     */
    public function encrypt(string $plainText, string $initVector, string $encryptionKey): string
    {
        return $this->utilEncryptionService->encrypt($plainText, $initVector, $encryptionKey);
    }
}
