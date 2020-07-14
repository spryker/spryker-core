<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipmentGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantShipmentGui\MerchantShipmentGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantShipmentGui\Persistence\MerchantShipmentGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantShipmentGui\MerchantShipmentGuiConfig getConfig()
 */
class MerchantShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantShipmentGui\Dependency\Facade\MerchantShipmentGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade()
    {
        return $this->getProvidedDependency(MerchantShipmentGuiDependencyProvider::FACADE_MERCHANT);
    }
}
