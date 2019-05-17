<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\Encryptor;

use Spryker\Service\UtilEncryption\UtilEncryptionConfig;

class OpenSslEncryptor implements OpenSslEncryptorInterface
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
     * @param string $plainText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function encryptOpenSsl(string $plainText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string
    {
        return base64_encode(
            openssl_encrypt(
                $plainText,
                $encryptionMethod ?? $this->utilEncryptionConfig->getDefaultOpenSslEncryptionMethod(),
                $encryptionKey,
                0,
                $initVector
            )
        );
    }
}
