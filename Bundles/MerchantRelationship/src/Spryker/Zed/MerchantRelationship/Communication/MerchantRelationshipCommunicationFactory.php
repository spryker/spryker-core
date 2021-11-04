<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationship\Communication\Hydrator\MerchantRelationshipHydrator;
use Spryker\Zed\MerchantRelationship\Communication\Hydrator\MerchantRelationshipHydratorInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 */
class MerchantRelationshipCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationship\Communication\Hydrator\MerchantRelationshipHydratorInterface
     */
    public function createMerchantRelationshipHydrator(): MerchantRelationshipHydratorInterface
    {
        return new MerchantRelationshipHydrator(
            $this->getRepository(),
        );
    }
}
