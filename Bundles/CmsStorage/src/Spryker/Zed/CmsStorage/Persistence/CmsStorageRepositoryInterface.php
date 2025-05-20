<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\CmsStorage\Persistence;

use Generator;

interface CmsStorageRepositoryInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\CmsStorage\Persistence\CmsStorageRepositoryInterface::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array;

    /**
     * @param string $storeName
     * @param int $limit
     *
     * @return \Generator
     */
    public function getSitemapGeneratorUrls(string $storeName, int $limit): Generator;
}
