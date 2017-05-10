<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Image;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface;

class ProductSetImageSaver implements ProductSetImageSaverInterface
{

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToProductImageInterface $productImageFacade
     */
    public function __construct(ProductSetToProductImageInterface $productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function saveImageSets(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $localizedProductSetTransfer->requireLocale();

        foreach ($localizedProductSetTransfer->getImageSets() as $productImageSetTransfer) {
            $productImageSetTransfer
                ->setLocale($localizedProductSetTransfer->getLocale())
                ->setFkResourceProductSet($idProductSet);
            $this->productImageFacade->saveProductImageSet($productImageSetTransfer);
        }

        return $localizedProductSetTransfer;
    }

}
