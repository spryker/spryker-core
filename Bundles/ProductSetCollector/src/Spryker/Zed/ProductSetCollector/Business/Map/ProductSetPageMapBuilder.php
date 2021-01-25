<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Map;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSetCollector\ProductSetCollectorConfig;
use Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface;
use Spryker\Zed\ProductSetCollector\Persistence\Search\Propel\ProductSetCollectorQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Pyz\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class ProductSetPageMapBuilder implements PageMapInterface
{
    /**
     * @var \Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface
     */
    protected $storageProductImageReader;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface $storageProductImageReader
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(StorageProductImageReaderInterface $storageProductImageReader, Store $store)
    {
        $this->storageProductImageReader = $storageProductImageReader;
        $this->store = $store;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $productSetData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $productSetData, LocaleTransfer $localeTransfer)
    {
        $productSetStorageTransfer = $this->mapProductSetStorageTransfer($productSetData, $localeTransfer);

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($this->store->getStoreName())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(ProductSetCollectorConfig::SEARCH_TYPE_PRODUCT_SET);

        $pageMapBuilder->addIntegerSort($pageMapTransfer, ProductSetStorageTransfer::WEIGHT, $productSetStorageTransfer->getWeight());

        $this->setSearchResultData($pageMapTransfer, $pageMapBuilder, $productSetStorageTransfer);

        return $pageMapTransfer;
    }

    /**
     * @param array $productSetData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function mapProductSetStorageTransfer(array $productSetData, LocaleTransfer $localeTransfer)
    {
        $productSetStorageTransfer = new ProductSetStorageTransfer();
        $productSetStorageTransfer = $this->setIdProductAbstracts($productSetData, $productSetStorageTransfer);

        unset($productSetData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);

        $productSetStorageTransfer->fromArray($productSetData, true);
        $productSetStorageTransfer = $this->setProductSetImageSets($productSetStorageTransfer, $localeTransfer);

        return $productSetStorageTransfer;
    }

    /**
     * @param array $collectItemData
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function setIdProductAbstracts(array $collectItemData, ProductSetStorageTransfer $productSetStorageTransfer)
    {
        $idProductAbstracts = explode(',', $collectItemData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);
        $idProductAbstracts = array_map('intval', $idProductAbstracts);

        $productSetStorageTransfer->setIdProductAbstracts($idProductAbstracts);

        return $productSetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function setProductSetImageSets(ProductSetStorageTransfer $productSetStorageTransfer, LocaleTransfer $localeTransfer)
    {
        $imageSets = $this->storageProductImageReader->getProductSetImageSets(
            $productSetStorageTransfer->getIdProductSet(),
            $localeTransfer->getIdLocale()
        );

        $productSetStorageTransfer->setImageSets($imageSets);

        return $productSetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return void
     */
    protected function setSearchResultData(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        ProductSetStorageTransfer $productSetStorageTransfer
    ) {
        foreach ($productSetStorageTransfer->modifiedToArray() as $key => $value) {
            if ($value !== null) {
                $pageMapBuilder->addSearchResultData($pageMapTransfer, $key, $value);
            }
        }
    }
}
