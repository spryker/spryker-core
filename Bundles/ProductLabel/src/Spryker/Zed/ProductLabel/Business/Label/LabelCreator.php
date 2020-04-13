<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface;
use Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface;

class LabelCreator implements LabelCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected $localizedAttributesCollectionWriter;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface
     */
    protected $productLabelEntityManager;

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
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $productLabelEntityManager
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     * @param \Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface $productLabelStoreRelationUpdater
     */
    public function __construct(
        LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter,
        ProductLabelEntityManagerInterface $productLabelEntityManager,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager,
        ProductLabelStoreRelationUpdaterInterface $productLabelStoreRelationUpdater
    ) {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
        $this->productLabelEntityManager = $productLabelEntityManager;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
        $this->productLabelStoreRelationUpdater = $productLabelStoreRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function create(ProductLabelTransfer $productLabelTransfer): void
    {
        $this->assertProductLabel($productLabelTransfer);

        $this->getTransactionHandler()->handleTransaction(function () use ($productLabelTransfer) {
            $this->executeCreateTransaction($productLabelTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function assertProductLabel(ProductLabelTransfer $productLabelTransfer): void
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
    protected function executeCreateTransaction(ProductLabelTransfer $productLabelTransfer): void
    {
        $productLabelTransfer = $this->productLabelEntityManager->createProductLabel($productLabelTransfer);
        $this->persistLocalizedAttributesCollection($productLabelTransfer);
        $this->persistStoreRelation($productLabelTransfer);

        $this->dictionaryTouchManager->touchActive();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function persistLocalizedAttributesCollection(ProductLabelTransfer $productLabelTransfer): void
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
        $storeRelationTransfer = $productLabelTransfer->getStoreRelation();

        if (!$storeRelationTransfer) {
            return;
        }

        $storeRelationTransfer
            ->setIdEntity($productLabelTransfer->getIdProductLabel());

        $this->productLabelStoreRelationUpdater->update($storeRelationTransfer);
    }
}
