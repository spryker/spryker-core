<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\Dependency\Plugin\CategoryUrlPathPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class CategoryUrlPathPrefixUpdaterPlugin extends AbstractPlugin implements CategoryUrlPathPluginInterface
{
    /**
     * Specification:
     * - Update category url paths returned array
     *
     * @api
     *
     * @param array $paths
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function update(array $paths, LocaleTransfer $localeTransfer)
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
    protected function getLanguageIdentifierFromLocale(LocaleTransfer $localeTransfer)
    {
        return mb_substr($localeTransfer->getLocaleName(), 0, 2);
    }
}
