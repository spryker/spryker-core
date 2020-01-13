<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriter;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface getRepository()
 */
class MerchantUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface
     */
    public function createMerchantUserWriter(): MerchantUserWriterInterface
    {
        return new MerchantUserWriter(
            $this->getUserFacade(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    public function getUserFacade(): MerchantUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_USER);
    }
}
