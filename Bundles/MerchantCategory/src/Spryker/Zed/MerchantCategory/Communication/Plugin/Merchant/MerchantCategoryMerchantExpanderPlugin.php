<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacade getFacade()
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
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
        $merchantCategoryResponseTransfer = $this->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantTransfer->requireIdMerchant()->getIdMerchant())
            );

        if (!$merchantCategoryResponseTransfer->getIsSuccessful()) {
            return $merchantTransfer;
        }

        $categoryTransfers = [];

        foreach ($merchantCategoryResponseTransfer->getMerchantCategories() as $merchantCategoryTransfer) {
            /**
             * @var \Generated\Shared\Transfer\CategoryTransfer
             */
            $categoryTransfer = $merchantCategoryTransfer->getCategory();

            $categoryTransfers[] = $categoryTransfer;
        }

        $merchantTransfer->setCategories(new ArrayObject($categoryTransfers));

        return $merchantTransfer;
    }
}
