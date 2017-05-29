<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Map;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSetCollector\ProductSetCollectorConfig;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainer;
use Spryker\Zed\ProductSetCollector\Persistence\Search\Propel\ProductSetCollectorQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Pyz\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class ProductSetPageMapBuilder implements PageMapInterface
{

    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $productSetData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $productSetData, LocaleTransfer $localeTransfer)
    {
        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName()) // TODO: inject Store?
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(ProductSetCollectorConfig::SEARCH_TYPE_PRODUCT_SET);

        $productSetStorageTransfer = new ProductSetStorageTransfer();
        $productSetStorageTransfer = $this->setIdProductAbstract($productSetData, $productSetStorageTransfer);

        unset($productSetData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);

        // TODO: clean up
        $productSetStorageTransfer->fromArray($productSetData, true);
        $productSetStorageTransfer = $this->setProductSetImageSets($productSetStorageTransfer);

        foreach ($productSetStorageTransfer->modifiedToArray() as $key => $value) {
            if ($value === null) {
                continue;
            }
            $pageMapBuilder->addSearchResultData($pageMapTransfer, $key, $value);
        }

        $pageMapBuilder->addIntegerSort($pageMapTransfer, ProductSetStorageTransfer::WEIGHT, $productSetStorageTransfer->getWeight());

        return $pageMapTransfer;
    }

    /**
     * @param array $collectItemData
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function setIdProductAbstract(array $collectItemData, ProductSetStorageTransfer $productSetStorageTransfer)
    {
        $idProductAbstracts = explode(',', $collectItemData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);
        $idProductAbstracts = array_map('intval', $idProductAbstracts);

        $productSetStorageTransfer->setIdProductAbstracts($idProductAbstracts);

        return $productSetStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function setProductSetImageSets(ProductSetStorageTransfer $productSetStorageTransfer)
    {
        $this->productSetQueryContainer = new ProductSetQueryContainer(); // FIXME: probably should use ProductSetCollectorQueryContainer

        $imageSetEntities = $this->productSetQueryContainer
            ->queryProductImageSet($productSetStorageTransfer->getIdProductSet())
            ->find();

        // TODO: use new ProductImageFacade methods to get only relevant image sets

        $imageSets = [];
        foreach ($imageSetEntities as $imageSetEntity) {
            $result[$imageSetEntity->getName()] = [];
            foreach ($imageSetEntity->getSpyProductImageSetToProductImages() as $productsToImageEntity) {
                $imageEntity = $productsToImageEntity->getSpyProductImage();
                $storageProductImageTransfer = new StorageProductImageTransfer();
                $storageProductImageTransfer->fromArray($imageEntity->toArray(), true);

                $imageSets[$imageSetEntity->getName()][] = $storageProductImageTransfer->modifiedToArray();
            }
        }

        $productSetStorageTransfer->setImageSets($imageSets);

        return $productSetStorageTransfer;
    }

}
