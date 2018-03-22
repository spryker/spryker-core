<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\ProductConcrete;

use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\ProductValidity\Dependency\Facade\ProductValidityToProductFacadeInterface;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Traversable;

class ProductConcreteSwitcher implements ProductConcreteSwitcherInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductValidity\Dependency\Facade\ProductValidityToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductValidity\Dependency\Facade\ProductValidityToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductValidityQueryContainerInterface $queryContainer,
        ProductValidityToProductFacadeInterface $productFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @return void
     */
    public function updateProductsValidity(): void
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
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable
     */
    protected function findProductsBecomingActive(): Traversable
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingValid()
            ->find();
    }

    /**
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable
     */
    protected function findProductsBecomingInactive(): Traversable
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingInvalid()
            ->find();
    }

    /**
     * @param \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable $productsBecomingActive
     * @param \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable $productsBecomingInactive
     *
     * @return void
     */
    protected function executeProductPublishTransaction(
        Traversable $productsBecomingActive,
        Traversable $productsBecomingInactive
    ): void {
        $this->activateProductConcretes($productsBecomingActive);
        $this->deactivateProductConcretes($productsBecomingInactive);
    }

    /**
     * @param \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function activateProductConcretes(Traversable $productValidityEntities): void
    {
        foreach ($productValidityEntities as $productValidityEntity) {
            $this->productFacade
                ->activateProductConcrete(
                    $productValidityEntity->getFkProduct()
                );
        }
    }

    /**
     * @param \Orm\Zed\ProductValidity\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function deactivateProductConcretes(Traversable $productValidityEntities): void
    {
        foreach ($productValidityEntities as $productValidityEntity) {
            $this->productFacade
                ->deactivateProductConcrete(
                    $productValidityEntity->getFkProduct()
                );
        }
    }
}
