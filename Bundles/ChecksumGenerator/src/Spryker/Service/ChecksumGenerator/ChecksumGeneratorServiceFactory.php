<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ChecksumGenerator;

use Spryker\Service\ChecksumGenerator\Dependency\Service\ChecksumGeneratorToUtilEncryptionInterface;
use Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface;
use Spryker\Service\ChecksumGenerator\Generator\CrcChecksumOpenSslGenerator;
use Spryker\Service\Kernel\AbstractServiceFactory;

class ChecksumGeneratorServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ChecksumGenerator\Generator\ChecksumOpenSslGeneratorInterface
     */
    public function createOpenSslChecksumGenerator(): ChecksumOpenSslGeneratorInterface
    {
        return new CrcChecksumOpenSslGenerator($this->getProductConfigurationDataChecksumGenerator());
    }

    /**
     * @return \Spryker\Service\ChecksumGenerator\Dependency\Service\ChecksumGeneratorToUtilEncryptionInterface
     */
    public function getProductConfigurationDataChecksumGenerator(): ChecksumGeneratorToUtilEncryptionInterface
    {
        return $this->getProvidedDependency(ChecksumGeneratorDependencyProvider::SERVICE_UTIL_ENCRYPTION);
    }
}
