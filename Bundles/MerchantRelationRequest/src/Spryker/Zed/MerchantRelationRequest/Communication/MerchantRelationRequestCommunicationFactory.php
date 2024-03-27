<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRelationRequest\Communication\Expander\MerchantRelationRequestAclEntityConfigurationExpander;
use Spryker\Zed\MerchantRelationRequest\Communication\Expander\MerchantRelationRequestAclEntityConfigurationExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface getFacade()
 */
class MerchantRelationRequestCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Communication\Expander\MerchantRelationRequestAclEntityConfigurationExpanderInterface
     */
    public function createMerchantRelationRequestAclEntityConfigurationExpander(): MerchantRelationRequestAclEntityConfigurationExpanderInterface
    {
        return new MerchantRelationRequestAclEntityConfigurationExpander();
    }
}
