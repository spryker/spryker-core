<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Reader;

use Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generator;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class RelatedProductReader implements RelatedProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @var \Spryker\Zed\ProductRelation\ProductRelationConfig
     */
    protected $productRelationConfig;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     * @param \Spryker\Zed\ProductRelation\ProductRelationConfig $productRelationConfig
     */
    public function __construct(
        ProductRelationRepositoryInterface $productRelationRepository,
        ProductRelationConfig $productRelationConfig
    ) {
        $this->productRelationRepository = $productRelationRepository;
        $this->productRelationConfig = $productRelationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generator|\Generated\Shared\Transfer\ProductAbstractTransfer[][]
     */
    public function getRelatedProducts(ProductRelationTransfer $productRelationTransfer): Generator
    {
        $count = $this->productRelationRepository->getRelatedProductsCount($productRelationTransfer);

        $limit = $this->productRelationConfig->getRelatedProductsReadChunkSize();

        for ($offset = 0; $offset <= $count; $offset += $limit) {
            $productRelationCriteriaFilterTransfer = $this->createProductRelationCriteriaFilterTransfer(
                $productRelationTransfer,
                $limit,
                $offset
            );

            yield $this->productRelationRepository
                ->getRelatedProductsByCriteriaFilter($productRelationCriteriaFilterTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     * @param int $limit
     * @param int $offset
     *
     * @return \Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer
     */
    protected function createProductRelationCriteriaFilterTransfer(
        ProductRelationTransfer $productRelationTransfer,
        int $limit,
        int $offset
    ): ProductRelationCriteriaFilterTransfer {
        return (new ProductRelationCriteriaFilterTransfer())
            ->setProductRelation($productRelationTransfer)
            ->setLimit($limit)
            ->setOffset($offset);
    }
}
