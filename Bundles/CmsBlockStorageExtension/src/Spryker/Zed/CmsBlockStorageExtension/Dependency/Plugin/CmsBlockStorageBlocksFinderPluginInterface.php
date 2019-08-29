<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin;

interface CmsBlockStorageBlocksFinderPluginInterface
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
    public function getRelatedCmsBlocks(array $cmsBlockOptions): array;
}
