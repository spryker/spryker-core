<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Dashboard\Handler;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface SspDashboardRestrictionHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @return void
     */
    public function handleRestriction(ControllerEvent $event): void;
}
