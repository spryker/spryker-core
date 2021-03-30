<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper;

use Generated\Shared\Transfer\NodeTransfer;

interface CategoryNodePageSearchDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return array
     */
    public function mapNodeTransferToCategoryNodePageSearchDataForStoreAndLocale(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName
    ): array;
}
