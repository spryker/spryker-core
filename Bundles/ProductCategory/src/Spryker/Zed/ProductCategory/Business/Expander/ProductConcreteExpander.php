<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Expander;

use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected $productCategoryRepository;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $productCategoryRepository
     */
    public function __construct(ProductCategoryRepositoryInterface $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteWithProductCategories(array $productConcreteTransfers): array
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productCategoryCollectionTransfer = $this->findProductCategories($productConcreteTransfer);

            if ($productCategoryCollectionTransfer !== null) {
                $productConcreteTransfer->setProductCategories($productCategoryCollectionTransfer->getProductCategories());
            }
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer|null
     */
    protected function findProductCategories(ProductConcreteTransfer $productConcreteTransfer): ?ProductCategoryCollectionTransfer
    {
        if ($productConcreteTransfer->getFkProductAbstract() === null) {
            return null;
        }

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->setProductAbstractIds([$productConcreteTransfer->getFkProductAbstract()]);

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        return $this->productCategoryRepository->getProductCategoryCollection($productCategoryCriteriaTransfer);
    }
}
