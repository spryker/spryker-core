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
     * @deprecated Use {@link emptyCache()} instead
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
     * Specification:
     * - Empties default codebucket cache directory.
     *
     * @api
     *
     * @return string
     */
    public function emptyDefaultCodeBucketCache(): string;

    /**
     * @api
     *
     * @deprecated Use {@link emptyAutoLoaderCache()} instead
     *
     * @return string[]
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

    /**
     * Specification:
     * - Empties configured project cache directories.
     *
     * @api
     *
     * @return string[]
     */
    public function emptyProjectCache(): array;
}
