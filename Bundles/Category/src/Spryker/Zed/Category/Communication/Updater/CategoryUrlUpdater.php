<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Updater;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\CategoryConfig;

class CategoryUrlUpdater implements CategoryUrlUpdaterInterface
{
    protected CategoryConfig $categoryConfig;

    /**
     * @param \Spryker\Zed\Category\CategoryConfig $categoryConfig
     */
    public function __construct(CategoryConfig $categoryConfig)
    {
        $this->categoryConfig = $categoryConfig;
    }

    /**
     * @param array $paths
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function updateCategoryUrlPath(array $paths, LocaleTransfer $localeTransfer): array
    {
        $urlLocalizedPrefix = $this->getUrlLocalizedPrefix($localeTransfer);
        array_unshift(
            $paths,
            [
                UrlPathGenerator::CATEGORY_NAME => $urlLocalizedPrefix,
            ],
        );

        return $paths;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getUrlLocalizedPrefix(LocaleTransfer $localeTransfer): string
    {
        if (!$this->categoryConfig->isFullLocaleNamesInUrlEnabled()) {
            return $this->getLanguageIdentifierFromLocale($localeTransfer);
        }

        return str_replace('_', '-', strtolower($localeTransfer->getLocaleNameOrFail()));
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getLanguageIdentifierFromLocale(LocaleTransfer $localeTransfer): string
    {
        return mb_substr($localeTransfer->getLocaleNameOrFail(), 0, 2);
    }
}
