<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Updater;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;

class CategoryUrlUpdater implements CategoryUrlUpdaterInterface
{
    /**
     * @param array $paths
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function updateCategoryUrlPath(array $paths, LocaleTransfer $localeTransfer): array
    {
        $languageIdentifier = $this->getLanguageIdentifierFromLocale($localeTransfer);
        array_unshift(
            $paths,
            [
                UrlPathGenerator::CATEGORY_NAME => $languageIdentifier,
            ]
        );

        return $paths;
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
