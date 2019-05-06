<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\EncryptInitVector;

use Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface;

class OpensslEncryptInitVectorGenerator implements EncryptInitVectorGeneratorInterface
{
    /**
     * @var string
     */
    protected $encryptionCipherMethod;

    /**
     * @var \Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface $utilTextService
     * @param string $encryptionCipherMethod
     */
    public function __construct(UtilEncryptionToUtilTextServiceInterface $utilTextService, string $encryptionCipherMethod)
    {
        $this->utilTextService = $utilTextService;
        $this->encryptionCipherMethod = $encryptionCipherMethod;
    }

    /**
     * @return string
     */
    public function generateEncryptInitVector(): string
    {
        return $this->utilTextService->generateRandomString(
            openssl_cipher_iv_length($this->encryptionCipherMethod)
        );
    }
}
