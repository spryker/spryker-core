<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship;

use Spryker\Service\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getSharedConfig()
 */
class PriceProductMerchantRelationshipConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPriceDimensionMerchantRelationship()
    {
        return $this->getSharedConfig()->getPriceDimensionMerchantRelationship();
    }
}
