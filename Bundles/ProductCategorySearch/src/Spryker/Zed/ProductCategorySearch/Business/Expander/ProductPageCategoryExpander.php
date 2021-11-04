<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Expander;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface;

class ProductPageCategoryExpander implements ProductPageCategoryExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface
     */
    protected $productCategorySearchRepository;

    /**
     * @param \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface $productCategorySearchRepository
     */
    public function __construct(ProductCategorySearchRepositoryInterface $productCategorySearchRepository)
    {
        $this->productCategorySearchRepository = $productCategorySearchRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageWithCategories(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        $payloadTransfers = $this->setProductCategories(
            $productPageLoadTransfer->getProductAbstractIds(),
            $productPageLoadTransfer->getPayloadTransfers(),
        );

        $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);

        return $productPageLoadTransfer;
    }

    /**
     * @param array<int> $productAbstractIds
     * @param array<\Generated\Shared\Transfer\ProductPayloadTransfer> $payloadTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPayloadTransfer>
     */
    protected function setProductCategories(array $productAbstractIds, array $payloadTransfers): array
    {
        $mappedProductCategoryEntities = $this->productCategorySearchRepository
            ->getMappedProductCategoriesByIdProductAbstractAndStore($productAbstractIds);

        foreach ($payloadTransfers as $payloadTransfer) {
            if (!isset($mappedProductCategoryEntities[$payloadTransfer->getIdProductAbstract()])) {
                continue;
            }

            $categories = $mappedProductCategoryEntities[$payloadTransfer->getIdProductAbstract()];
            $payloadTransfer->setCategories($categories);
        }

        return $payloadTransfers;
    }
}
