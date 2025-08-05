<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductClassReaderInterface;

class ProductConcreteClassExpander implements ProductConcreteClassExpanderInterface
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductClassReaderInterface
     */
    protected $productClassReader;

    public function __construct(ProductClassReaderInterface $productClassReader)
    {
        $this->productClassReader = $productClassReader;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithProductClasses(array $productConcreteTransfers): array
    {
        $productConcreteIds = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if ($productConcreteTransfer->getIdProductConcrete()) {
                $productConcreteIds[] = $productConcreteTransfer->getIdProductConcrete();
            }
        }

        if (!$productConcreteIds) {
            return $productConcreteTransfers;
        }

        $productClassesByProductConcreteId = $this->getProductClassesByProductConcreteIds($productConcreteIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (isset($productClassesByProductConcreteId[$productConcreteTransfer->getIdProductConcreteOrFail()])) {
                $productConcreteTransfer->setProductClasses(new ArrayObject($productClassesByProductConcreteId[$productConcreteTransfer->getIdProductConcreteOrFail()]));
            }
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    protected function getProductClassesByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productClassReader->getProductClassesByProductConcreteIds($productConcreteIds);
    }
}
