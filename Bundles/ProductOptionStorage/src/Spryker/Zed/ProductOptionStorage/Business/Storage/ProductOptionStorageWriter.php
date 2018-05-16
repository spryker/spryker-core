<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface;
use Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface;

class ProductOptionStorageWriter implements ProductOptionStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade, ProductOptionStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->productOptionFacade = $productOptionFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $productOptionEntities = $this->findProductOptionAbstractEntities($productAbstractIds);
        $productOptions = [];
        foreach ($productOptionEntities as $productOptionEntity) {
            $productOptions[$productOptionEntity['fk_product_abstract']][] = $productOptionEntity;
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractOptionStorageEntities = $this->findProductStorageOptionEntitiesByProductAbstractIds($productAbstractIds);

        if (!$spyProductAbstractLocalizedAttributeEntities) {
            $this->deleteStorageData($spyProductAbstractOptionStorageEntities);
        }

        $this->storeData($spyProductAbstractLocalizedAttributeEntities, $spyProductAbstractOptionStorageEntities, $productOptions);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductAbstractOptionStorageEntities = $this->findProductStorageOptionEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($spyProductAbstractOptionStorageEntities as $spyProductAbstractOptionStorageLocalizedEntities) {
            foreach ($spyProductAbstractOptionStorageLocalizedEntities as $spyProductAbstractOptionStorageLocalizedEntity) {
                $spyProductAbstractOptionStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractOptionStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $spyProductAbstractOptionStorageEntities)
    {
        foreach ($spyProductAbstractOptionStorageEntities as $spyProductAbstractOptionStorageLocalizedEntities) {
            foreach ($spyProductAbstractOptionStorageLocalizedEntities as $spyProductAbstractOptionStorageLocalizedEntity) {
                $spyProductAbstractOptionStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractOptionStorageEntities
     * @param array $productOptions
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractOptionStorageEntities, array $productOptions)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractOptionStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productOptions, $spyProductAbstractOptionStorageEntities[$idProduct][$localeName]);

                continue;
            }

            $this->storeDataSet($spyProductAbstractLocalizedEntity, $productOptions);
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $productOptions
     * @param \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorage|null $spyProductAbstractOptionStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $productOptions, ?SpyProductAbstractOptionStorage $spyProductAbstractOptionStorageEntity = null)
    {
        if ($spyProductAbstractOptionStorageEntity === null) {
            $spyProductAbstractOptionStorageEntity = new SpyProductAbstractOptionStorage();
        }

        if (empty($productOptions[$spyProductAbstractLocalizedEntity->getFkProductAbstract()])) {
            if (!$spyProductAbstractOptionStorageEntity->isNew()) {
                $spyProductAbstractOptionStorageEntity->delete();
            }

            return;
        }

        $productOptionGroupStorageTransfers = $this->getProductOptionGroupStorageTransfers($spyProductAbstractLocalizedEntity, $productOptions);
        $productAbstractOptionStorageTransfer = new ProductAbstractOptionStorageTransfer();
        $productAbstractOptionStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $productAbstractOptionStorageTransfer->setProductOptionGroups($productOptionGroupStorageTransfers);

        $spyProductAbstractOptionStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractOptionStorageEntity->setData($productAbstractOptionStorageTransfer->toArray());
        $spyProductAbstractOptionStorageEntity->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractOptionStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractOptionStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup[]
     */
    protected function findProductOptionAbstractEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductOptionsByProductAbstractIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductStorageOptionEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractOptionStorageEntities = $this->queryContainer->queryProductAbstractOptionStorageByIds($productAbstractIds)->find();
        $productAbstractOptionStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractOptionStorageEntities as $productAbstractOptionStorageEntity) {
            $productAbstractOptionStorageEntitiesByIdAndLocale[$productAbstractOptionStorageEntity->getFkProductAbstract()][$productAbstractOptionStorageEntity->getLocale()] = $productAbstractOptionStorageEntity;
        }

        return $productAbstractOptionStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $productOptions
     *
     * @return array|\ArrayObject
     */
    protected function getProductOptionGroupStorageTransfers(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $productOptions)
    {
        $productOptionGroupStorageTransfers = new ArrayObject();
        foreach ($productOptions[$spyProductAbstractLocalizedEntity->getFkProductAbstract()] as $productOption) {
            $productOptionGroupStorageTransfer = new ProductOptionGroupStorageTransfer();
            $productOptionGroupStorageTransfer->setName($productOption['SpyProductOptionGroup']['name']);
            foreach ($productOption['SpyProductOptionGroup']['SpyProductOptionValues'] as $productOptionValue) {
                $productOptionGroupStorageTransfer->addProductOptionValue((new ProductOptionValueStorageTransfer())->setIdProductOptionValue($productOptionValue['id_product_option_value'])
                    ->setSku($productOptionValue['sku'])
                    ->setPrices($this->getPrices($productOptionValue['ProductOptionValuePrices']))
                    ->setValue($productOptionValue['value']));
            }
            $productOptionGroupStorageTransfers[] = $productOptionGroupStorageTransfer;
        }

        return $productOptionGroupStorageTransfers;
    }

    /**
     * @param array $prices
     *
     * @return array
     */
    protected function getPrices(array $prices)
    {
        $moneyValueCollection = $this->transformPriceEntityCollectionToMoneyValueTransferCollection($prices);

        $priceResponse = $this->productOptionFacade->getProductOptionValueStorePrices(
            (new ProductOptionValueStorePricesRequestTransfer())->setPrices($moneyValueCollection)
        );

        return $priceResponse->getStorePrices();
    }

    /**
     * @param array $prices
     *
     * @return \ArrayObject
     */
    protected function transformPriceEntityCollectionToMoneyValueTransferCollection(array $prices)
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($prices as $price) {
            $moneyValueCollection->append(
                (new MoneyValueTransfer())
                    ->fromArray($price, true)
                    ->setNetAmount($price['net_price'])
                    ->setGrossAmount($price['gross_price'])
            );
        }

        return $moneyValueCollection;
    }
}
