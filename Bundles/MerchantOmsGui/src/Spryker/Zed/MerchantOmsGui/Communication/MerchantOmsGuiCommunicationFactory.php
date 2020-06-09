<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOmsGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantOmsGui\Dependency\Facade\MerchantOmsGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\MerchantOmsGui\MerchantOmsGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOmsGui\Persistence\MerchantOmsGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantOmsGui\MerchantOmsGuiConfig getConfig()
 */
class MerchantOmsGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantOmsGui\Dependency\Facade\MerchantOmsGuiToMerchantOmsFacadeInterface
     */
    public function getMerchantOmsFacade(): MerchantOmsGuiToMerchantOmsFacadeInterface
    {
        return $this->getProvidedDependency(MerchantOmsGuiDependencyProvider::FACADE_MERCHANT_OMS);
    }
}
