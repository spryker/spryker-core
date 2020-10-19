<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface;

/**
 *
 *
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacade getFacade()
 */
class MerchantCategoryMerchantExpanderPlugin extends AbstractPlugin implements MerchantExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands MerchantTransfer with categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function expand(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantCategoryTransfer = $this->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantTransfer->getIdMerchant())
            );

        $merchantTransfer->setCategories($merchantCategoryTransfer->getCategories());

        return $merchantTransfer;
    }
}
