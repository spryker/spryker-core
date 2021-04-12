<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductCategory\Business\Manager\ProductCategoryManagerInterface;

class ProductCategoryReader implements ProductCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Business\Manager\ProductCategoryManagerInterface
     */
    protected $productCategoryManager;

    /**
     * @param \Spryker\Zed\ProductCategory\Business\Manager\ProductCategoryManagerInterface $productCategoryManager
     */
    public function __construct(ProductCategoryManagerInterface $productCategoryManager)
    {
        $this->productCategoryManager = $productCategoryManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getLocalizedProductAbstractNamesByCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array
    {
        $productNames = [];
        $productTransferCollection = $this->productCategoryManager
            ->getAbstractProductTransferCollectionByCategory($categoryTransfer->getIdCategory(), $localeTransfer);

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
