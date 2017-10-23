<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductSetDataTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSetData;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface;

class ProductSetDataReader implements ProductSetDataReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface
     */
    protected $productSetUrlReader;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlReaderInterface $productSetUrlReader
     */
    public function __construct(ProductSetUrlReaderInterface $productSetUrlReader)
    {
        $this->productSetUrlReader = $productSetUrlReader;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function getLocalizedData(SpyProductSetData $productSetDataEntity)
    {
        $localeTransfer = $this->getLocaleTransfer($productSetDataEntity);

        $productSetDataTransfer = $this->mapProductSetDataEntityToTransfer($productSetDataEntity);
        $productSetUrlEntity = $this->productSetUrlReader->getProductSetUrlEntity($productSetDataEntity->getFkProductSet(), $localeTransfer->getIdLocale());

        $localizedProductSetDataTransfer = new LocalizedProductSetTransfer();
        $localizedProductSetDataTransfer
            ->setLocale($localeTransfer)
            ->setProductSetData($productSetDataTransfer)
            ->setUrl($productSetUrlEntity->getUrl());

        return $localizedProductSetDataTransfer;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(SpyProductSetData $productSetDataEntity)
    {
        $localeEntity = $productSetDataEntity->getSpyLocale();
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     *
     * @return \Generated\Shared\Transfer\ProductSetDataTransfer
     */
    protected function mapProductSetDataEntityToTransfer(SpyProductSetData $productSetDataEntity)
    {
        $productSetDataTransfer = new ProductSetDataTransfer();
        $productSetDataTransfer->fromArray($productSetDataEntity->toArray(), true);

        return $productSetDataTransfer;
    }
}
