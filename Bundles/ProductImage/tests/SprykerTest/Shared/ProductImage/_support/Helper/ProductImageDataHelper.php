<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductImage\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductImageDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    public const URL_SMALL = 'small';
    public const URL_LARGE = 'large';
    public const NAME = 'set';
    public const SORT_ORDER = 1;

    /**
     * @param array $productImageSetOverride
     * @param array $productImageOverride
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function haveProductImageSet(array $productImageSetOverride = [], array $productImageOverride = []): ProductImageSetTransfer
    {
        $productImageFacade = $this->getProductImageFacade();

        $productImageSeed = [
            ProductImageTransfer::EXTERNAL_URL_SMALL => static::URL_SMALL,
            ProductImageTransfer::EXTERNAL_URL_LARGE => static::URL_LARGE,
            ProductImageTransfer::SORT_ORDER => static::SORT_ORDER,
        ];

        $productImageTransfer = (new ProductImageBuilder())
            ->seed($productImageOverride + $productImageSeed)
            ->build();

        $productImageTransfer->setExternalUrlLarge(static::URL_SMALL)
            ->setExternalUrlSmall(static::URL_LARGE);

        $productImageSetSeed = [
            ProductImageSetTransfer::NAME => static::NAME,
            ProductImageSetTransfer::LOCALE => $this->getLocaleFacade()->getCurrentLocale(),
        ];

        $productImageSetTransfer = (new ProductImageSetBuilder())
            ->seed($productImageSetOverride + $productImageSetSeed)
            ->build();

        $productImages = $productImageSetOverride[ProductImageSetTransfer::PRODUCT_IMAGES] ?? [$productImageTransfer];
        $productImageSetTransfer->setProductImages(new ArrayObject($productImages));

        $productImageSetTransfer = $productImageFacade->saveProductImageSet($productImageSetTransfer);

        $this->debug(sprintf(
            'Inserted Product Image Set: %d',
            $productImageSetTransfer->getIdProductImageSet()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productImageSetTransfer) {
            $this->cleanupProductImageSet($productImageSetTransfer);
        });

        return $productImageSetTransfer;
    }

    /**
     * @param int $idProductImageSet
     * @param int $sortOrder
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    public function createProductImageSetToProductImage(
        int $idProductImageSet,
        int $sortOrder
    ): SpyProductImageSetToProductImage {
        $productImage = new SpyProductImage();
        $productImage
            ->setExternalUrlLarge(static::URL_LARGE)
            ->setExternalUrlSmall(static::URL_SMALL)
            ->save();

        $productImageSetToProductImage = new SpyProductImageSetToProductImage();
        $productImageSetToProductImage
            ->setFkProductImage($productImage->getIdProductImage())
            ->setFkProductImageSet($idProductImageSet)
            ->setSortOrder($sortOrder)
            ->save();

        return $productImageSetToProductImage;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    private function cleanupProductImageSet(ProductImageSetTransfer $productImageSetTransfer): void
    {
        $this->debug(
            sprintf('Deleting Product Image Set: %d', $productImageSetTransfer->getIdProductImageSet())
        );

        $this->getProductImageFacade()->deleteProductImageSet($productImageSetTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected function getProductImageFacade(): ProductImageFacadeInterface
    {
        return $this->getLocator()->productImage()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
