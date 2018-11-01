<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationship\Dependency\Client;

interface PriceProductMerchantRelationshipToCartClientInterface
{
    /**
     * @return void
     */
    public function reloadItems(): void;
}
