<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Image;

use Generated\Shared\Transfer\StorageProductImageTransfer;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToProductSetInterface;

class StorageProductImageReader implements StorageProductImageReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToProductSetInterface
     */
    protected $productSetFacade;

    /**
     * @param \Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToProductSetInterface $productSetFacade
     */
    public function __construct(ProductSetCollectorToProductSetInterface $productSetFacade)
    {
        $this->productSetFacade = $productSetFacade;
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\StorageProductImageTransfer[]
     */
    public function getProductSetImageSets($idProductSet, $idLocale)
    {
        $productImageSetTransfers = $this->productSetFacade->getCombinedProductSetImageSets($idProductSet, $idLocale);

        $imageSets = [];
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $result[$productImageSetTransfer->getName()] = [];
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $storageProductImageTransfer = new StorageProductImageTransfer();
                $storageProductImageTransfer->fromArray($productImageTransfer->modifiedToArray(), true);

                $imageSets[$productImageSetTransfer->getName()][] = $storageProductImageTransfer->modifiedToArray();
            }
        }

        return $imageSets;
    }

}
