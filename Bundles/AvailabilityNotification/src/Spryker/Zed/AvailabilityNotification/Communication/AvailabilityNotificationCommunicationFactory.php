<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication;

use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 */
class AvailabilityNotificationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToGlossaryFacadeInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(AvailabilityNotificationDependencyProvider::FACADE_GLOSSARY);
    }
}
