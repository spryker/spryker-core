<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationship\Communication\Hydrator\MerchantRelationshipHydrator;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationship\Communication\Hydrator\MerchantRelationshipHydrator
     */
    public function createMerchantRelationshipHydrator(): MerchantRelationshipHydrator
    {
        return new MerchantRelationshipHydrator(
            $this->getRepository()
        );
    }
}
