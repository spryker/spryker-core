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
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSaverInterface;
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
     * @var \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSaverInterface
     */
    protected $productSetImageSaver;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataCreatorInterface $productSetDataCreator
     * @param \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface $productSetTouch
     * @param \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageSaverInterface $productSetImageSaver
     */
    public function __construct(
        ProductSetDataCreatorInterface $productSetDataCreator,
        ProductSetTouchInterface $productSetTouch,
        ProductSetImageSaverInterface $productSetImageSaver
    ) {
        $this->productSetTouch = $productSetTouch;
        $this->productSetDataCreator = $productSetDataCreator;
        $this->productSetImageSaver = $productSetImageSaver;
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
        $productSetTransfer = $this->productSetImageSaver->saveImageSets($productSetTransfer);

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
        $productSetEntity->fromArray($productSetTransfer->modifiedToArray());

        $idProductAbstracts = array_values($productSetTransfer->getIdProductAbstracts());
        foreach ($idProductAbstracts as $index => $idProductAbstract) {
            $position = $index + 1;
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
        $this->productSetTouch->touchProductSetByStatus($productSetTransfer);
    }
}
