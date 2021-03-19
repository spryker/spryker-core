<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 */
class MerchantSalesReturnGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantSalesReturnGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnGuiDependencyProvider::FACADE_MERCHANT);
    }
}
