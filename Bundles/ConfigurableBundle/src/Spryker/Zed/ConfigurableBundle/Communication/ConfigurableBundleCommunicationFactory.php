<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Communication;

use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleFacadeInterface getFacade()
 */
class ConfigurableBundleCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::FACADE_LOCALE);
    }
}
