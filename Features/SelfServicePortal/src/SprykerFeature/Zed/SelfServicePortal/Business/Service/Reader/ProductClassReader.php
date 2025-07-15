<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader;

use Generated\Shared\Transfer\ProductClassConditionsTransfer;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductClassReader implements ProductClassReaderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexerInterface $productClassIndexer
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository,
        protected ProductClassIndexerInterface $productClassIndexer
    ) {
        $this->selfServicePortalRepository = $selfServicePortalRepository;
        $this->productClassIndexer = $productClassIndexer;
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesByProductConcreteIds(array $productConcreteIds): array
    {
        $productClassConditionsTransfer = (new ProductClassConditionsTransfer())
            ->setProductConcreteIds($productConcreteIds);

        $productClassCriteriaTransfer = (new ProductClassCriteriaTransfer())
            ->setProductClassConditions($productClassConditionsTransfer);

        $productClassCollectionTransfer = $this->selfServicePortalRepository
            ->getProductClassCollection($productClassCriteriaTransfer);

        return $this->productClassIndexer->getProductClassesIndexedByProductConcreteId(
            $productClassCollectionTransfer->getProductClasses()->getArrayCopy(),
        );
    }
}
