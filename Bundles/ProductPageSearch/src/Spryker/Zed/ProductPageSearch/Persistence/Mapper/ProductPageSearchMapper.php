<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface;

class ProductPageSearchMapper implements ProductPageSearchMapperInterface
{
    public const IDENTIFIER_PRODUCT_CONCRETE_PAGE_SEARCH = 'id_product_concrete_page_search';

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface $searchFacade
     */
    public function __construct(ProductPageSearchToSearchInterface $searchFacade)
    {
        $this->searchFacade = $searchFacade;
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function mapProductConcretePageSearchEntityToTransfer(
        SpyProductConcretePageSearch $productConcretePageSearchEntity,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer {
        return $productConcretePageSearchTransfer->fromArray(
            $productConcretePageSearchEntity->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch
     */
    public function mapProductConcretePageSearchTransferToEntity(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        SpyProductConcretePageSearch $productConcretePageSearchEntity
    ): SpyProductConcretePageSearch {
        $productConcretePageSearchEntity->fromArray(
            $productConcretePageSearchTransfer->toArray()
        );

        return $productConcretePageSearchEntity;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productConcreteEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productConcreteTransfer->fromArray(
            $productConcreteEntity->toArray(),
            true
        );

        $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
        $productConcreteTransfer->setAbstractSku(
            $productConcreteEntity->getSpyProductAbstract()->getSku()
        );

        foreach ($productConcreteEntity->getSpyProductLocalizedAttributess() as $productLocalizedAttributesEntity) {
            $productConcreteTransfer->addLocalizedAttributes(
                $this->mapProductLocalizedAttributesEntityToTransfer($productLocalizedAttributesEntity, new LocalizedAttributesTransfer())
            );
        }

        foreach ($productConcreteEntity->getSpyProductAbstract()->getSpyProductAbstractStores() as $productAbstractStoreEntity) {
            $productConcreteTransfer->addStores(
                $this->mapStoreEntityToTransfer($productAbstractStoreEntity->getSpyStore(), new StoreTransfer())
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function mapProductConcreteTransferToProductConcretePageSearchTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        StoreTransfer $storeTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): ProductConcretePageSearchTransfer {
        $productConcretePageSearchTransfer->fromArray(
            $productConcreteTransfer->toArray(),
            true
        );

        $productConcretePageSearchTransfer->setFkProduct($productConcreteTransfer->getIdProductConcrete());
        $productConcretePageSearchTransfer->setType(ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME);
        $productConcretePageSearchTransfer->setStore($storeTransfer->getName());
        $productConcretePageSearchTransfer->setLocale($localizedAttributesTransfer->getLocale()->getLocaleName());
        $productConcretePageSearchTransfer->setName($localizedAttributesTransfer->getName());

        return $productConcretePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return array
     */
    public function mapToSearchData(ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): array
    {
        return $this->searchFacade->transformPageMapToDocumentByMapperName(
            $productConcretePageSearchTransfer->toArray(true, true),
            (new LocaleTransfer())->setLocaleName($productConcretePageSearchTransfer->getLocale()),
            ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function mapProductLocalizedAttributesEntityToTransfer(
        SpyProductLocalizedAttributes $productLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer {
        $localizedAttributesTransfer->fromArray(
            $productLocalizedAttributesEntity->toArray(),
            true
        );

        $localizedAttributesTransfer->setLocale(
            $this->mapLocaleEntityToTransfer($productLocalizedAttributesEntity->getLocale(), new LocaleTransfer())
        );

        return $localizedAttributesTransfer;
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        return $localeTransfer->fromArray(
            $localeEntity->toArray(),
            true
        );
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray(
            $storeEntity->toArray(),
            true
        );
    }
}
