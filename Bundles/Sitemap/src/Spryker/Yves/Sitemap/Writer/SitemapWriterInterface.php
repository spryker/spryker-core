<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Writer;

interface SitemapWriterInterface
{
    /**
     * @param string $sitemapFileName
     * @param string $storeName
     * @param mixed $stream
     *
     * @return void
     */
    public function writeStream(string $sitemapFileName, string $storeName, $stream): void;
}
