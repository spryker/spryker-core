<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductSet\ProductSetConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface;
use Spryker\Zed\ProductSetCollector\Persistence\Storage\Propel\ProductSetCollectorQuery;

class ProductSetCollector extends AbstractStoragePropelCollector
{
    /**
     * @var \Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface
     */
    protected $storageProductImageReader;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\ProductSetCollector\Business\Image\StorageProductImageReaderInterface $storageProductImageReader
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService, StorageProductImageReaderInterface $storageProductImageReader)
    {
        parent::__construct($utilDataReaderService);

        $this->storageProductImageReader = $storageProductImageReader;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productSetStorageTransfer = $this->mapProductSetStorageTransfer($collectItemData);

        return $productSetStorageTransfer->modifiedToArray();
    }

    /**
     * @param array $collectItemData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function mapProductSetStorageTransfer(array $collectItemData)
    {
        $productSetStorageTransfer = new ProductSetStorageTransfer();
        $productSetStorageTransfer = $this->setIdProductAbstract($collectItemData, $productSetStorageTransfer);

        unset($collectItemData[ProductSetCollectorQuery::FIELD_ID_PRODUCT_ABSTRACTS]);

        $productSetStorageTransfer->fromArray($collectItemData, true);
        $productSetStorageTransfer = $this->setProductSetImageSets($productSetStorageTransfer);

        return $productSetStorageTransfer;
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
        $imageSets = $this->storageProductImageReader->getProductSetImageSets(
            $productSetStorageTransfer->getIdProductSet(),
            $this->locale->getIdLocale()
        );

        $productSetStorageTransfer->setImageSets($imageSets);

        return $productSetStorageTransfer;
    }
}
