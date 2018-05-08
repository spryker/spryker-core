<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductSetPageSearchTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch;
use Spryker\Shared\ProductSetPageSearch\ProductSetPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetPageSearch\Communication\ProductSetPageSearchCommunicationFactory getFactory()
 */
class AbstractProductSetPageSearchListener extends AbstractPlugin
{
    const COL_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $spyProductSetEntities = $this->findProductSetLocalizedEntities($productAbstractIds);
        $spyProductSetPageSearchEntities = $this->findProductSetPageSearchEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($spyProductSetEntities, $spyProductSetPageSearchEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIds)
    {
        $spyProductSetPageSearchEntities = $this->findProductSetPageSearchEntitiesByProductAbstractIds($productAbstractIds);
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
            } else {
                $this->storeDataSet($spyProductSetLocalizedEntity);
            }
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     * @param \Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearch|null $spyProductSetPageSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductSetLocalizedEntity, ?SpyProductSetPageSearch $spyProductSetPageSearchEntity = null)
    {
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
        $localeTransfer = (new LocaleTransfer())
            ->setLocaleName($spyProductSetLocalizedEntity['SpyLocale']['locale_name'])
            ->setIdLocale($spyProductSetLocalizedEntity['SpyLocale']['id_locale']);

        $data = $this
            ->getFactory()->getSearchFacade()
            ->transformPageMapToDocumentByMapperName($productSetPageSearchTransfer->toArray(), $localeTransfer, ProductSetPageSearchConstants::PRODUCT_SET_RESOURCE_NAME);

        $spyProductSetPageSearchEntity->setFkProductSet($spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET]);
        $spyProductSetPageSearchEntity->setStructuredData($this->getFactory()->getUtilEncoding()->encodeJson($productSetPageSearchTransfer->toArray()));
        $spyProductSetPageSearchEntity->setData($data);
        $spyProductSetPageSearchEntity->setStore($this->getStoreName());
        $spyProductSetPageSearchEntity->setLocale($spyProductSetLocalizedEntity['SpyLocale']['locale_name']);
        $spyProductSetPageSearchEntity->save();
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetPageSearchTransfer
     */
    protected function getProductSetPageSearchTransfer(array $spyProductAbstractLocalizedEntity)
    {
        $productAbstractIds = [];
        foreach ($spyProductAbstractLocalizedEntity['SpyProductSet']['SpyProductAbstractSets'] as $spyProductAbstractSet) {
            $productAbstractIds[] = $spyProductAbstractSet['fk_product_abstract'];
        }
        $productSetPageSearchTransfer = new ProductSetPageSearchTransfer();
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity, true);
        $productSetPageSearchTransfer->fromArray($spyProductAbstractLocalizedEntity['SpyProductSet'], true);
        $productSetPageSearchTransfer->setLocale($spyProductAbstractLocalizedEntity['SpyLocale']['locale_name']);
        $productSetPageSearchTransfer->setStore($this->getStoreName());
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
        return $this->getQueryContainer()->queryProductSetDataByIds($productSetIds)->find()->getData();
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetPageSearchEntitiesByProductAbstractIds(array $productSetIds)
    {
        $productSetStorageEntities = $this->getQueryContainer()->queryProductSetPageSearchPageByIds($productSetIds)->find();
        $productSetStorageEntitiesByIdAndLocale = [];
        foreach ($productSetStorageEntities as $productSetStorageEntity) {
            $productSetStorageEntitiesByIdAndLocale[$productSetStorageEntity->getFkProductSet()][$productSetStorageEntity->getLocale()] = $productSetStorageEntity;
        }

        return $productSetStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\StorageProductImageTransfer[]
     */
    public function getProductSetImageSets($idProductSet, $idLocale)
    {
        $productImageSetTransfers = $this->getFactory()->getProductSetFacade()->getCombinedProductSetImageSets($idProductSet, $idLocale);

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
