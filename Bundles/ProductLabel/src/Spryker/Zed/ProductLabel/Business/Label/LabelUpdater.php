<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;
use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LabelUpdater implements LabelUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected $localizedAttributesCollectionWriter;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface
     */
    protected $dictionaryTouchManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface
     */
    protected $productAbstractRelationTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productAbstractRelationTouchManager
     */
    public function __construct(
        LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter,
        ProductLabelQueryContainerInterface $queryContainer,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager,
        ProductAbstractRelationTouchManagerInterface $productAbstractRelationTouchManager
    ) {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
        $this->queryContainer = $queryContainer;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
        $this->productAbstractRelationTouchManager = $productAbstractRelationTouchManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function update(ProductLabelTransfer $productLabelTransfer)
    {
        $this->assertProductLabel($productLabelTransfer);

        $this->handleDatabaseTransaction(function () use ($productLabelTransfer) {
            $this->executeUpdateTransaction($productLabelTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function assertProductLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer
            ->requireIdProductLabel()
            ->requireName()
            ->requireIsActive()
            ->requireIsExclusive()
            ->requirePosition();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function executeUpdateTransaction(ProductLabelTransfer $productLabelTransfer)
    {
        $isModified = $this->persistLabel($productLabelTransfer);

        $this->persistLocalizedAttributesCollection($productLabelTransfer);

        if ($isModified) {
            $this->touchDictionary();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function persistLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->getUpdatedLabelEntity($productLabelTransfer);

        if ($productLabelEntity->isColumnModified(SpyProductLabelTableMap::COL_IS_ACTIVE)) {
            $this->touchLabelProducts($productLabelTransfer->getIdProductLabel());
        }

        if (!$productLabelEntity->isModified()) {
            return false;
        }

        $productLabelEntity->save();
        $this->updateTransferFromEntity($productLabelTransfer, $productLabelEntity);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function getUpdatedLabelEntity(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->getEntityByIdProductLabel($productLabelTransfer->getIdProductLabel());
        $productLabelEntity = $this->updateEntityFromTransfer($productLabelEntity, $productLabelTransfer);

        return $productLabelEntity;
    }

    /**
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function getEntityByIdProductLabel($idProductLabel)
    {
        $productLabelEntity = $this
            ->queryContainer
            ->queryProductLabelById($idProductLabel)
            ->findOne();

        if (!($productLabelEntity instanceof SpyProductLabel)) {
            throw new MissingProductLabelException(sprintf(
                'Could not find product label for id "%s"',
                $idProductLabel
            ));
        }

        return $productLabelEntity;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function updateEntityFromTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ) {
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return void
     */
    protected function updateTransferFromEntity(
        ProductLabelTransfer $productLabelTransfer,
        SpyProductLabel $productLabelEntity
    ) {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function persistLocalizedAttributesCollection(ProductLabelTransfer $productLabelTransfer)
    {
        $this->localizedAttributesCollectionWriter->save($productLabelTransfer->getLocalizedAttributesCollection());
    }

    /**
     * @return void
     */
    protected function touchDictionary()
    {
        $this->dictionaryTouchManager->touchActive();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function touchLabelProducts($idProductLabel)
    {
        $idProductAbstractList = $this->queryContainer
            ->queryProductAbstractRelationsByIdProductLabel($idProductLabel)
            ->select([SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find();

        foreach ($idProductAbstractList as $idProductAbstract) {
            $this->productAbstractRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
        }
    }
}
