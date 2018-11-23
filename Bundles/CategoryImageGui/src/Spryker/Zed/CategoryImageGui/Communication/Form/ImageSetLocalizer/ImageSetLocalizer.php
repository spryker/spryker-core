<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\ImageSetLocalizer;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider\LocaleProviderInterface;

class ImageSetLocalizer implements ImageSetLocalizerInterface
{
    /**
     * @var \Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider\LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @param \Spryker\Zed\CategoryImageGui\Communication\Form\DataProvider\LocaleProviderInterface $localeProvider
     */
    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param array $formImageSetCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function buildCategoryImageSetCollectionFromLocalizedArray(array $formImageSetCollection): array
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
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    public function buildLocalizedArrayFromImageSetCollection(array $categoryImageSetCollection): array
    {
        $formImageSetCollection = [];
        foreach ($this->localeProvider->getLocaleCollection(true) as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $formImageSetCollection[$localeName] = array_filter(
                $categoryImageSetCollection,
                function (CategoryImageSetTransfer $categoryImageSet) use ($localeName) {
                    $this->prepareLocale($categoryImageSet);

                    return $categoryImageSet->getLocale()->getLocaleName() === $localeName;
                }
            );
        }

        return $formImageSetCollection;
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
