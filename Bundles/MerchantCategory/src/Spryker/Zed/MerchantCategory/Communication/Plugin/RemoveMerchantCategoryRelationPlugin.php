<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Communication\Plugin;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
 */
class RemoveMerchantCategoryRelationPlugin extends AbstractPlugin implements CategoryRelationDeletePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $merchantCategoryCriteriaTransfer = new MerchantCategoryCriteriaTransfer();
        $merchantCategoryCriteriaTransfer->setCategoryIds([$idCategory]);

        $this
            ->getFacade()
            ->delete($merchantCategoryCriteriaTransfer);
    }
}
