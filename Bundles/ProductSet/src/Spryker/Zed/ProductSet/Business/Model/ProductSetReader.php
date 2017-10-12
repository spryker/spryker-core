<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataReaderInterface;
use Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageReaderInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetReader implements ProductSetReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataReaderInterface
     */
    protected $productSetDataReader;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageReaderInterface
     */
    protected $productSetImageReader;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataReaderInterface $productSetDataReader
     * @param \Spryker\Zed\ProductSet\Business\Model\Image\ProductSetImageReaderInterface $productSetImageReader
     */
    public function __construct(
        ProductSetQueryContainerInterface $productSetQueryContainer,
        ProductSetDataReaderInterface $productSetDataReader,
        ProductSetImageReaderInterface $productSetImageReader
    ) {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->productSetDataReader = $productSetDataReader;
        $this->productSetImageReader = $productSetImageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer|null
     */
    public function findProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForRead($productSetTransfer);

        $productSetEntity = $this->productSetQueryContainer
            ->queryProductSetById($productSetTransfer->getIdProductSet())
            ->findOne();

        if (!$productSetEntity) {
            return null;
        }

        $productSetTransfer = $this->mapProductSetEntity($productSetEntity);
        $productSetTransfer = $this->mapProductAbstractSets($productSetTransfer, $productSetEntity);
        $productSetTransfer = $this->mapProductSetData($productSetTransfer, $productSetEntity);
        $productSetTransfer = $this->mapProductSetImageSets($productSetTransfer, $productSetEntity);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForRead(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductSetEntity(SpyProductSet $productSetEntity)
    {
        $productSetTransfer = new ProductSetTransfer();
        $productSetTransfer->fromArray($productSetEntity->toArray(), true);

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductAbstractSets(ProductSetTransfer $productSetTransfer, SpyProductSet $productSetEntity)
    {
        $productAbstractSetEntities = $this->productSetQueryContainer
            ->queryProductAbstractSetsById($productSetEntity->getIdProductSet())
            ->find();

        foreach ($productAbstractSetEntities as $productAbstractSetEntity) {
            $productSetTransfer->addIdProductAbstract($productAbstractSetEntity->getFkProductAbstract());
        }

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductSetData(ProductSetTransfer $productSetTransfer, SpyProductSet $productSetEntity)
    {
        foreach ($productSetEntity->getSpyProductSetDatas() as $productSetDataEntity) {
            $productSetTransfer->addLocalizedData($this->productSetDataReader->getLocalizedData($productSetDataEntity));
        }

        return $productSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function mapProductSetImageSets(ProductSetTransfer $productSetTransfer, SpyProductSet $productSetEntity)
    {
        $productImageSetTransferCollection = $this->productSetImageReader->findProductSetImageSets($productSetEntity->getIdProductSet());
        $productSetTransfer->setImageSets(new ArrayObject($productImageSetTransferCollection));

        return $productSetTransfer;
    }
}
