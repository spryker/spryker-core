<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductAbstractSet;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetExpander implements ProductSetExpanderInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface
     */
    protected $productSetEntityReader;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected $productSetTouch;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface $productSetEntityReader
     * @param \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface $productSetTouch
     */
    public function __construct(ProductSetEntityReaderInterface $productSetEntityReader, ProductSetTouchInterface $productSetTouch)
    {
        $this->productSetTouch = $productSetTouch;
        $this->productSetEntityReader = $productSetEntityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function extendProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForExtension($productSetTransfer);

        return $this->handleDatabaseTransaction(function () use ($productSetTransfer) {
            return $this->executeExtendProductSetTransaction($productSetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForExtension(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function executeExtendProductSetTransaction(ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity = $this->productSetEntityReader->getProductSetEntity($productSetTransfer);

        $this->touchProductSet($productSetTransfer);

        return $this->saveProductSetEntity($productSetEntity, $productSetTransfer);
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

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function saveProductSetEntity(SpyProductSet $productSetEntity, ProductSetTransfer $productSetTransfer)
    {
        $idProductAbstracts = $productSetTransfer->getIdProductAbstracts();
        $existingProductAbstractSets = $this->getExistingIdProductAbstracts($productSetEntity);
        $productSetTransfer->setIdProductAbstracts($existingProductAbstractSets);

        $position = count($existingProductAbstractSets) + 1;
        foreach ($idProductAbstracts as $idProductAbstract) {
            if (in_array($idProductAbstract, $existingProductAbstractSets)) {
                continue;
            }

            $productAbstractSetEntity = $this->createProductAbstractSetEntity($idProductAbstract, $position);
            $productSetEntity->addSpyProductAbstractSet($productAbstractSetEntity);
            $productSetTransfer->addIdProductAbstract($idProductAbstract);
            $position++;
        }

        $productSetEntity->save();

        return $productSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return array
     */
    protected function getExistingIdProductAbstracts(SpyProductSet $productSetEntity)
    {
        $existingProductAbstractSets = [];

        foreach ($productSetEntity->getSpyProductAbstractSets() as $productAbstractSetEntity) {
            $existingProductAbstractSets[] = $productAbstractSetEntity->getFkProductAbstract();
        }

        return $existingProductAbstractSets;
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
}
