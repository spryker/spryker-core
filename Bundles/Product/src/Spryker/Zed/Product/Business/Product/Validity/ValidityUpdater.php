<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Validity;

use Orm\Zed\Product\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Traversable;

class ValidityUpdater implements ValidityUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface
     */
    protected $productConcreteActivator;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface $productConcreteActivator
     */
    public function __construct(
        ProductQueryContainerInterface $queryContainer,
        ProductConcreteActivatorInterface $productConcreteActivator
    ) {
        $this->queryContainer = $queryContainer;
        $this->productConcreteActivator = $productConcreteActivator;
    }

    /**
     * @return void
     */
    public function updateProductsValidity()
    {
        if (!$this->hasTriggeredProductValidity()) {
            return;
        }

        $this->handleDatabaseTransaction(function () {
            $productsBecomingActive = $this->findProductsBecomingActive();
            $productsBecomingInactive = $this->findProductsBecomingInactive();

            $this->executeProductPublishTransaction($productsBecomingActive, $productsBecomingInactive);
        });
    }

    /**
     * @return bool
     */
    protected function hasTriggeredProductValidity(): bool
    {
        $willProductsBecomeValid = $this
            ->queryContainer
            ->queryProductsBecomingValid()
            ->select([SpyProductValidityTableMap::COL_ID_PRODUCT_VALIDITY])
            ->findOne();

        if ($willProductsBecomeValid) {
            return true;
        }

        $willProductsBecomeInvalid = $this
            ->queryContainer
            ->queryProductsBecomingInvalid()
            ->select([SpyProductValidityTableMap::COL_ID_PRODUCT_VALIDITY])
            ->findOne();

        if ($willProductsBecomeInvalid) {
            return true;
        }

        return false;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable
     */
    protected function findProductsBecomingActive(): \Traversable
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingValid()
            ->find();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable
     */
    protected function findProductsBecomingInactive(): \Traversable
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingInvalid()
            ->find();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productsBecomingActive
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productsBecomingInactive
     *
     * @return void
     */
    protected function executeProductPublishTransaction(
        Traversable $productsBecomingActive,
        Traversable $productsBecomingInactive
    ) {
        $this->activateProductConcretes($productsBecomingActive);
        $this->deactivateProductConcretes($productsBecomingInactive);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function activateProductConcretes(Traversable $productValidityEntities)
    {
        foreach ($productValidityEntities as $productValidityEntity) {
            $this->productConcreteActivator
                ->activateProductConcrete($productValidityEntity->getFkProduct());
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function deactivateProductConcretes(Traversable $productValidityEntities)
    {
        foreach ($productValidityEntities as $productValidityEntity) {
            $this->productConcreteActivator
                ->deactivateProductConcrete($productValidityEntity->getFkProduct());
        }
    }
}
