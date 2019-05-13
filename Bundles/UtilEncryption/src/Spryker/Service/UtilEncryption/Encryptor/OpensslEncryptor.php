<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\Encryptor;

use Spryker\Service\UtilEncryption\UtilEncryptionConfig;

class OpensslEncryptor implements EncryptorInterface
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
     *
     * @return string
     */
    public function encrypt(string $plainText, string $initVector, string $encryptionKey): string
    {
        return base64_encode(
            openssl_encrypt(
                $plainText,
                $this->utilEncryptionConfig->getEncryptionCipherMethod(),
                $encryptionKey,
                0,
                $initVector
            )
        );
    }
}
