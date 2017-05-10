<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductAbstractSet;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreatorInterface;
use Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetCreator implements ProductSetCreatorInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreatorInterface
     */
    protected $productSetDataCreator;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected $productSetTouch;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreatorInterface $productSetDataCreator
     * @param \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface $productSetTouch
     */
    public function __construct(ProductSetDataCreatorInterface $productSetDataCreator, ProductSetTouchInterface $productSetTouch)
    {
        $this->productSetTouch = $productSetTouch;
        $this->productSetDataCreator = $productSetDataCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSet(ProductSetTransfer $productSetTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productSetTransfer) {
            return $this->executeCreateProductSetTransaction($productSetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function executeCreateProductSetTransaction(ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity = $this->createProductSetEntity($productSetTransfer);

        $idProductSet = $productSetEntity->getIdProductSet();
        $productSetTransfer->setIdProductSet($idProductSet);

        $productSetTransfer = $this->productSetDataCreator->createProductSetData($productSetTransfer);

        $this->touchProductSet($productSetTransfer);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSet
     */
    protected function createProductSetEntity(ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity = new SpyProductSet();
        $productSetEntity->fromArray($productSetTransfer->toArray());

        foreach ($productSetTransfer->getIdProductAbstracts() as $position => $idProductAbstract) {
            $productAbstractSetEntity = $this->createProductAbstractSetEntity($idProductAbstract, $position);
            $productSetEntity->addSpyProductAbstractSet($productAbstractSetEntity);
        }

        $productSetEntity->save();

        return $productSetEntity;
    }

    /**
     * @param int $idProductAbstract
     * @param int $position
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductAbstractSet
     */
    protected function createProductAbstractSetEntity($idProductAbstract, $position)
    {
        $productAbstractSetEntity = new SpyProductAbstractSet();
        $productAbstractSetEntity
            ->setFkProductAbstract($idProductAbstract)
            ->setPosition($position);

        return $productAbstractSetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function touchProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->productSetTouch->touchProductSetActive($productSetTransfer);
    }

}
