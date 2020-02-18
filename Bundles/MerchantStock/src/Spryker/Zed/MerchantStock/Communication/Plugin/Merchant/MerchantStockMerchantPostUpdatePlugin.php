<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface getFacade()
 */
class MerchantStockMerchantPostUpdatePlugin extends AbstractPlugin implements MerchantPostUpdatePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function postUpdate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFacade()->updateMerchantStocksByMerchant($merchantTransfer);
    }
}
