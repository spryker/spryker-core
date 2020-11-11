<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface;
use Spryker\Service\ProductConfiguration\HashGenerator\ProductConfigurationInstanceHashGenerator;
use Spryker\Service\ProductConfiguration\HashGenerator\ProductConfigurationInstanceHashGeneratorInterface;

class ProductConfigurationServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductConfiguration\HashGenerator\ProductConfigurationInstanceHashGeneratorInterface
     */
    public function createProductConfigurationInstanceHashGenerator(): ProductConfigurationInstanceHashGeneratorInterface
    {
        return new ProductConfigurationInstanceHashGenerator(
            $this->getUtilEncodingService(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface
     */
    public function getUtilTextService(): ProductConfigurationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
