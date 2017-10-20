<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSetGui\ProductSetGuiConfig;

abstract class AbstractProductSetFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductSetGui\ProductSetGuiConfig
     */
    protected $productSetGuiConfig;

    /**
     * @param \Spryker\Zed\ProductSetGui\ProductSetGuiConfig $productSetGuiConfig
     */
    public function __construct(ProductSetGuiConfig $productSetGuiConfig)
    {
        $this->productSetGuiConfig = $productSetGuiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return null|string
     */
    protected function getUrlPrefix(LocaleTransfer $localeTransfer)
    {
        if ($this->productSetGuiConfig->prependLocaleForProductSetUrl()) {
            return '/' . $this->extractLanguageCode($localeTransfer->getLocaleName()) . '/';
        }

        return null;
    }

    /**
     * @param string $localeName
     *
     * @return string
     */
    protected function extractLanguageCode($localeName)
    {
        $localeNameParts = explode('_', $localeName);
        $languageCode = $localeNameParts[0];

        return $languageCode;
    }
}
