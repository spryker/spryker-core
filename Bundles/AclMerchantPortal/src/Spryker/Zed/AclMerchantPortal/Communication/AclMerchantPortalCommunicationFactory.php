<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Communication;

use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalFacadeInterface getFacade()
 */
class AclMerchantPortalCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    public function getAclFacade(): AclMerchantPortalToAclFacadeInterface
    {
        return $this->getProvidedDependency(AclMerchantPortalDependencyProvider::FACADE_ACL);
    }
}
