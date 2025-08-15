<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage;
use Spryker\Zed\ProductSetStorage\Business\Expander\ProductSetDataStorageExpanderInterface;
use Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface;

class ProductSetStorageWriter implements ProductSetStorageWriterInterface
{
    /**
     * @var string
     */
    public const COL_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @var string
     */
    public const COL_IS_ACTIVE = 'is_active';

    /**
     * @var array
     */
    protected $superAttributes = [];

    /**
     * @param \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\ProductSetStorage\Business\Expander\ProductSetDataStorageExpanderInterface|null $productSetDataStorageExpander
     */
    public function __construct(
        protected ProductSetStorageQueryContainerInterface $queryContainer,
        protected bool $isSendingToQueue = true,
        protected ?ProductSetDataStorageExpanderInterface $productSetDataStorageExpander = null
    ) {
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds)
    {
        $spyProductSetLocalizedEntities = $this->findProductSetLocalizedEntities($productSetIds);
        $spyProductSetStorageEntities = $this->findProductSetStorageEntitiesByProductSetIds($productSetIds);

        $this->storeData($spyProductSetLocalizedEntities, $spyProductSetStorageEntities);
    }

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds)
    {
        $spyProductSetStorageEntities = $this->findProductSetStorageEntitiesByProductSetIds($productSetIds);
        foreach ($spyProductSetStorageEntities as $spyProductSetStorageEntityLocales) {
            foreach ($spyProductSetStorageEntityLocales as $spyProductSetStorageEntityLocale) {
                $spyProductSetStorageEntityLocale->delete();
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
                $this->storeDataSet(
                    $spyProductSetLocalizedEntity,
                    $spyProductSetStorageEntities[$idProductSet][$localeName],
                );

                continue;
            }

            $this->storeDataSet($spyProductSetLocalizedEntity);
        }
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     * @param \Orm\Zed\ProductSetStorage\Persistence\SpyProductSetStorage|null $spyProductSetStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        array $spyProductSetLocalizedEntity,
        ?SpyProductSetStorage $spyProductSetStorageEntity = null
    ) {
        if (!$spyProductSetLocalizedEntity['SpyProductSet'][static::COL_IS_ACTIVE]) {
            if (!$spyProductSetStorageEntity) {
                return;
            }

            $spyProductSetStorageEntity->delete();

            return;
        }

        $productSetStorageTransfer = new ProductSetDataStorageTransfer();
        if ($spyProductSetStorageEntity === null) {
            $spyProductSetStorageEntity = new SpyProductSetStorage();
        }

        $productSetStorageTransfer->fromArray($spyProductSetLocalizedEntity, true);
        $productSetStorageTransfer->fromArray($spyProductSetLocalizedEntity['SpyProductSet'], true);

        $productAbstractIds = $this->extractProductAbstractIdsFromProductSetLocalizedEntity($spyProductSetLocalizedEntity);
        $productImageSet = $this->getProductImageSets($spyProductSetLocalizedEntity);

        $productSetStorageTransfer->setProductAbstractIds($productAbstractIds);
        $productSetStorageTransfer->setImageSets($productImageSet);

        if ($this->productSetDataStorageExpander) {
            $productSetStorageTransfer = $this->productSetDataStorageExpander->expandProductSetDataStorageWithProductImageAlternativeTexts(
                $productSetStorageTransfer,
                $spyProductSetLocalizedEntity,
            );
        }

        $spyProductSetStorageEntity->setFkProductSet($spyProductSetLocalizedEntity['SpyProductSet'][static::COL_ID_PRODUCT_SET]);
        $spyProductSetStorageEntity->setData($productSetStorageTransfer->toArray());
        $spyProductSetStorageEntity->setLocale($spyProductSetLocalizedEntity['SpyLocale']['locale_name']);
        $spyProductSetStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductSetStorageEntity->save();
    }

    /**
     * @param array $productSetLocalizedEntity
     *
     * @return array<int>
     */
    protected function extractProductAbstractIdsFromProductSetLocalizedEntity(array $productSetLocalizedEntity): array
    {
        $productAbstractSetEntities = $productSetLocalizedEntity['SpyProductSet']['SpyProductAbstractSets'];

        if (!$productAbstractSetEntities[0]) {
            return [];
        }

        $productAbstractIds = [];

        foreach ($productAbstractSetEntities as $productAbstract) {
            $productAbstractIds[] = $productAbstract['fk_product_abstract'];
        }

        return $productAbstractIds;
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetLocalizedEntities(array $productSetIds)
    {
        return $this->queryContainer->queryProductSetDataByProductSetIds($productSetIds)->find()->getData();
    }

    /**
     * @param array $productSetIds
     *
     * @return array
     */
    protected function findProductSetStorageEntitiesByProductSetIds(array $productSetIds)
    {
        $productSetStorageEntities = $this->queryContainer->queryProductSetStorageByIds($productSetIds)->find();
        $productSetStorageEntitiesByIdAndLocale = [];
        foreach ($productSetStorageEntities as $productSetStorageEntity) {
            $productSetStorageEntitiesByIdAndLocale[$productSetStorageEntity->getFkProductSet()][$productSetStorageEntity->getLocale()] = $productSetStorageEntity;
        }

        return $productSetStorageEntitiesByIdAndLocale;
    }

    /**
     * @param array $spyProductSetLocalizedEntity
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer>
     */
    protected function getProductImageSets(array $spyProductSetLocalizedEntity)
    {
        $filteredProductImageSets = $this->filterProductImagesSetsByLocale(
            $spyProductSetLocalizedEntity['SpyProductSet']['SpyProductImageSets'],
            $spyProductSetLocalizedEntity['fk_locale'],
        );
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetStorageTransfer> $productImageSets */
        $productImageSets = new ArrayObject();
        foreach ($filteredProductImageSets as $spyProductImageSets) {
            $productImageSetStorageTransfer = new ProductImageSetStorageTransfer();
            $productImageSetStorageTransfer->setName($spyProductImageSets['name']);
            foreach ($spyProductImageSets['SpyProductImageSetToProductImages'] as $productImageSetToProductImage) {
                $productImageStorageTransfer = new ProductImageStorageTransfer();
                $productImageStorageTransfer->setIdProductImage($productImageSetToProductImage['SpyProductImage']['id_product_image']);
                $productImageStorageTransfer->setExternalUrlSmall($productImageSetToProductImage['SpyProductImage']['external_url_small']);
                $productImageStorageTransfer->setExternalUrlLarge($productImageSetToProductImage['SpyProductImage']['external_url_large']);
                $productImageSetStorageTransfer->addImage($productImageStorageTransfer);
            }
            $productImageSets[] = $productImageSetStorageTransfer;
        }

        return $productImageSets;
    }

    /**
     * @param array $productImageSets
     * @param int $idLocale
     *
     * @return array
     */
    protected function filterProductImagesSetsByLocale(array $productImageSets, $idLocale)
    {
        $localizedProductImageSets = [];
        $defaultProductImageSets = [];
        foreach ($productImageSets as $productImageSet) {
            if (!array_key_exists('fk_locale', $productImageSet)) {
                continue;
            }

            if ($productImageSet['fk_locale'] === null) {
                $defaultProductImageSets[] = $productImageSet;

                continue;
            }

            $localizedProductImageSets[$productImageSet['fk_locale']][] = $productImageSet;
        }

        if (array_key_exists($idLocale, $localizedProductImageSets)) {
            return $localizedProductImageSets[$idLocale];
        }

        return $defaultProductImageSets;
    }
}
