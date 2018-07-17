<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdManager;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdManagerInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
     */
    public function getMinimumOrderValueFacade(): MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDependencyProvider::FACADE_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdManagerInterface
     */
    public function createMerchantRelationshipThresholdManager(): MerchantRelationshipThresholdManagerInterface
    {
        return new MerchantRelationshipThresholdManager(
            $this->getMinimumOrderValueFacade(),
            $this->getEntityManager()
        );
    }
}
