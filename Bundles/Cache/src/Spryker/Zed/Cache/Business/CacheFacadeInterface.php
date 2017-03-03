<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

interface CacheFacadeInterface
{

    /**
     * @api
     *
     * @return array
     */
    public function deleteAllFiles();

    /**
     * @api
     *
     * @deprecated Please use emptyCache() instead
     *
     * @return string[]
     */
    public function emptyCache();

    /**
     * @api
     *
     * @deprecated Please use emptyAutoLoaderCache() instead
     *
     * @return array
     */
    public function deleteAllAutoloaderFiles();

    /**
     * @api
     *
     * @return string[]
     */
    public function emptyAutoLoaderCache();

}
