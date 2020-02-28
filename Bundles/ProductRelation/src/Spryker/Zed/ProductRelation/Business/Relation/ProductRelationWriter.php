<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException;
use Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class ProductRelationWriter implements ProductRelationWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductRelation\ProductRelationConfig
     */
    protected $productRelationConfig;

    /**
     * @var \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface
     */
    protected $relatedProductReader;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductRelation\ProductRelationConfig $productRelationConfig
     * @param \Spryker\Zed\ProductRelation\Business\Relation\Reader\RelatedProductReaderInterface $relatedProductReader
     */
    public function __construct(
        ProductRelationToTouchInterface $touchFacade,
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToUtilEncodingInterface $utilEncodingService,
        ProductRelationConfig $productRelationConfig,
        RelatedProductReaderInterface $relatedProductReader
    ) {
        $this->touchFacade = $touchFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->productRelationConfig = $productRelationConfig;
        $this->relatedProductReader = $relatedProductReader;
    }

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation)
    {
        $productRelationEntity = $this->findProductRelationEntityById($idProductRelation);

        if ($productRelationEntity === null) {
            throw new ProductRelationNotFoundException(
                sprintf(
                    'Product relation with id "%d" not found.',
                    $productRelationEntity
                )
            );
        }

        $deleted = $this->getTransactionHandler()->handleTransaction(function () use ($productRelationEntity) {
            return $this->executeDeleteProductRelationTransaction($productRelationEntity);
        });

        return $deleted;
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return bool
     */
    protected function executeDeleteProductRelationTransaction(SpyProductRelation $productRelationEntity)
    {
        $this->removeRelatedProductsByIdRelation($productRelationEntity->getIdProductRelation());

        $this->touchFacade->touchDeleted(
            ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION,
            $productRelationEntity->getFkProductAbstract()
        );

        $productRelationEntity->delete();

        return $productRelationEntity->isDeleted();
    }

    /**
     * @param int $idProductRelation
     *
     * @return int
     */
    protected function removeRelatedProductsByIdRelation($idProductRelation)
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationProductAbstractByIdProductRelation($idProductRelation)
            ->delete();
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation|null
     */
    protected function findProductRelationEntityById($idProductRelation)
    {
        $productRelationEntity = $this->productRelationQueryContainer
            ->queryProductRelationByIdProductRelation($idProductRelation)
            ->findOne();

        return $productRelationEntity;
    }
}
