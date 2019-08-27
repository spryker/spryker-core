<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin;

interface CmsBlockStorageRelatedBlocksFinderPluginInterface
{
    /**
     * Specification:
     * - Finds cms blocks by provided options.
     * - Returns CmsBlockTransfers with `key` parameter.
     *
     * @api
     *
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function findRelatedCmsBlocks(array $options): array;
}
