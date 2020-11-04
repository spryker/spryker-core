<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ChecksumGenerator;

use Spryker\Service\ChecksumGenerator\Dependency\Service\ChecksumGeneratorToUtilEncryptionServiceInterface;
use Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface;
use Spryker\Service\ChecksumGenerator\Generator\CrcChecksumOpenSslGenerator;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ChecksumGeneratorServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface
     */
    public function createCrcChecksumOpenSslGenerator(): ChecksumOpenSslGeneratorInterface
    {
        return new CrcChecksumOpenSslGenerator($this->getUtilEncryptionService());
    }

    /**
     * @return \Spryker\Service\ChecksumGenerator\Dependency\Service\ChecksumGeneratorToUtilEncryptionServiceInterface
     */
    public function getUtilEncryptionService(): ChecksumGeneratorToUtilEncryptionServiceInterface
    {
        return $this->getProvidedDependency(ChecksumGeneratorDependencyProvider::SERVICE_UTIL_ENCRYPTION);
    }
}
