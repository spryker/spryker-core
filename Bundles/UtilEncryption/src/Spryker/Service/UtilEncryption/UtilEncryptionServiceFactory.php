<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilEncryption\Decryptor\OpenSslDecryptor;
use Spryker\Service\UtilEncryption\Decryptor\OpenSslDecryptorInterface;
use Spryker\Service\UtilEncryption\Dependency\Service\UtilEncryptionToUtilTextServiceInterface;
use Spryker\Service\UtilEncryption\EncryptInitVector\OpenSslEncryptInitVectorGenerator;
use Spryker\Service\UtilEncryption\EncryptInitVector\OpenSslEncryptInitVectorGeneratorInterface;
use Spryker\Service\UtilEncryption\Encryptor\OpenSslEncryptor;
use Spryker\Service\UtilEncryption\Encryptor\OpenSslEncryptorInterface;

/**
 * @method \Spryker\Service\UtilEncryption\UtilEncryptionConfig getConfig()
 */
class UtilEncryptionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilEncryption\Encryptor\OpenSslEncryptorInterface
     */
    public function createOpenSslEncryptor(): OpenSslEncryptorInterface
    {
        return new OpenSslEncryptor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\Decryptor\OpenSslDecryptorInterface
     */
    public function createOpenSslDecryptor(): OpenSslDecryptorInterface
    {
        return new OpenSslDecryptor(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncryption\EncryptInitVector\OpenSslEncryptInitVectorGeneratorInterface
     */
    public function createOpenSslEncryptInitVectorGenerator(): OpenSslEncryptInitVectorGeneratorInterface
    {
        return new OpenSslEncryptInitVectorGenerator(
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
