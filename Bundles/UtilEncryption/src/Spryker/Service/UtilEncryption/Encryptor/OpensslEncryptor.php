<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\Encryptor;

class OpensslEncryptor implements EncryptorInterface
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
     * @param string $plainText
     * @param string $initVector
     * @param string $encriptionKey
     *
     * @return string
     */
    public function encrypt(string $plainText, string $initVector, string $encriptionKey): string
    {
        return openssl_encrypt($plainText, $this->encryptionCipherMethod, $encriptionKey, 0, $initVector);
    }
}
