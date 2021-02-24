<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPreCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\Persistence\MerchantSalesReturnQueryContainer getQueryContainer()
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 */
class MerchantReturnPreCreatePlugin implements ReturnPreCreatePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function preCreate(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        return $this->getFacade()->preCreate($returnTransfer);
    }
}
