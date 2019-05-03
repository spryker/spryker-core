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
     * @return string
     */
    public function generateEncryptInitVector(): string
    {
        return $this->getFactory()
            ->createEncryptInitVectorGenerator()
            ->generateEncryptInitVector();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $plainText
     * @param string $initVector
     * @param string $encriptionKey
     *
     * @return string
     */
    public function encrypt(string $plainText, string $initVector, string $encriptionKey): string
    {
        return $this->getFactory()
            ->createEncryptor()
            ->encrypt($plainText, $initVector, $encriptionKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $chiperText
     * @param string $initVector
     * @param string $encriptionKey
     *
     * @return string
     */
    public function decrypt(string $chiperText, string $initVector, string $encriptionKey): string
    {
        return $this->getFactory()
            ->createDecryptor()
            ->decrypt($chiperText, $initVector, $encriptionKey);
    }
}
