<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin\SalesReturn;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 * @method \Spryker\Zed\MerchantSalesReturn\Communication\MerchantSalesReturnCommunicationFactory getFactory()
 */
class MerchantReturnCollectionExpanderPlugin extends AbstractPlugin implements ReturnCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands return collection with merchant data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function expand(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        return $this->getFacade()->expandReturnCollection($returnCollectionTransfer);
    }
}
