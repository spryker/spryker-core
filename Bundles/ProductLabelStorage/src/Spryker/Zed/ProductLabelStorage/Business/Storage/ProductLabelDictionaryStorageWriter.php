<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Storage;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabel;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface;

class ProductLabelDictionaryStorageWriter implements ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductLabelStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @return void
     */
    public function publish()
    {
        $spyProductLabelLocalizedAttributeEntities = $this->findProductLabelLocalizedEntities();
        $productLabelDictionaryItems = [];
        foreach ($spyProductLabelLocalizedAttributeEntities as $spyProductLabelLocalizedAttributeEntity) {
            $localeName = $spyProductLabelLocalizedAttributeEntity->getSpyLocale()->getLocaleName();
            if ($this->isValidByDate($spyProductLabelLocalizedAttributeEntity)) {
                $productLabelDictionaryItems[$localeName][] = $this->mapProductLabelDictionaryItem($spyProductLabelLocalizedAttributeEntity);
            }
        }

        if (!$productLabelDictionaryItems) {
            $this->deleteStorageData();

            return;
        }

        $this->storeData($productLabelDictionaryItems);
    }

    /**
     * @return void
     */
    public function unpublish()
    {
        $this->deleteStorageData();
    }

    /**
     * @return void
     */
    protected function deleteStorageData()
    {
        $spyProductStorageEntities = $this->findProductLabelDictionaryStorageEntities();
        foreach ($spyProductStorageEntities as $spyProductStorageEntity) {
            $spyProductStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItems
     *
     * @return void
     */
    protected function storeData(array $productLabelDictionaryItems)
    {
        $spyProductLabelStorageEntities = $this->findProductLabelDictionaryStorageEntities();

        foreach ($productLabelDictionaryItems as $localeName => $productLabelDictionaryItemTransfers) {
            $productLabelDictionaryStorageTransfer = new ProductLabelDictionaryStorageTransfer();
            $productLabelDictionaryStorageTransfer->setItems(new ArrayObject($productLabelDictionaryItemTransfers));

            if (isset($spyProductLabelStorageEntities[$localeName])) {
                $this->storeDataSet($productLabelDictionaryStorageTransfer, $localeName, $spyProductLabelStorageEntities[$localeName]);

                continue;
            }

            $this->storeDataSet($productLabelDictionaryStorageTransfer, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     * @param string $localeName
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage|null $spyProductLabelStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer,
        $localeName,
        ?SpyProductLabelDictionaryStorage $spyProductLabelStorageEntity = null
    ) {
        if ($spyProductLabelStorageEntity === null) {
            $spyProductLabelStorageEntity = new SpyProductLabelDictionaryStorage();
        }

        $spyProductLabelStorageEntity->setData($productLabelDictionaryStorageTransfer->modifiedToArray());
        $spyProductLabelStorageEntity->setLocale($localeName);
        $spyProductLabelStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductLabelStorageEntity->save();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes[]
     */
    protected function findProductLabelLocalizedEntities()
    {
        return $this->queryContainer->queryProductLabelLocalizedAttributes()->find();
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $spyProductLabelLocalizedAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer
     */
    protected function mapProductLabelDictionaryItem(SpyProductLabelLocalizedAttributes $spyProductLabelLocalizedAttributeEntity)
    {
        $productLabelDictionaryStorageTransfer = new ProductLabelDictionaryItemTransfer();
        $productLabelDictionaryStorageTransfer
            ->setName($spyProductLabelLocalizedAttributeEntity->getName())
            ->setIdProductLabel($spyProductLabelLocalizedAttributeEntity->getFkProductLabel())
            ->setKey($spyProductLabelLocalizedAttributeEntity->getSpyProductLabel()->getName())
            ->setIsExclusive($spyProductLabelLocalizedAttributeEntity->getSpyProductLabel()->getIsExclusive())
            ->setPosition($spyProductLabelLocalizedAttributeEntity->getSpyProductLabel()->getPosition())
            ->setFrontEndReference($spyProductLabelLocalizedAttributeEntity->getSpyProductLabel()->getFrontEndReference());

        return $productLabelDictionaryStorageTransfer;
    }

    /**
     * @return array
     */
    protected function findProductLabelDictionaryStorageEntities()
    {
        $productAbstractStorageEntities = $this->queryContainer->queryProductLabelDictionaryStorage()->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $spyProductLabelLocalizedAttributes
     *
     * @return bool
     */
    protected function isValidByDate(SpyProductLabelLocalizedAttributes $spyProductLabelLocalizedAttributes)
    {
        $isValidFromDate = $this->isValidByDateFrom($spyProductLabelLocalizedAttributes->getSpyProductLabel());
        $isValidToDate = $this->isValidByDateTo($spyProductLabelLocalizedAttributes->getSpyProductLabel());

        return ($isValidFromDate && $isValidToDate);
    }

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabel $productLabel
     *
     * @return bool
     */
    protected function isValidByDateFrom(SpyProductLabel $productLabel)
    {
        if (!$productLabel->getValidFrom()) {
            return true;
        }

        $now = new DateTime();

        if ($now < $productLabel->getValidFrom()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabel $productLabel
     *
     * @return bool
     */
    protected function isValidByDateTo(SpyProductLabel $productLabel)
    {
        if (!$productLabel->getValidTo()) {
            return true;
        }

        $now = new DateTime();

        if ($productLabel->getValidTo() < $now) {
            return false;
        }

        return true;
    }
}
