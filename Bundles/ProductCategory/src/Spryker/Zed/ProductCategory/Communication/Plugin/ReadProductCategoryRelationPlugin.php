<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationReadPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 */
class ReadProductCategoryRelationPlugin extends AbstractPlugin implements CategoryRelationReadPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getRelationName()
    {
        return 'Products';
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $productNames = [];
        $productTransferCollection = $this
            ->getFacade()
            ->getAbstractProductsByIdCategory($categoryTransfer->getIdCategory(), $localeTransfer);

        foreach ($productTransferCollection as $productTransfer) {
            $productNames[] = sprintf(
                '%s (%s)',
                $productTransfer->getLocalizedAttributes()[0]->getName(),
                $productTransfer->getSku()
            );
        }

        return $productNames;
    }
}
