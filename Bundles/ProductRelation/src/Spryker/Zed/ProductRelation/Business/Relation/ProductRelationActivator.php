<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelation;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductRelationActivator implements ProductRelationActivatorInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchInterface $touchFacade
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToTouchInterface $touchFacade
    ) {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activate($idProductRelation)
    {
        $productRelationEntity = $this->findProductRelationById($idProductRelation);

        if ($productRelationEntity === null) {
            throw new ProductRelationNotFoundException(
                sprintf(
                    'Product relation with id "%d" not found',
                    $idProductRelation
                )
            );
        }

        $this->handleDatabaseTransaction(function () use ($productRelationEntity) {
            $this->executeActivateRelationTransaction($productRelationEntity);
        });
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return void
     */
    protected function executeActivateRelationTransaction(SpyProductRelation $productRelationEntity)
    {
        $productRelationEntity->setIsActive(true);
        $productRelationEntity->save();

        $this->touchActiveRelation($productRelationEntity->getFkProductAbstract());
    }

    /**
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivate($idProductRelation)
    {
        $productRelationEntity = $this->findProductRelationById($idProductRelation);

        if ($productRelationEntity === null) {
            throw new ProductRelationNotFoundException(
                sprintf(
                    'Product relation with id "%d" not found.',
                    $idProductRelation
                )
            );
        }

        $this->handleDatabaseTransaction(function () use ($productRelationEntity) {
            $this->executeDeactivateRelationTransaction($productRelationEntity);
        });
    }

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelation $productRelationEntity
     *
     * @return void
     */
    protected function executeDeactivateRelationTransaction(SpyProductRelation $productRelationEntity)
    {
        $productRelationEntity->setIsActive(false);
        $productRelationEntity->save();

        $this->touchActiveRelation($productRelationEntity->getFkProductAbstract());
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelation|null
     */
    protected function findProductRelationById($idProductRelation)
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationByIdProductRelation($idProductRelation)
            ->findOne();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function touchActiveRelation($idProductAbstract)
    {
        return $this->touchFacade->touchActive(ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION, $idProductAbstract);
    }
}
