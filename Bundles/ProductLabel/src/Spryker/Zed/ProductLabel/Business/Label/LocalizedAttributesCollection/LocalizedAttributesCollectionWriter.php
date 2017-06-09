<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LocalizedAttributesCollectionWriter implements LocalizedAttributesCollectionWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface
     */
    protected $dictionaryTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager
    ) {
        $this->queryContainer = $queryContainer;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesTransferCollection
     *
     * @return void
     */
    public function set(ArrayObject $localizedAttributesTransferCollection)
    {
        $this->handleDatabaseTransaction(function () use ($localizedAttributesTransferCollection) {
            $this->executeSetTransaction($localizedAttributesTransferCollection);
        });
    }

    /**
     * @param \ArrayObject $localizedAttributesTransferCollection
     *
     * @return void
     */
    protected function executeSetTransaction(ArrayObject $localizedAttributesTransferCollection)
    {
        $hasModified = false;

        foreach ($localizedAttributesTransferCollection as $localizedAttributesTransfer) {
            $this->assertLocalizedAttributes($localizedAttributesTransfer);
            $hasModified |= $this->persistLocalizedAttributes($localizedAttributesTransfer);
        }

        if ($hasModified) {
            $this->touchDictionary();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function assertLocalizedAttributes(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesTransfer
            ->requireFkLocale()
            ->requireFkProductLabel();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return bool
     */
    protected function persistLocalizedAttributes(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesEntity = $this->findOrCreateEntity($localizedAttributesTransfer);
        $this->updateEntityFromTransfer($localizedAttributesEntity, $localizedAttributesTransfer);

        if (!$localizedAttributesEntity->isModified()) {
            return false;
        }

        $localizedAttributesEntity->save();
        $this->updateTransferFromEntity($localizedAttributesTransfer, $localizedAttributesEntity);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes
     */
    protected function findOrCreateEntity(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesEntity = $this
            ->queryContainer
            ->queryLocalizedAttributesByIdProductLabelAndIdLocale(
                $localizedAttributesTransfer->getFkProductLabel(),
                $localizedAttributesTransfer->getFkLocale()
            )
            ->findOneOrCreate();

        return $localizedAttributesEntity;
    }

    /**
     * @return void
     */
    protected function touchDictionary()
    {
        $this->dictionaryTouchManager->touchActive();
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $localizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function updateEntityFromTransfer(
        SpyProductLabelLocalizedAttributes $localizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
    ) {
        $localizedAttributesEntity->fromArray($localizedAttributesTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $localizedAttributesEntity
     *
     * @return void
     */
    protected function updateTransferFromEntity(
        ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer,
        SpyProductLabelLocalizedAttributes $localizedAttributesEntity
    ) {
        $localizedAttributesTransfer->fromArray($localizedAttributesEntity->toArray(), true);
    }

}
