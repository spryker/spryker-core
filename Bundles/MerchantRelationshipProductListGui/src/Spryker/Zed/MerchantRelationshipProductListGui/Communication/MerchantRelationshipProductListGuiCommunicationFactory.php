<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 */
class MerchantRelationshipProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationFacade(): MerchantRelationshipGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(
            MerchantRelationshipProductListGuiDependencyProvider::FACADE_MERCHANT_RELATION
        );
    }
}
