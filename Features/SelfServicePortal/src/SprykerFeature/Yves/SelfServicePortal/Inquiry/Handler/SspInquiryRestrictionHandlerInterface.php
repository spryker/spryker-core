<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

interface SspInquiryRestrictionHandlerInterface
{
    public function handleRestriction(ControllerEvent $event): void;
}
