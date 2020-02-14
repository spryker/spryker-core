<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceChecker;
use Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceCheckerInterface;
use Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface;
use Spryker\Zed\MerchantSwitcher\MerchantSwitcherDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Persistence\MerchantSwitcherEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSwitcher\Persistence\MerchantSwitcherRepositoryInterface getRepository()
 */
class MerchantSwitcherBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSwitcher\Business\MerchantReferenceChecker\MerchantReferenceCheckerInterface
     */
    public function createMerchantReferenceChecker(): MerchantReferenceCheckerInterface
    {
        return new MerchantReferenceChecker(
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSwitcher\Dependency\Facade\MerchantSwitcherToMessengerFacadeInterface
     */
    public function getMessengerFacade(): MerchantSwitcherToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::FACADE_MESSENGER);
    }
}
