<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Handler;

use Symfony\Component\Form\FormEvent;

interface SingleAddressPerShipmentTypePreSubmitHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function handlePreSubmit(FormEvent $event): void;
}
