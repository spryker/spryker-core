<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStock\MerchantStockConfig getConfig()
 */
class MerchantStockMerchantExpanderPlugin extends AbstractPlugin implements MerchantExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands MerchantTransfer with related stocks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function expand(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant());

        $stockCollectionTransfer = $this->getFacade()
            ->get($merchantStockCriteriaTransfer);

        return $merchantTransfer->setStocks($stockCollectionTransfer->getStocks());
    }
}
