<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\Transformer;

use ArrayObject;
use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Symfony\Component\Form\DataTransformerInterface;

class ImageSetCollectionTransformer implements DataTransformerInterface
{
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
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        $formImageSetCollection = [];
        foreach ($this->localeFacade->getAvailableLocales() as $localeName) {
            foreach ($value as $categoryImageSetTransfer) {
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
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $localizedImageSetCollection = [];
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
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
}
