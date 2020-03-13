<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStock\MerchantStockConfig getConfig()
 */
class MerchantStockMerchantPostCreatePlugin extends AbstractPlugin implements MerchantPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates default stock for the provided merchant.
     * - Returns Merchant.stocks with related stocks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFacade()->createDefaultMerchantStock($merchantTransfer);
    }
}
