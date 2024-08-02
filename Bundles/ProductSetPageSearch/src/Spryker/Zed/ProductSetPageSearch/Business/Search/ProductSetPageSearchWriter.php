<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductSetPageSearchTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface;

class ProductSetPageSearchWriter implements ProductSetPageSearchWriterInterface
{
    /**
     * @var string
     */
    public const COL_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface
     */
    protected $productSetPageSearchDataMapper;

    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface
     */
    protected $productSetFacade;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface $productSetPageSearchDataMapper
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface $productSetFacade
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductSetPageSearchQueryContainerInterface $queryContainer,
        ProductSetPageSearchToUtilEncodingInterface $utilEncodingService,
        ProductSetSearchDataMapperInterface $productSetPageSearchDataMapper,
        ProductSetPageSearchToProductSetInterface $productSetFacade,
        ProductSetPageSearchToStoreFacadeInterface $storeFacade,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->productSetPageSearchDataMapper = $productSetPageSearchDataMapper;
        $this->productSetFacade = $productSetFacade;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds)
    {
        $spyProductSetEntities = $this->findProductSetLocalizedEntities($productSetIds);
        $spyProductSetPageSearchEntities = $this->findProductSetPageSearchEntitiesByProductSetIds($productSetIds);

        $storeTransfer = $this->storeFacade->getCurrentStore(true);
        $this->storeData($spyProductSetEntities, $spyProductSetPageSearchEntities, $storeTransfer);
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds)
    {
        $spyProductSetPageSearchEntities = $this->findProductSetPageSearchEntitiesByProductSetIds($productSetIds);
        foreach ($spyProductSetPageSearchEntities as $spyProductSetPageSearchEntityLocales) {
            foreach ($spyProductSetPageSearchEntityLocales as $spyProductSetPageSearchEntityLocale) {
                $spyProductSetPageSearchEntityLocale->delete();
            }
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntities
     * @param array $spyProductSetStorageEntities
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function storeData(array $spyProductSetLocalizedEntities, array $spyProductSetStorageEntities, StoreTransfer $storeTransfer)
    {
        foreach ($spyProductSetLocalizedEntities as $spyProductSetLocalizedEntity) {
            $idProductSet = $spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET];
            $localeName = $spyProductSetLocalizedEntity['SpyLocale']['locale_name'];
            if (isset($spyProductSetStorageEntities[$idProductSet][$localeName])) {
                $this->storeDataSet($spyProductSetLocalizedEntity, $storeTransfer, $spyProductSetStorageEntities[$idProductSet][$localeName]);

                continue;
            }

            $this->storeDataSet($spyProductSetLocalizedEntity, $storeTransfer);
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch|null $spyProductSetPageSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(
        array $spyProductSetLocalizedEntity,
        StoreTransfer $storeTransfer,
        ?SpyProductSetPageSearch $spyProductSetPageSearchEntity = null
    ) {
        if ($spyProductSetPageSearchEntity === null) {
            $spyProductSetPageSearchEntity = new SpyProductSetPageSearch();
        }

        if (!filter_var($spyProductSetLocalizedEntity['SpyProductSet']['is_active'], FILTER_VALIDATE_BOOLEAN)) {
            if (!$spyProductSetPageSearchEntity->isNew()) {
                $spyProductSetPageSearchEntity->delete();
            }

            return;
        }

        $productSetPageSearchTransfer = $this->getProductSetPageSearchTransfer($spyProductSetLocalizedEntity, $storeTransfer);
        $localeTransfer = (new LocaleTransfer())
            ->setLocaleName($spyProductSetLocalizedEntity['SpyLocale']['locale_name'])
            ->setIdLocale($spyProductSetLocalizedEntity['SpyLocale']['id_locale']);

        $data = $this->productSetPageSearchDataMapper->mapProductSetDataToSearchData($productSetPageSearchTransfer->toArray(), $localeTransfer);

        $spyProductSetPageSearchEntity->setFkProductSet($spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET]);
        $spyProductSetPageSearchEntity->setStructuredData($this->utilEncodingService->encodeJson($productSetPageSearchTransfer->toArray()));
        $spyProductSetPageSearchEntity->setData($data);
        $spyProductSetPageSearchEntity->setLocale($spyProductSetLocalizedEntity['SpyLocale']['locale_name']);
        $spyProductSetPageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductSetPageSearchEntity->save();
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetPageSearchTransfer
     */
    protected function getProductSetPageSearchTransfer(
        array $spyProductAbstractLocalizedEntity,
        StoreTransfer $storeTransfer
    ) {
        $productAbstractIds = [];
        foreach ($spyProductAbstractLocalizedEntity['SpyProductSet']['SpyProductAbstractSets'] as $spyProductAbstractSet) {
            $productAbstractIds[] = $spyProductAbstractSet['fk_product_abstract'];
        }
        $productSetPageSearchTransfer = new ProductSetPageSearchTransfer();
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity, true);
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity['SpyProductSet'], true);
        $productSetPageSearchTransfer->setLocale($spyProductAbstractLocalizedEntity['SpyLocale']['locale_name']);
        $productSetPageSearchTransfer->setStore($storeTransfer->getNameOrFail());
        $productSetPageSearchTransfer->setIdProductAbstracts($productAbstractIds);
        $productSetPageSearchTransfer->setType('product_set');
        $productSetPageSearchTransfer->setImageSets($this->getProductSetImageSets($spyProductAbstractLocalizedEntity['fk_product_set'], $spyProductAbstractLocalizedEntity['SpyLocale']['id_locale']));

        return $productSetPageSearchTransfer;
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetLocalizedEntities(array $productSetIds)
    {
        return $this->queryContainer->queryProductSetDataByIds($productSetIds)->find()->getData();
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetPageSearchEntitiesByProductSetIds(array $productSetIds)
    {
        $productSetStorageEntities = $this->queryContainer->queryProductSetPageSearchPageByIds($productSetIds)->find();
        $productSetStorageEntitiesByIdAndLocale = [];
        foreach ($productSetStorageEntities as $productSetStorageEntity) {
            $productSetStorageEntitiesByIdAndLocale[$productSetStorageEntity->getFkProductSet()][$productSetStorageEntity->getLocale()] = $productSetStorageEntity;
        }

        return $productSetStorageEntitiesByIdAndLocale;
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductSetImageSets($idProductSet, $idLocale)
    {
        $productImageSetTransfers = $this->productSetFacade->getCombinedProductSetImageSets($idProductSet, $idLocale);

        $imageSets = [];
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $imageSets[$productImageSetTransfer->getName()] = $this->getProductImageData($productImageSetTransfer);
        }

        return $imageSets;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return array
     */
    protected function getProductImageData(ProductImageSetTransfer $productImageSetTransfer)
    {
        $result = [];

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $storageProductImageTransfer = new StorageProductImageTransfer();
            $storageProductImageTransfer->fromArray($productImageTransfer->modifiedToArray(), true);

            $result[] = $storageProductImageTransfer->modifiedToArray();
        }

        return $result;
    }
}
