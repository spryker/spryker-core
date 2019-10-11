<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin;

interface CmsBlockStorageReaderPluginInterface
{
    /**
     * Specification:
     * - Finds cms blocks by provided options.
     * - Returns CmsBlockTransfers with filled `key` property.
     *
     * @api
     *
     * @param array $cmsBlockOptions
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocks(array $cmsBlockOptions): array;
}
