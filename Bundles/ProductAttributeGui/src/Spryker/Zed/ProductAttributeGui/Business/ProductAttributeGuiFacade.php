<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiFacade extends AbstractFacade implements ProductAttributeGuiFacadeInterface
{

    /**
     * @api
     *
     * @deprecated Use emptyCache() instead
     *
     * @return array
     */
    public function deleteAllFiles()
    {
        return $this->getFactory()->createCacheDelete()->deleteAllFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function emptyCache()
    {
        return $this->getFactory()->createCacheClearer()->clearCache();
    }

    /**
     * @api
     *
     * @deprecated Use emptyAutoLoaderCache() instead
     *
     * @return array
     */
    public function deleteAllAutoloaderFiles()
    {
        return $this->getFactory()->createAutoloaderCacheDelete()->deleteAllFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function emptyAutoLoaderCache()
    {
        return $this->getFactory()->createCacheClearer()->clearAutoLoaderCache();
    }

}
