<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteMerchantPortalGui\Communication;

use Spryker\Zed\CartNoteMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpander;
use Spryker\Zed\CartNoteMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CartNoteMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CartNoteMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface
     */
    public function createMerchantOrderItemTableExpander(): MerchantOrderItemTableExpanderInterface
    {
        return new MerchantOrderItemTableExpander();
    }
}
