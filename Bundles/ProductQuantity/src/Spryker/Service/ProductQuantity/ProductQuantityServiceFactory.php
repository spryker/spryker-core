<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface;
use Spryker\Service\ProductQuantity\Rounder\ProductQuantityRounder;
use Spryker\Service\ProductQuantity\Rounder\ProductQuantityRounderInterface;

/**
 * @method \Spryker\Service\ProductQuantity\ProductQuantityConfig getConfig()
 */
class ProductQuantityServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ProductQuantity\Rounder\ProductQuantityRounderInterface
     */
    public function createProductQuantityRounder(): ProductQuantityRounderInterface
    {
        return new ProductQuantityRounder(
            $this->getConfig(),
            $this->getUtilQuantityService()
        );
    }

    /**
     * @return \Spryker\Service\ProductQuantity\Dependency\Service\ProductQuantityToUtilQuantityServiceInterface
     */
    public function getUtilQuantityService(): ProductQuantityToUtilQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityDependencyProvider::SERVICE_UTIL_QUANTITY);
    }
}
