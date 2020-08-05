<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOptionMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpander;
use Spryker\Zed\ProductOptionMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface;

class ProductOptionMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOptionMerchantPortalGui\Communication\Expander\MerchantOrderItemTableExpanderInterface
     */
    public function createMerchantOrderItemTableExpander(): MerchantOrderItemTableExpanderInterface
    {
        return new MerchantOrderItemTableExpander();
    }
}
