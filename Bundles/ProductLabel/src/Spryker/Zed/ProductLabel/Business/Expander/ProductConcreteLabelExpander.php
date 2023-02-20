<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Expander;

use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;

class ProductConcreteLabelExpander implements ProductConcreteLabelExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface
     */
    protected $productLabelRepository;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     */
    public function __construct(ProductLabelRepositoryInterface $productLabelRepository)
    {
        $this->productLabelRepository = $productLabelRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithLabels(array $productConcreteTransfers): array
    {
        if (!$productConcreteTransfers) {
            return $productConcreteTransfers;
        }

        $productAbstractIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productAbstractIds[] = $productConcreteTransfer->getFkProductAbstract();
        }

        $productAbstractIds = array_unique($productAbstractIds);

        $productLabelProductAbstractTransfers = $this->productLabelRepository->getProductLabelProductAbstractsByProductAbstractIds($productAbstractIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productLabelProductAbstractTransfers as $productLabelProductAbstractTransfer) {
                if ($productConcreteTransfer->getFkProductAbstract() === $productLabelProductAbstractTransfer->getFkProductAbstract()) {
                    $productConcreteTransfer->addProductLabel($productLabelProductAbstractTransfer->getProductLabel());
                }
            }
        }

        return $productConcreteTransfers;
    }
}
