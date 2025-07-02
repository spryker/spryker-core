<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander;

use ArrayObject;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductConcreteClassExpander implements ProductConcreteClassExpanderInterface
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface
     */
    protected $selfServicePortalRepository;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
        $this->selfServicePortalRepository = $selfServicePortalRepository;
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

        $productClassesByProductConcreteId = $this->selfServicePortalRepository
            ->getProductClassesByProductConcreteIds($productConcreteIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (isset($productClassesByProductConcreteId[$productConcreteTransfer->getIdProductConcreteOrFail()])) {
                $productConcreteTransfer->setProductClasses(new ArrayObject($productClassesByProductConcreteId[$productConcreteTransfer->getIdProductConcreteOrFail()]));
            }
        }

        return $productConcreteTransfers;
    }
}
