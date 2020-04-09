<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Updater;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface;

class RelatedProductUpdater implements RelatedProductUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface
     */
    protected $relatedProductReader;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface
     */
    protected $productRelationEntityManager;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface $relatedProductReader
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface $productRelationEntityManager
     */
    public function __construct(
        RelatedProductReaderInterface $relatedProductReader,
        ProductRelationEntityManagerInterface $productRelationEntityManager
    ) {
        $this->relatedProductReader = $relatedProductReader;
        $this->productRelationEntityManager = $productRelationEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    public function updateAllRelatedProducts(ProductRelationTransfer $productRelationTransfer): void
    {
        $productRelationTransfer->requireIdProductRelation();
        
        foreach ($this->relatedProductReader->getRelatedProducts($productRelationTransfer) as $relatedProductTransfers) {
            $productAbstractIds = $this->collectProductAbstractIds($relatedProductTransfers);

            $this->productRelationEntityManager->saveRelatedProducts(
                $productAbstractIds,
                $productRelationTransfer->getIdProductRelation()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $relatedProductTransfers
     *
     * @return int[]
     */
    protected function collectProductAbstractIds(array $relatedProductTransfers): array
    {
        $productAbstractIds = [];

        foreach ($relatedProductTransfers as $relatedProductTransfer) {
            $productAbstractIds[] = $relatedProductTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }
}
