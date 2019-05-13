<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilEncryption\Decryptor\DecryptorInterface;
use Spryker\Service\UtilEncryption\Decryptor\OpensslDecryptor;
use Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface;
use Spryker\Service\UtilEncryption\EncryptInitVector\EncryptInitVectorGeneratorInterface;
use Spryker\Service\UtilEncryption\EncryptInitVector\OpensslEncryptInitVectorGenerator;
use Spryker\Service\UtilEncryption\Encryptor\EncryptorInterface;
use Spryker\Service\UtilEncryption\Encryptor\OpensslEncryptor;

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
        return new OpensslEncryptor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\Decryptor\DecryptorInterface
     */
    public function createDecryptor(): DecryptorInterface
    {
        return new OpensslDecryptor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\EncryptInitVector\EncryptInitVectorGeneratorInterface
     */
    public function createEncryptInitVectorGenerator(): EncryptInitVectorGeneratorInterface
    {
        return new OpensslEncryptInitVectorGenerator(
            $this->getUtilTextService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface
     */
    public function getUtilTextService(): UtilEncryptionToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(UtilEncryptionDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
