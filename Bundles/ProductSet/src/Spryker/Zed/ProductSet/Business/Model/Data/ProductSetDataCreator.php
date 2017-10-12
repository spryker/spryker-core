<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSetData;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetDataCreator implements ProductSetDataCreatorInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected $productSetTouch;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface
     */
    protected $productSetUrlCreator;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlCreatorInterface $productSetUrlCreator
     */
    public function __construct(ProductSetUrlCreatorInterface $productSetUrlCreator)
    {
        $this->productSetUrlCreator = $productSetUrlCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSetData(ProductSetTransfer $productSetTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productSetTransfer) {
            return $this->executeCreateProductSetDataTransaction($productSetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function executeCreateProductSetDataTransaction(ProductSetTransfer $productSetTransfer)
    {
        foreach ($productSetTransfer->getLocalizedData() as $localizedProductSetTransfer) {
            $this->createLocalizedProductSet($localizedProductSetTransfer, $productSetTransfer->getIdProductSet());
        }

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    protected function createLocalizedProductSet(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $this->assertLocalizedProductSetForCreate($localizedProductSetTransfer);

        $localizedProductSetTransfer = $this->createProductSetDataEntity($localizedProductSetTransfer, $idProductSet);
        $localizedProductSetTransfer = $this->productSetUrlCreator->createUrl($localizedProductSetTransfer, $idProductSet);

        return $localizedProductSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     *
     * @return void
     */
    protected function assertLocalizedProductSetForCreate(LocalizedProductSetTransfer $localizedProductSetTransfer)
    {
        $localizedProductSetTransfer->requireLocale();
        $localizedProductSetTransfer->getLocale()->requireIdLocale();
        $localizedProductSetTransfer->getProductSetData()->requireName();
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    protected function createProductSetDataEntity(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $productSetDataTransfer = $localizedProductSetTransfer->getProductSetData();

        $productSetDataTransfer
            ->setFkLocale($localizedProductSetTransfer->getLocale()->getIdLocale())
            ->setFkProductSet($idProductSet);

        $productSetDataEntity = new SpyProductSetData();
        $productSetDataEntity->fromArray($productSetDataTransfer->modifiedToArray());
        $productSetDataEntity->save();

        $productSetDataTransfer->setIdProductSetData($productSetDataEntity->getIdProductSetData());

        return $localizedProductSetTransfer;
    }
}
