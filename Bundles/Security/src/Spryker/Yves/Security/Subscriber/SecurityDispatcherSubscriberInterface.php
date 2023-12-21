<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Subscriber;

use Spryker\Service\Container\ContainerInterface;

interface SecurityDispatcherSubscriberInterface
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public function addSubscriber(ContainerInterface $container): void;
}
