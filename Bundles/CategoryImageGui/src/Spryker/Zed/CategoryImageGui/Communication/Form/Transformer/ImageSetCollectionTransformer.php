<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Symfony\Component\Form\DataTransformerInterface;

class ImageSetCollectionTransformer implements DataTransformerInterface
{
    public const DEFAULT_LOCALE_NAME = 'default';

    /**
     * @var \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[]|\ArrayObject $value The value in the original representation
     *
     * @return array|null The value in the transformed representation
     *
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        $formImageSetCollection = [];
        foreach ($this->getLocales() as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            foreach ($value as $categoryImageSetTransfer) {
                $this->prepareLocale($categoryImageSetTransfer);
                if ($categoryImageSetTransfer->getLocale()->getLocaleName() === $localeName) {
                    $formImageSetCollection[$localeName][] = $categoryImageSetTransfer;
                }
            }
        }

        return $formImageSetCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @param array|null $value The value in the transformed representation
     *
     * @return \ArrayObject|null The value in the original representation
     *
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $localizedImageSetCollection = [];
        foreach ($this->getLocales() as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();
            $imageSetTransferCollection = $value[$localeName] ?? [];

            /** @var \Generated\Shared\Transfer\CategoryImageSetTransfer $imageSetTransfer */
            foreach ($imageSetTransferCollection as $imageSetTransfer) {
                $imageSetTransfer->setLocale($localeTransfer);
                $localizedImageSetCollection[] = $imageSetTransfer;
            }
        }

        return new ArrayObject($localizedImageSetCollection);
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
                $this->createDefaultLocale()
            );
        }

        return $categoryImageSetTransfer;
    }

    /**
     * Gets the array of locale transfers based on the store config plus the 'default' locale.
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getLocales(): array
    {
        return array_merge([$this->createDefaultLocale()], $this->localeFacade->getLocaleCollection());
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createDefaultLocale(): LocaleTransfer
    {
        return (new LocaleTransfer())
            ->setLocaleName(static::DEFAULT_LOCALE_NAME);
    }
}
