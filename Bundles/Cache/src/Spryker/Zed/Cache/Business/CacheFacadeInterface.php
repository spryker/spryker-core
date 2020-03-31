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
     * @deprecated Use emptyCache() instead
     *
     * @return string[]
     */
    public function deleteAllFiles();

    /**
     * Specification
     * - Empties configured cache directory
     *
     * @api
     *
     * @return string[]
     */
    public function emptyCache();

    /**
     * Specification:
     * - Empties configured cache directory for codebucket directory structure.
     *
     * @api
     *
     * @return string
     */
    public function emptyCodeBucketCache(): string;

    /**
     * @api
     *
     * @deprecated Use emptyAutoLoaderCache() instead
     *
     * @return string
     */
    public function deleteAllAutoloaderFiles();

    /**
     * Specification:
     * - Empties configured auto-loader cache directory
     *
     * @api
     *
     * @return string[]
     */
    public function emptyAutoLoaderCache();
}
