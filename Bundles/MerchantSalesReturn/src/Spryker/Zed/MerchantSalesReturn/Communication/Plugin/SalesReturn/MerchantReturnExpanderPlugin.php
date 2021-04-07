<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin\SalesReturn;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 * @method \Spryker\Zed\MerchantSalesReturn\Communication\MerchantSalesReturnCommunicationFactory getFactory()
 */
class MerchantReturnExpanderPlugin extends AbstractPlugin implements ReturnExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `Return` transfer object witg merchant orders.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expand(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        return $this->getFacade()->expand($returnTransfer);
    }
}
