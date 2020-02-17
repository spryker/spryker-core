<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface;
use Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LabelCreator implements LabelCreatorInterface
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
     * @var \Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface
     */
    protected $productLabelStoreRelationUpdater;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     * @param \Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface $productLabelStoreRelationUpdater
     */
    public function __construct(
        LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter,
        ProductLabelQueryContainerInterface $queryContainer,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager,
        ProductLabelStoreRelationUpdaterInterface $productLabelStoreRelationUpdater
    ) {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
        $this->queryContainer = $queryContainer;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
        $this->productLabelStoreRelationUpdater = $productLabelStoreRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function create(ProductLabelTransfer $productLabelTransfer)
    {
        $this->assertProductLabel($productLabelTransfer);

        $this->handleDatabaseTransaction(function () use ($productLabelTransfer) {
            $this->executeCreateTransaction($productLabelTransfer);
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
            ->requireName()
            ->requireIsActive()
            ->requireIsExclusive();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function executeCreateTransaction(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer = $this->persistLabel($productLabelTransfer);
        $this->persistLocalizedAttributesCollection($productLabelTransfer);
        $this->persistStoreRelation($productLabelTransfer);

        $this->dictionaryTouchManager->touchActive();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function persistLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->createEntityFromTransfer($productLabelTransfer);

        $productLabelEntity->save();

        return $this->updateTransferFromEntity($productLabelTransfer, $productLabelEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function createEntityFromTransfer(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = new SpyProductLabel();
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function updateTransferFromEntity(
        ProductLabelTransfer $productLabelTransfer,
        SpyProductLabel $productLabelEntity
    ) {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        return $productLabelTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function persistLocalizedAttributesCollection(ProductLabelTransfer $productLabelTransfer)
    {
        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $localizedAttributesTransfer) {
            $localizedAttributesTransfer->setFkProductLabel($productLabelTransfer->getIdProductLabel());
        }

        $this
            ->localizedAttributesCollectionWriter
            ->save($productLabelTransfer->getLocalizedAttributesCollection());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function persistStoreRelation(ProductLabelTransfer $productLabelTransfer): void
    {
        if (!$productLabelTransfer->getStoreRelation()) {
            return;
        }

        $productLabelTransfer
            ->getStoreRelation()
            ->setIdEntity($productLabelTransfer->getIdProductLabel());

        $this->productLabelStoreRelationUpdater->update($productLabelTransfer->getStoreRelation());
    }
}
