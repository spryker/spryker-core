<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Validity;

use Orm\Zed\Product\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface;
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
     * @var \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface
     */
    protected $productConcreteTouch;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface $productConcreteTouch
     */
    public function __construct(
        ProductQueryContainerInterface $queryContainer,
        ProductConcreteTouchInterface $productConcreteTouch
    ) {
        $this->queryContainer = $queryContainer;
        $this->productConcreteTouch = $productConcreteTouch;
    }

    /**
     * @return void
     */
    public function updateProductsValidity()
    {
        if (!$this->willProductsUpdate()) {
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
    protected function willProductsUpdate(): bool
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
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findProductsBecomingActive(): \Traversable
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingValid()
            ->find();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection
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
    protected function executeProductPublishTransaction(Traversable $productsBecomingActive, Traversable $productsBecomingInactive)
    {
        $this->setPublished($productsBecomingActive);
        $this->setUnpublished($productsBecomingInactive);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function setPublished(Traversable $productValidityEntities)
    {
        foreach ($productValidityEntities as $productLabelEntity) {
            $productLabelEntity
                ->getSpyProduct()
                ->setIsActive(true)
                ->save();
            $this->productConcreteTouch
                ->touchProductConcreteActive($productLabelEntity->getFkProduct());
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Traversable $productValidityEntities
     *
     * @return void
     */
    protected function setUnpublished(Traversable $productValidityEntities)
    {
        foreach ($productValidityEntities as $productLabelEntity) {
            $productLabelEntity
                ->getSpyProduct()
                ->setIsActive(false)
                ->save();
            $this->productConcreteTouch
                ->touchProductConcreteInactive($productLabelEntity->getFkProduct());
        }
    }
}
