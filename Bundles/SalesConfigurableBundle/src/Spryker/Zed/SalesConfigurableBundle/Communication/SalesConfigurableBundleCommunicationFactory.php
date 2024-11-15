<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesConfigurableBundle\Communication\Adder\FlashMessageAdder;
use Spryker\Zed\SalesConfigurableBundle\Communication\Adder\FlashMessageAdderInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToMessengerFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleDependencyProvider;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 */
class SalesConfigurableBundleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Communication\Adder\FlashMessageAdderInterface
     */
    public function createFlashMessageAdder(): FlashMessageAdderInterface
    {
        return new FlashMessageAdder(
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToMessengerFacadeInterface
     */
    public function getMessengerFacade(): SalesConfigurableBundleToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleDependencyProvider::FACADE_MESSENGER);
    }
}
