<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\EncryptInitVector;

class EncryptInitVectorGenerator implements EncryptInitVectorGeneratorInterface
{
    /**
     * @var string
     */
    protected $encryptionCipherMethod;

    /**
     * @param string $encryptionCipherMethod
     */
    public function __construct(string $encryptionCipherMethod)
    {
        $this->encryptionCipherMethod = $encryptionCipherMethod;
    }

    /**
     * @return string
     */
    public function generateEncryptInitVector(): string
    {
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->encryptionCipherMethod));
    }
}
