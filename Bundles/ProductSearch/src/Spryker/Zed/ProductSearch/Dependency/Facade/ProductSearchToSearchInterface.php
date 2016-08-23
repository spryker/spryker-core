<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Dependency\Facade;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;

interface ProductSearchToSearchInterface
{

    /**
     * @param \Generated\Shared\Transfer\SearchConfigCacheTransfer $searchConfigCacheTransfer
     *
     * @return void
     */
    public function saveSearchConfigCache(SearchConfigCacheTransfer $searchConfigCacheTransfer);

}
