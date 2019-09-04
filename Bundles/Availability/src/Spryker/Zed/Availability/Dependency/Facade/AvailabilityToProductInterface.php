<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

interface AvailabilityToProductInterface
{
    /**
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);
}
