<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication;

use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    public function getStoreFacade(): AvailabilityToStoreFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityDependencyProvider::FACADE_STORE);
    }
}
