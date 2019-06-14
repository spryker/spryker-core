<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilEncryption\UtilEncryptionServiceFactory getFactory()
 */
class UtilEncryptionService extends AbstractService implements UtilEncryptionServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function generateOpenSslEncryptInitVector(?string $encryptionMethod = null): string
    {
        return $this->getFactory()
            ->createOpenSslEncryptInitVectorGenerator()
            ->generateOpenSslEncryptInitVector($encryptionMethod);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $plainText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function encryptOpenSsl(string $plainText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string
    {
        return $this->getFactory()
            ->createOpenSslEncryptor()
            ->encryptOpenSsl($plainText, $initVector, $encryptionKey, $encryptionMethod);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $cipherText
     * @param string $initVector
     * @param string $encryptionKey
     * @param string|null $encryptionMethod
     *
     * @return string
     */
    public function decryptOpenSsl(string $cipherText, string $initVector, string $encryptionKey, ?string $encryptionMethod = null): string
    {
        return $this->getFactory()
            ->createOpenSslDecryptor()
            ->decryptOpenSsl($cipherText, $initVector, $encryptionKey, $encryptionMethod);
    }
}
