<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Validity;

use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

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
        $productsBecomingActive = $this->findProductsBecomingActive();
        $productsBecomingInactive = $this->findProductsBecomingInactive();

        if (!$productsBecomingActive->count() && !$productsBecomingInactive->count()) {
            return;
        }

        $this->handleDatabaseTransaction(function () use ($productsBecomingActive, $productsBecomingInactive) {
            $this->executeProductPublishTransaction($productsBecomingActive, $productsBecomingInactive);
        });
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findProductsBecomingActive()
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingValid()
            ->find();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findProductsBecomingInactive()
    {
        return $this
            ->queryContainer
            ->queryProductsBecomingInvalid()
            ->find();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection $productsBecomingActive
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection $productsBecomingInactive
     *
     * @return void
     */
    protected function executeProductPublishTransaction($productsBecomingActive, $productsBecomingInactive)
    {
        $this->setPublished($productsBecomingActive);
        $this->setUnpublished($productsBecomingInactive);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection $productValidityEntities
     *
     * @return void
     */
    protected function setPublished($productValidityEntities)
    {
        foreach ($productValidityEntities as $productLabelEntity) {
            $productLabelEntity
                ->getSpyProduct()
                ->setIsActive(true)
                ->save();
            $this->productConcreteTouch
                ->touchProductConcreteActive($productLabelEntity->getIdProduct());
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductValidity[]|\Propel\Runtime\Collection\ObjectCollection $productValidityEntities
     *
     * @return void
     */
    protected function setUnpublished($productValidityEntities)
    {
        foreach ($productValidityEntities as $productLabelEntity) {
            $productLabelEntity
                ->getSpyProduct()
                ->setIsActive(false)
                ->save();
            $this->productConcreteTouch
                ->touchProductConcreteInactive($productLabelEntity->getIdProduct());
        }
    }
}
