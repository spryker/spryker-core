<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationship;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProductMerchantRelationship\Dependency\Client\PriceProductMerchantRelationshipToCartClientInterface;

class PriceProductMerchantRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProductMerchantRelationship\Dependency\Client\PriceProductMerchantRelationshipToCartClientInterface
     */
    public function getCartClient(): PriceProductMerchantRelationshipToCartClientInterface
    {
        return $this->getProvidedDependency(PriceProductMerchantRelationshipDependencyProvider::CLIENT_CART);
    }
}
