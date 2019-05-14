<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\Decryptor;

use Spryker\Service\UtilEncryption\UtilEncryptionConfig;

class OpenSslDecryptor implements OpenSslDecryptorInterface
{
    /**
     * @var \Spryker\Service\UtilEncryption\UtilEncryptionConfig
     */
    protected $utilEncryptionConfig;

    /**
     * @param \Spryker\Service\UtilEncryption\UtilEncryptionConfig $utilEncryptionConfig
     */
    public function __construct(UtilEncryptionConfig $utilEncryptionConfig)
    {
        $this->utilEncryptionConfig = $utilEncryptionConfig;
    }

    /**
     * @param string $chiperText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function decryptOpenSsl(string $chiperText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string
    {
        return openssl_decrypt(
            base64_decode($chiperText),
            $encryptionMethod ?? $this->utilEncryptionConfig->getDefaultOpenSslEncryptionMethod(),
            $encryptionKey,
            0,
            $initVector
        );
    }
}
