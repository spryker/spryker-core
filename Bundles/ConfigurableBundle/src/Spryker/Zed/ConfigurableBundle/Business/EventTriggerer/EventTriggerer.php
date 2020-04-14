<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\EventTriggerer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface;

class EventTriggerer implements EventTriggererInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToEventFacadeInterface $eventFacade
     */
    public function __construct(ConfigurableBundleToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function triggerConfigurableBundleTemplatePublishEvent(int $idConfigurableBundleTemplate): void
    {
        $this->eventFacade->trigger(
            ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_PUBLISH,
            (new EventEntityTransfer())->setId($idConfigurableBundleTemplate)
        );
    }
}
