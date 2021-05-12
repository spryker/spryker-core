<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin\SalesReturn;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPreCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 * @method \Spryker\Zed\MerchantSalesReturn\Communication\MerchantSalesReturnCommunicationFactory getFactory()
 */
class MerchantReturnPreCreatePlugin extends AbstractPlugin implements ReturnPreCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets merchant reference in return transfer.
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
