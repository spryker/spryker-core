<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantRelationship\Expander\MerchantRelationshipCartChangeExpander;
use Spryker\Client\MerchantRelationship\Expander\MerchantRelationshipCartChangeExpanderInterface;

class MerchantRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantRelationship\Expander\MerchantRelationshipCartChangeExpanderInterface
     */
    public function createMerchantRelationshipCartChangeExpander(): MerchantRelationshipCartChangeExpanderInterface
    {
        return new MerchantRelationshipCartChangeExpander();
    }
}
