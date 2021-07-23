<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductMerchantRelationship\Filter\MerchantRelationshipPriceProductFilter;
use Spryker\Service\PriceProductMerchantRelationship\Filter\MerchantRelationshipPriceProductFilterInterface;

class PriceProductMerchantRelationshipServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductMerchantRelationship\Filter\MerchantRelationshipPriceProductFilterInterface
     */
    public function createMerchantRelationshipPriceProductFilter(): MerchantRelationshipPriceProductFilterInterface
    {
        return new MerchantRelationshipPriceProductFilter();
    }
}
