<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Reader;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
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
     * @return array<string>
     */
    public function getLocalizedProductAbstractNamesByCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): array
    {
        $productNames = [];
        $productTransferCollection = $this->productCategoryManager
            ->getAbstractProductTransferCollectionByCategory($categoryTransfer->getIdCategoryOrFail(), $localeTransfer);

        foreach ($productTransferCollection as $productTransfer) {
            $productNames[] = $this->buildProductName($productTransfer);
        }

        return $productNames;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string
     */
    protected function buildProductName(ProductAbstractTransfer $productAbstractTransfer): string
    {
        return sprintf(
            '%s (%s)',
            $productAbstractTransfer->getLocalizedAttributes()[0]->getName(),
            $productAbstractTransfer->getSku(),
        );
    }
}
