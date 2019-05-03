<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilEncryption\Decryptor\Decryptor;
use Spryker\Service\UtilEncryption\Decryptor\DecryptorInterface;
use Spryker\Service\UtilEncryption\EncryptInitVector\EncryptInitVectorGenerator;
use Spryker\Service\UtilEncryption\EncryptInitVector\EncryptInitVectorGeneratorInterface;
use Spryker\Service\UtilEncryption\Encryptor\Encryptor;
use Spryker\Service\UtilEncryption\Encryptor\EncryptorInterface;

/**
 * @method \Spryker\Service\UtilEncryption\UtilEncryptionConfig getConfig()
 */
class UtilEncryptionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilEncryption\Encryptor\EncryptorInterface
     */
    public function createEncryptor(): EncryptorInterface
    {
        return new Encryptor(
            $this->getConfig()
                ->getEncryptionCipherMethod()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\Decryptor\DecryptorInterface
     */
    public function createDecryptor(): DecryptorInterface
    {
        return new Decryptor(
            $this->getConfig()
                ->getEncryptionCipherMethod()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\EncryptInitVector\EncryptInitVectorGeneratorInterface
     */
    public function createEncryptInitVectorGenerator(): EncryptInitVectorGeneratorInterface
    {
        return new EncryptInitVectorGenerator(
            $this->getConfig()
                ->getEncryptionCipherMethod()
        );
    }
}
