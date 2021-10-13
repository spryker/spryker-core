<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

interface CacheFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link emptyCache()} instead
     *
     * @return array<string>
     */
    public function deleteAllFiles();

    /**
     * Specification:
     * - Empties configured cache directory
     *
     * @api
     *
     * @return array<string>
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link emptyAutoLoaderCache()} instead
     *
     * @return array<string>
     */
    public function deleteAllAutoloaderFiles();

    /**
     * Specification:
     * - Empties configured auto-loader cache directory
     *
     * @api
     *
     * @return array<string>
     */
    public function emptyAutoLoaderCache();

    /**
     * Specification:
     * - Empties configured project specific cache directories.
     *
     * @api
     *
     * @return array<string>
     */
    public function emptyProjectSpecificCache(): array;
}
