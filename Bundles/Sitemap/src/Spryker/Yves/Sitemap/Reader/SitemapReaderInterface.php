<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Sitemap\Reader;

interface SitemapReaderInterface
{
    /**
     * @param string $sitemapFileName
     * @param string $storeName
     *
     * @return mixed
     */
    public function readStream(string $sitemapFileName, string $storeName): mixed;
}
