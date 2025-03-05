<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Dependency\Service;

interface SitemapToFlysystemServiceInterface
{
    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return mixed
     */
    public function readStream(string $filesystemName, string $path): mixed;

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|null
     */
    public function getTimestamp(string $filesystemName, string $path): ?int;

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has(string $filesystemName, string $path): bool;
}
