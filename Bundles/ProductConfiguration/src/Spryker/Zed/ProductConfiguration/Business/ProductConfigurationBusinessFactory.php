<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpander;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface;
use Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface;
use Spryker\Zed\ProductConfiguration\ProductConfigurationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface
     */
    public function createProductConfigurationGroupKeyItemExpander(): ProductConfigurationGroupKeyItemExpanderInterface
    {
        return new ProductConfigurationGroupKeyItemExpander(
            $this->getUtilEncodingService(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface
     */
    public function getUtilTextService(): ProductConfigurationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
