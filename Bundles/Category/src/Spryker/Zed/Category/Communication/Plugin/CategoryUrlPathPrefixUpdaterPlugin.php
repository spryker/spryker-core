<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class CategoryUrlPathPrefixUpdaterPlugin extends AbstractPlugin implements CategoryUrlPathPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds language identifier to category url paths.
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
        return $this->getFactory()
            ->createCategoryUrlUpdater()
            ->updateCategoryUrlPath($paths, $localeTransfer);
    }
}
