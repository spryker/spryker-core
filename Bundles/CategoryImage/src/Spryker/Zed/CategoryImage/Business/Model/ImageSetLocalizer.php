<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface;

class ImageSetLocalizer implements ImageSetLocalizerInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @param \Spryker\Zed\CategoryImage\Business\Provider\LocaleProviderInterface $localeProvider
     */
    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCategoryImageSetCollection(array $formImageSetCollection): array
    {
        $localizedImageSetCollection = [];
        foreach ($this->localeProvider->getLocaleCollection(true) as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $imageSetTransferCollection = $formImageSetCollection[$localeName] ?? [];

            /** @var \Generated\Shared\Transfer\CategoryImageSetTransfer $imageSetTransfer */
            foreach ($imageSetTransferCollection as $imageSetTransfer) {
                $imageSetTransfer->setLocale($localeTransfer);
                $localizedImageSetCollection[] = $imageSetTransfer;
            }
        }

        return $localizedImageSetCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFormImageSetCollection(array $categoryImageSetCollection): array
    {
        $localizedImageSetCollection = [];
        foreach ($this->localeProvider->getLocaleCollection(true) as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $localizedImageSetCollection[$localeName] = array_filter(
                $categoryImageSetCollection,
                function (CategoryImageSetTransfer $categoryImageSet) use ($localeName) {
                    $this->prepareLocale($categoryImageSet);

                    return $categoryImageSet->getLocale()->getLocaleName() === $localeName;
                }
            );
        }

        return $localizedImageSetCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    protected function prepareLocale(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer
    {
        if ($categoryImageSetTransfer->getLocale() === null) {
            $categoryImageSetTransfer->setLocale(
                $this->localeProvider->createDefaultLocale()
            );
        }

        return $categoryImageSetTransfer;
    }
}
