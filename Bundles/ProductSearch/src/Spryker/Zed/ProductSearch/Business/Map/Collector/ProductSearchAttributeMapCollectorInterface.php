<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Map\Collector;

interface ProductSearchAttributeMapCollectorInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    public function getProductSearchAttributeMap();
}
