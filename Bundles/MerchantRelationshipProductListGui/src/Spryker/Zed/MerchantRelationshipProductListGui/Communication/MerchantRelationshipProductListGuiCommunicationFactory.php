<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListDataProvider;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 */
class MerchantRelationshipProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Communication\DataProvider\ProductListDataProvider
     */
    public function createPluginListMerchantRelationDataProvider(): ProductListDataProvider
    {
        return new ProductListDataProvider(
            $this->getMerchantRelationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationFacade(): MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(
            MerchantRelationshipProductListGuiDependencyProvider::FACADE_MERCHANT_RELATION
        );
    }
}
