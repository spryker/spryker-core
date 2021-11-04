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
use Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationReaderInterface;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;
use Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface;

class LabelUpdater implements LabelUpdaterInterface
{
    use TransactionTrait;

    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap::COL_IS_ACTIVE
     *
     * @var string
     */
    protected const COL_IS_ACTIVE = 'spy_product_label.is_active';

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected $localizedAttributesCollectionWriter;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationReaderInterface
     */
    protected $productAbstractRelationReader;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface
     */
    protected $dictionaryTouchManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface
     */
    protected $productAbstractRelationTouchManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface
     */
    protected $productLabelEntityManager;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface
     */
    protected $storeRelationUpdater;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter
     * @param \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationReaderInterface $productAbstractRelationReader
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     * @param \Spryker\Zed\ProductLabel\Business\Touch\ProductAbstractRelationTouchManagerInterface $productAbstractRelationTouchManager
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $productLabelEntityManager
     * @param \Spryker\Zed\ProductLabel\Business\Label\ProductLabelStoreRelation\ProductLabelStoreRelationUpdaterInterface $storeRelationUpdater
     */
    public function __construct(
        LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter,
        ProductAbstractRelationReaderInterface $productAbstractRelationReader,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager,
        ProductAbstractRelationTouchManagerInterface $productAbstractRelationTouchManager,
        ProductLabelEntityManagerInterface $productLabelEntityManager,
        ProductLabelStoreRelationUpdaterInterface $storeRelationUpdater
    ) {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
        $this->productAbstractRelationReader = $productAbstractRelationReader;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
        $this->productAbstractRelationTouchManager = $productAbstractRelationTouchManager;
        $this->productLabelEntityManager = $productLabelEntityManager;
        $this->storeRelationUpdater = $storeRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function update(ProductLabelTransfer $productLabelTransfer): void
    {
        $this->assertProductLabel($productLabelTransfer);

        $this->getTransactionHandler()->handleTransaction(function () use ($productLabelTransfer) {
            $this->executeUpdateTransaction($productLabelTransfer);
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
    protected function executeUpdateTransaction(ProductLabelTransfer $productLabelTransfer): void
    {
        $modifiedColumns = $this->productLabelEntityManager->updateProductLabel($productLabelTransfer);

        $this->persistLocalizedAttributesCollection($productLabelTransfer);
        $this->persistStoreRelation($productLabelTransfer);

        if ($modifiedColumns !== []) {
            $this->touchDictionary();
        }

        if (in_array(static::COL_IS_ACTIVE, $modifiedColumns, true)) {
            $this->touchLabelProducts($productLabelTransfer->getIdProductLabel());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function persistLocalizedAttributesCollection(ProductLabelTransfer $productLabelTransfer): void
    {
        $this->localizedAttributesCollectionWriter->save($productLabelTransfer->getLocalizedAttributesCollection());
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

        $productLabelTransfer
            ->getStoreRelation()
            ->setIdEntity($productLabelTransfer->getIdProductLabel());

        $this->storeRelationUpdater->update($storeRelationTransfer);
    }

    /**
     * @return void
     */
    protected function touchDictionary(): void
    {
        $this->dictionaryTouchManager->touchActive();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    protected function touchLabelProducts(int $idProductLabel): void
    {
        $productAbstractIds = $this->productAbstractRelationReader->findIdsProductAbstractByIdProductLabel($idProductLabel);

        foreach ($productAbstractIds as $idProductAbstract) {
            $this->productAbstractRelationTouchManager->touchActiveByIdProductAbstract($idProductAbstract);
        }
    }
}
