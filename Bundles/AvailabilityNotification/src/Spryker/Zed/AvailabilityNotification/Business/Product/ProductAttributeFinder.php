<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;

class ProductAttributeFinder implements ProductAttributeFinderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     */
    public function __construct(AvailabilityNotificationToProductFacadeInterface $productFacade, UrlGeneratorInterface $urlGenerator)
    {
        $this->productFacade = $productFacade;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    public function findProductName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): ?string
    {
        $attributes = [];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $attributes = array_merge($attributes, $localizedAttributes->toArray());
            }
        }

        return $attributes['name'] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    public function findProductUrl(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): ?string
    {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());

        if ($productAbstractTransfer === null) {
            return null;
        }

        $productUrlTransfer = $this->productFacade->getProductUrl($productAbstractTransfer);

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            if ($localeTransfer->getIdLocale() === $localizedUrlTransfer->getLocale()->getIdLocale()) {
                return $this->urlGenerator->generateProductUrl($localizedUrlTransfer);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    public function findExternalProductImage(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $imageSetTransfer = current($productConcreteTransfer->getImageSets());

        if ($imageSetTransfer === false) {
            return null;
        }

        $productImageTransfer = current($imageSetTransfer->getProductImages());

        if ($productImageTransfer === false) {
            return null;
        }

        return $productImageTransfer->getExternalUrlLarge();
    }
}
