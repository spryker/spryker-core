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
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface;
use Spryker\Zed\ProductSetPageSearch\Business\Expander\ProductSetPageSearchExpanderInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface;

class ProductSetPageSearchWriter implements ProductSetPageSearchWriterInterface
{
    /**
     * @var string
     */
    public const COL_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @param \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface $productSetPageSearchDataMapper
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface $productSetFacade
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\ProductSetPageSearch\Business\Expander\ProductSetPageSearchExpanderInterface|null $productSetPageSearchExpander
     */
    public function __construct(
        protected ProductSetPageSearchQueryContainerInterface $queryContainer,
        protected ProductSetPageSearchToUtilEncodingInterface $utilEncodingService,
        protected ProductSetSearchDataMapperInterface $productSetPageSearchDataMapper,
        protected ProductSetPageSearchToProductSetInterface $productSetFacade,
        protected bool $isSendingToQueue = true,
        protected ?ProductSetPageSearchExpanderInterface $productSetPageSearchExpander = null
    ) {
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

        $this->storeData($spyProductSetEntities, $spyProductSetPageSearchEntities);
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
     *
     * @return void
     */
    protected function storeData(array $spyProductSetLocalizedEntities, array $spyProductSetStorageEntities)
    {
        foreach ($spyProductSetLocalizedEntities as $spyProductSetLocalizedEntity) {
            $idProductSet = $spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET];
            $localeName = $spyProductSetLocalizedEntity['SpyLocale']['locale_name'];
            if (isset($spyProductSetStorageEntities[$idProductSet][$localeName])) {
                $this->storeDataSet($spyProductSetLocalizedEntity, $spyProductSetStorageEntities[$idProductSet][$localeName]);

                continue;
            }

            $this->storeDataSet($spyProductSetLocalizedEntity);
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     * @param \Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch|null $spyProductSetPageSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(
        array $spyProductSetLocalizedEntity,
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

        $productSetPageSearchTransfer = $this->getProductSetPageSearchTransfer($spyProductSetLocalizedEntity);

        if ($this->productSetPageSearchExpander) {
            $productSetPageSearchTransfer = $this->productSetPageSearchExpander
                ->expandProductSetPageSearchWithProductImageAlternativeTexts($productSetPageSearchTransfer);
        }

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
     *
     * @return \Generated\Shared\Transfer\ProductSetPageSearchTransfer
     */
    protected function getProductSetPageSearchTransfer(
        array $spyProductAbstractLocalizedEntity
    ) {
        $productAbstractIds = [];
        foreach ($spyProductAbstractLocalizedEntity['SpyProductSet']['SpyProductAbstractSets'] as $spyProductAbstractSet) {
            $productAbstractIds[] = $spyProductAbstractSet['fk_product_abstract'];
        }
        $productSetPageSearchTransfer = new ProductSetPageSearchTransfer();
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity, true);
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity['SpyProductSet'], true);
        $productSetPageSearchTransfer->setLocale($spyProductAbstractLocalizedEntity['SpyLocale']['locale_name']);
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
