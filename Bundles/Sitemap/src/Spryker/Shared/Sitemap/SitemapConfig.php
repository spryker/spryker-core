<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Sitemap;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SitemapConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Returns sitemap file name.
     *
     * @api
     *
     * @param string $storeName
     * @param string $fileName
     *
     * @return string
     */
    public function getFilePath(string $storeName, string $fileName): string
    {
        return sprintf(
            '%s%s%s',
            $storeName,
            DIRECTORY_SEPARATOR,
            $fileName,
        );
    }
}
