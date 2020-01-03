<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption\EncryptInitVector;

use Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface;
use Spryker\Service\UtilEncryption\UtilEncryptionConfig;

class OpenSslEncryptInitVectorGenerator implements OpenSslEncryptInitVectorGeneratorInterface
{
    /**
     * @var \Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Service\UtilEncryption\UtilEncryptionConfig
     */
    protected $utilEncryptionConfig;

    /**
     * @param \Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Service\UtilEncryption\UtilEncryptionConfig $utilEncryptionConfig
     */
    public function __construct(UtilEncryptionToUtilTextServiceInterface $utilTextService, UtilEncryptionConfig $utilEncryptionConfig)
    {
        $this->utilTextService = $utilTextService;
        $this->utilEncryptionConfig = $utilEncryptionConfig;
    }

    /**
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function generateOpenSslEncryptInitVector(?string $encryptionMethod = null): string
    {
        return $this->utilTextService->generateRandomString(
            openssl_cipher_iv_length($encryptionMethod ?? $this->utilEncryptionConfig->getDefaultOpenSslEncryptionMethod())
        );
    }
}
