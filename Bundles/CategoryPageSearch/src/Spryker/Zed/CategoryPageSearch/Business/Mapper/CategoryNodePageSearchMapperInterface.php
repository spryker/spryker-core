<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Mapper;

use Generated\Shared\Transfer\CategoryNodePageSearchTransfer;
use Generated\Shared\Transfer\NodeTransfer;

interface CategoryNodePageSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\CategoryNodePageSearchTransfer $categoryNodePageSearchTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodePageSearchTransfer
     */
    public function mapNodeTransferToCategoryNodePageSearchTransferForStoreAndLocale(
        NodeTransfer $nodeTransfer,
        CategoryNodePageSearchTransfer $categoryNodePageSearchTransfer,
        string $storeName,
        string $localeName
    ): CategoryNodePageSearchTransfer;
}
